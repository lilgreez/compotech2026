<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class RbacController extends Controller
{
    /**
     * Display the Role Management CRUD Index Table.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $tab = $request->input('tab', 'all');
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        // Global KPI Metrics
        $totalRoles = Role::count();
        $totalPermissions = Permission::count();
        $totalUsers = User::count();

        $query = Role::withCount('users')->with('permissions');

        if ($tab === 'system') {
            $query->where('name', 'Admin');
        } elseif ($tab === 'operational') {
            $query->where('name', '!=', 'Admin');
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $roles = $query->orderBy('id', 'asc')->paginate($perPage)->appends(request()->query());

        // All permissions grouped by module for create/edit modal forms
        $permissions = Permission::all();
        $groupedPermissions = $permissions->groupBy('module');

        return view('admin.rbac.index', compact(
            'roles',
            'permissions',
            'groupedPermissions',
            'search',
            'tab',
            'perPage',
            'totalRoles',
            'totalPermissions',
            'totalUsers'
        ));
    }

    /**
     * Store a new custom role.
     */
    public function storeRole(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:roles,name',
            'description' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $role = Role::create([
                'name' => trim($validated['name']),
                'description' => $validated['description'] ?? null,
            ]);

            if ($request->has('permissions')) {
                $role->permissions()->sync($request->permissions);
            }
        });

        return redirect()->route('rbac.index')
            ->with('success', "Role '{$request->name}' berhasil ditambahkan!");
    }

    /**
     * Update an existing role and its permissions.
     */
    public function updateRole(Request $request, $id): RedirectResponse
    {
        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:roles,name,' . $role->id,
            'description' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Safety: Admin superuser role name cannot be renamed
        if ($role->name === 'Admin' && strtolower($validated['name']) !== 'admin') {
            return back()->with('error', "Nama role 'Admin' tidak dapat diubah karena merupakan superuser sistem.");
        }

        DB::transaction(function () use ($role, $validated, $request) {
            $role->update([
                'name' => trim($validated['name']),
                'description' => $validated['description'] ?? null,
            ]);

            // Sync permissions (if Admin, always keep all permissions)
            if ($role->name === 'Admin') {
                $role->permissions()->sync(Permission::all());
            } else {
                $role->permissions()->sync($request->input('permissions', []));
            }
        });

        return redirect()->route('rbac.index')
            ->with('success', "Role '{$role->name}' dan hak aksesnya berhasil diperbarui!");
    }

    /**
     * Delete a role.
     */
    public function destroyRole($id): RedirectResponse
    {
        $role = Role::withCount('users')->findOrFail($id);

        // Core Admin superuser role cannot be deleted
        if ($role->name === 'Admin') {
            return back()->with('error', "Role 'Admin' adalah superuser sistem utama dan tidak dapat dihapus.");
        }

        // Cannot delete role if currently assigned to any active user
        if ($role->users_count > 0) {
            return back()->with('error', "Gagal menghapus role '{$role->name}'. Masih terdapat {$role->users_count} pengguna aktif yang memiliki role ini.");
        }

        $roleName = $role->name;
        $role->permissions()->detach();
        $role->delete();

        return redirect()->route('rbac.index')
            ->with('success', "Role '{$roleName}' berhasil dihapus!");
    }
}

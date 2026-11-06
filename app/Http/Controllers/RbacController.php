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
     * Display the RBAC management interface.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search_user');
        $activeTab = $request->input('tab', 'roles');

        // Roles with user counts and permissions
        $roles = Role::withCount('users')->with('permissions')->get();

        // All permissions grouped by module
        $permissions = Permission::all();
        $groupedPermissions = $permissions->groupBy('module');

        // User management query with assigned roles
        $userQuery = User::with('roles');
        if ($search) {
            $userQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhereHas('roles', function ($rq) use ($search) {
                      $rq->where('name', 'like', "%{$search}%");
                  });
            });
        }
        $users = $userQuery->orderBy('id', 'asc')->paginate(15)->appends(['tab' => 'users', 'search_user' => $search]);

        // KPI Summary Stats
        $stats = [
            'total_roles' => $roles->count(),
            'total_users' => User::count(),
            'total_permissions' => $permissions->count(),
            'admin_users' => User::whereHas('roles', fn($q) => $q->where('name', 'Admin'))->count(),
        ];

        return view('admin.rbac.index', compact(
            'roles',
            'permissions',
            'groupedPermissions',
            'users',
            'stats',
            'activeTab',
            'search'
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

        return redirect()->route('rbac.index', ['tab' => 'roles'])
            ->with('success', "Role '{$request->name}' berhasil ditambahkan.");
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

        // Protected role: Admin name cannot be changed
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

        return redirect()->route('rbac.index', ['tab' => 'roles'])
            ->with('success', "Role '{$role->name}' dan hak aksesnya berhasil diperbarui.");
    }

    /**
     * Delete a custom role.
     */
    public function destroyRole($id): RedirectResponse
    {
        $role = Role::withCount('users')->findOrFail($id);

        // Core system roles cannot be deleted
        $protectedRoles = ['Admin', 'Supervisor', 'Maintenance', 'Operator'];
        if (in_array($role->name, $protectedRoles)) {
            return back()->with('error', "Role '{$role->name}' adalah role bawaan sistem dan tidak boleh dihapus.");
        }

        // Cannot delete role if assigned to any user
        if ($role->users_count > 0) {
            return back()->with('error', "Gagal menghapus role '{$role->name}'. Masih terdapat {$role->users_count} user yang memiliki role ini.");
        }

        $roleName = $role->name;
        $role->permissions()->detach();
        $role->delete();

        return redirect()->route('rbac.index', ['tab' => 'roles'])
            ->with('success', "Role '{$roleName}' berhasil dihapus.");
    }

    /**
     * Update user's assigned role(s).
     */
    public function updateUserRole(Request $request, $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $request->validate([
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
        ]);

        // Safety: Ensure user 1 always retains Admin role
        if ($user->id === 1) {
            $adminRole = Role::where('name', 'Admin')->first();
            if ($adminRole && !in_array($adminRole->id, $request->roles)) {
                return back()->with('error', "Role 'Admin' pada Super Admin utama tidak dapat dicabut.");
            }
        }

        $user->roles()->sync($request->roles);

        return redirect()->route('rbac.index', ['tab' => 'users'])
            ->with('success', "Role untuk pengguna '{$user->name}' berhasil diperbarui.");
    }

    /**
     * Batch update permissions across roles from the matrix view.
     */
    public function syncMatrix(Request $request): RedirectResponse
    {
        $matrix = $request->input('matrix', []); // format: [role_id => [perm_id_1, perm_id_2, ...]]

        DB::transaction(function () use ($matrix) {
            $allRoles = Role::all();
            foreach ($allRoles as $role) {
                // If role is Admin, always retain all permissions
                if ($role->name === 'Admin') {
                    $role->permissions()->sync(Permission::all());
                    continue;
                }

                $rolePerms = $matrix[$role->id] ?? [];
                $role->permissions()->sync($rolePerms);
            }
        });

        return redirect()->route('rbac.index', ['tab' => 'matrix'])
            ->with('success', "Matrix hak akses permission untuk seluruh role berhasil disimpan.");
    }
}

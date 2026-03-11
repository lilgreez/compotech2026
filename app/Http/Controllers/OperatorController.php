<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class OperatorController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        // Hanya mengambil user yang memiliki role 'Operator'
        $query = User::whereHas('roles', function ($q) {
            $q->where('name', 'Operator');
        });

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
            });
        }

        $operators = $query->orderBy('id', 'desc')->paginate($perPage);

        return view('admin.operators.index', compact('operators', 'search', 'perPage'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|max:255|unique:users,nik',
            'name' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Buat User Baru
        $user = User::create([
            'nik' => $request->nik,
            'name' => $request->name,
            'email' => $request->nik . '@compotech.local', // Email dummy karena login HP pakai NIK
            'department' => $request->department,
            'password' => Hash::make($request->password),
        ]);

        // Berikan Role 'Operator'
        $operatorRole = Role::where('name', 'Operator')->first();
        if ($operatorRole) {
            $user->roles()->attach($operatorRole->id);
        }

        return back()->with('success', 'Data Operator berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $operator = User::findOrFail($id);

        $request->validate([
            'nik' => 'required|string|max:255|unique:users,nik,' . $operator->id,
            'name' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'password' =>['nullable', 'string', 'min:8', 'confirmed'], // Password opsional saat edit
        ]);

        $operator->nik = $request->nik;
        $operator->name = $request->name;
        $operator->department = $request->department;
        
        // Jika password diisi, maka update passwordnya
        if ($request->filled('password')) {
            $operator->password = Hash::make($request->password);
        }

        $operator->save();

        return back()->with('success', 'Data Operator berhasil diperbarui!');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Data Operator berhasil dihapus!');
    }
}
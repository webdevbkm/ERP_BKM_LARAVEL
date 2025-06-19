<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Warehouse; // Import model Warehouse
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        // Muat relasi warehouse untuk ditampilkan di tabel
        $users = User::with('roles', 'warehouse')->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $warehouses = Warehouse::where('is_active', true)->get(); // Ambil semua gudang aktif
        return view('admin.users.create', compact('roles', 'warehouses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'roles' => ['required', 'array'],
            'warehouse_id' => ['nullable', 'exists:warehouses,id'] // Validasi warehouse_id
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'warehouse_id' => $request->warehouse_id, // Simpan warehouse_id
        ]);

        $user->syncRoles($request->roles);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna baru berhasil dibuat.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $warehouses = Warehouse::where('is_active', true)->get();
        $userRoles = $user->roles->pluck('name')->toArray();
        return view('admin.users.edit', compact('user', 'roles', 'warehouses', 'userRoles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class.',email,'.$user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'roles' => ['required', 'array'],
            'warehouse_id' => ['nullable', 'exists:warehouses,id']
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->warehouse_id = $request->warehouse_id; // Update warehouse_id

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        $user->syncRoles($request->roles);

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if (auth()->id() == $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}

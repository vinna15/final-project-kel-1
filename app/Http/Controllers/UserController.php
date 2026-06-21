<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['branch', 'roles'])->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $branches = Branch::all();
        $roles    = Role::all();
        return view('users.create', compact('branches', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:8|confirmed',
            'branch_id' => 'nullable|exists:branches,id',
            'role'      => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'branch_id' => $validated['branch_id'],
        ]);
        $user->assignRole($validated['role']);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $branches = Branch::all();
        $roles    = Role::all();
        return view('users.edit', compact('user', 'branches', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'password'  => 'nullable|string|min:8|confirmed',
            'branch_id' => 'nullable|exists:branches,id',
            'role'      => 'required|exists:roles,name',
        ]);

        $user->update([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'branch_id' => $validated['branch_id'],
            'password'  => $validated['password'] ? Hash::make($validated['password']) : $user->password,
        ]);
        $user->syncRoles([$validated['role']]);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
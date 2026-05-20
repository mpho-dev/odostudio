<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $users = User::with('roles')->latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'roles' => ['required', 'array'],
            'crew_specialty' => ['nullable', 'in:photographer,videographer,both'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'must_change_password' => true,
            'crew_specialty' => $validated['crew_specialty'] ?? null,
        ]);

        $user->syncRoles($validated['roles']);

        return redirect()->route('admin.users.index')->with('success', 'Specialist identity initialized.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'roles' => ['required', 'array'],
            'crew_specialty' => ['nullable', 'in:photographer,videographer,both'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'crew_specialty' => $validated['crew_specialty'] ?? null,
        ]);

        $user->syncRoles($validated['roles']);

        return redirect()->route('admin.users.index')->with('success', 'Specialist identity synchronized.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['self_delete' => 'Self-termination not permitted in this sequence.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Specialist identity terminated.');
    }
}

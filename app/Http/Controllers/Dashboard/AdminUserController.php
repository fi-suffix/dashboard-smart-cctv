<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(): View
    {
        $admins = User::orderBy('name')->paginate(20);

        return view('dashboard.admin.index', compact('admins'));
    }

    public function create(): View
    {
        return view('dashboard.admin.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required', 'in:admin,superadmin'],
        ]);

        User::create($data);

        return redirect()->route('dashboard.admin.index')
            ->with('success', 'Admin account created successfully.');
    }

    public function edit(User $user): View
    {
        return view('dashboard.admin.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'role' => ['required', 'in:admin,superadmin'],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('dashboard.admin.index')
            ->with('success', 'Admin account updated successfully.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'You cannot delete the account you are currently using.');
        }

        $user->delete();

        return redirect()->route('dashboard.admin.index')
            ->with('success', 'Admin account deleted successfully.');
    }
}
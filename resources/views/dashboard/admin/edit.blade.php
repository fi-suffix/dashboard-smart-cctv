@extends('layouts.dashboard')

@section('title', 'Edit Admin')
@section('page-title', 'Admin Accounts / Edit Admin')
@section('timestamp', now()->format('M d, Y — h:i A'))

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('dashboard.admin.update', $user) }}" class="bg-dark-card border border-border-subtle rounded-xl p-6 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block text-sm font-medium mb-1.5">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required
                   class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none placeholder:text-text-secondary">
            @error('name')
                <p class="text-xs text-danger mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="username" class="block text-sm font-medium mb-1.5">Username</label>
                <input id="username" type="text" name="username" value="{{ old('username', $user->username) }}" required autocomplete="off"
                       class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none placeholder:text-text-secondary">
                @error('username')
                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium mb-1.5">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="off"
                       class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none placeholder:text-text-secondary">
                @error('email')
                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="password" class="block text-sm font-medium mb-1.5">New Password <span class="text-text-secondary font-normal">(leave blank to keep)</span></label>
                <input id="password" type="password" name="password" autocomplete="new-password"
                       class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none placeholder:text-text-secondary">
                @error('password')
                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium mb-1.5">Confirm New Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password"
                       class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none placeholder:text-text-secondary">
            </div>
        </div>

        <div>
            <label for="role" class="block text-sm font-medium mb-1.5">Role</label>
            <select id="role" name="role" class="w-full max-w-xs rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                <option value="superadmin" @selected(old('role', $user->role) === 'superadmin')>Super Admin</option>
            </select>
            @error('role')
                <p class="text-xs text-danger mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('dashboard.admin.index') }}" class="px-4 py-2 text-sm font-medium text-text-secondary hover:text-text-primary">Cancel</a>
            <button type="submit" class="px-4 py-2 rounded-lg bg-accent-blue hover:bg-accent-blue-hover text-white text-sm font-medium transition-colors">Save Changes</button>
        </div>
    </form>
</div>
@endsection
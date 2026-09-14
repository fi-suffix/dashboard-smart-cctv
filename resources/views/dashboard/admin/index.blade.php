@extends('layouts.dashboard')

@section('title', 'Admin Accounts')
@section('page-title', 'Admin Accounts')
@section('timestamp', now()->format('M d, Y — h:i A'))

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-base font-semibold">Manage Admin Accounts</h2>
            <p class="text-xs text-text-secondary">{{ $admins->total() }} admin account(s)</p>
        </div>
        <a href="{{ route('dashboard.admin.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-accent-blue hover:bg-accent-blue-hover text-white text-sm font-medium rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Admin
        </a>
    </div>

    <div class="bg-dark-card border border-border-subtle rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-dark-elevated/50 text-text-secondary text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3 font-medium">Name</th>
                        <th class="px-4 py-3 font-medium">Username</th>
                        <th class="px-4 py-3 font-medium">Email</th>
                        <th class="px-4 py-3 font-medium">Role</th>
                        <th class="px-4 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-subtle">
                    @forelse ($admins as $admin)
                        <tr class="hover:bg-dark-elevated/30 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-accent-blue/20 flex items-center justify-center text-accent-blue text-xs font-semibold uppercase">
                                        {{ collect(explode(' ', $admin->name))->map(fn ($part) => substr($part, 0, 1))->take(2)->join('') }}
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ $admin->name }}</p>
                                        @if ($admin->id === auth()->id())
                                            <p class="text-xs text-accent-blue">You</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-text-secondary font-mono">{{ $admin->username }}</td>
                            <td class="px-4 py-3 text-text-secondary">{{ $admin->email }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2 py-0.5 rounded-full bg-accent-blue/10 text-accent-blue text-xs font-medium">{{ $admin->role_label }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('dashboard.admin.edit', $admin) }}" class="px-3 py-1.5 text-xs font-medium text-text-secondary hover:text-text-primary bg-dark-elevated/50 border border-border-subtle rounded-lg transition-colors">Edit</a>
                                    @if ($admin->id !== auth()->id())
                                        <form method="POST" action="{{ route('dashboard.admin.destroy', $admin) }}" onsubmit="return confirm('Delete this admin account?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 text-xs font-medium text-danger bg-danger/10 hover:bg-danger/20 rounded-lg transition-colors">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-text-secondary">No admin accounts found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($admins->hasPages())
            <div class="p-4 border-t border-border-subtle">
                {{ $admins->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
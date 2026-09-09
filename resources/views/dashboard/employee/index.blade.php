@extends('layouts.dashboard')

@section('title', 'Employees')
@section('page-title', 'Employees')
@section('timestamp', 'Oct 24, 2025 — 10:42 AM')

@section('content')
    <div class="bg-dark-card border border-border-subtle rounded-xl overflow-hidden">
        <div class="flex items-center justify-between p-4 border-b border-border-subtle">
            <div>
                <h2 class="text-base font-semibold">Employee Registry</h2>
                <p class="text-xs text-text-secondary">Manage registered employees and face profiles</p>
            </div>
            <a href="{{ route('dashboard.employee.create') }}" class="px-4 py-2 bg-accent-blue hover:bg-accent-blue-hover text-white text-sm font-medium rounded-lg transition-colors">
                Add Employee
            </a>
        </div>

        @if (session('success'))
            <div class="m-4 rounded-lg border border-success/30 bg-success/10 px-4 py-3 text-sm text-success" role="status">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-dark-elevated/50 text-text-secondary text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3 font-medium">Employee</th>
                        <th class="px-4 py-3 font-medium">ID</th>
                        <th class="px-4 py-3 font-medium">Department</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium">Recognitions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-subtle">
                    @forelse ($employees as $employee)
                        <tr class="hover:bg-dark-elevated/30 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-accent-blue/20 flex items-center justify-center text-accent-blue text-xs font-semibold">{{ collect(explode(' ', $employee->name))->map(fn ($part) => substr($part, 0, 1))->take(2)->join('') }}</div>
                                    <span class="font-medium">{{ $employee->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-text-secondary">#{{ $employee->employee_code }}</td>
                            <td class="px-4 py-3 text-text-secondary">{{ $employee->department }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2 py-0.5 rounded-full {{ $employee->status === 'active' ? 'bg-success/10 text-success' : 'bg-text-secondary/10 text-text-secondary' }} text-xs font-medium">{{ ucfirst($employee->status) }}</span>
                            </td>
                            <td class="px-4 py-3 text-text-secondary">{{ number_format($employee->recognitions) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-text-secondary">No employees registered yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

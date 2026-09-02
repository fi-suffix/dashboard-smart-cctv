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
            <button class="px-4 py-2 bg-accent-blue hover:bg-accent-blue-hover text-white text-sm font-medium rounded-lg transition-colors">
                Add Employee
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-dark-elevated/50 text-text-secondary text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3 font-medium">Employee</th>
                        <th class="px-4 py-3 font-medium">ID</th>
                        <th class="px-4 py-3 font-medium">Department</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium">Recognitions</th>
                        <th class="px-4 py-3 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-subtle">
                    <tr class="hover:bg-dark-elevated/30 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-accent-blue/20 flex items-center justify-center text-accent-blue text-xs font-semibold">AK</div>
                                <span class="font-medium">Alexander K.</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-text-secondary">#EMP-001</td>
                        <td class="px-4 py-3 text-text-secondary">Security</td>
                        <td class="px-4 py-3"><span class="inline-flex px-2 py-0.5 rounded-full bg-success/10 text-success text-xs font-medium">Active</span></td>
                        <td class="px-4 py-3 text-text-secondary">1,024</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <button class="p-1.5 rounded hover:bg-dark-elevated text-text-secondary hover:text-text-primary transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                                </button>
                                <button class="p-1.5 rounded hover:bg-dark-elevated text-text-secondary hover:text-danger transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.061-.94-1.75-1.975-1.75H9.975C8.94 3 8 3.69 8 4.75v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-dark-elevated/30 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-success/20 flex items-center justify-center text-success text-xs font-semibold">SM</div>
                                <span class="font-medium">Sarah M.</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-text-secondary">#EMP-002</td>
                        <td class="px-4 py-3 text-text-secondary">Operations</td>
                        <td class="px-4 py-3"><span class="inline-flex px-2 py-0.5 rounded-full bg-success/10 text-success text-xs font-medium">Active</span></td>
                        <td class="px-4 py-3 text-text-secondary">892</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <button class="p-1.5 rounded hover:bg-dark-elevated text-text-secondary hover:text-text-primary transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                                </button>
                                <button class="p-1.5 rounded hover:bg-dark-elevated text-text-secondary hover:text-danger transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.061-.94-1.75-1.975-1.75H9.975C8.94 3 8 3.69 8 4.75v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-dark-elevated/30 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-accent-blue/20 flex items-center justify-center text-accent-blue text-xs font-semibold">RJ</div>
                                <span class="font-medium">Robert J.</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-text-secondary">#EMP-003</td>
                        <td class="px-4 py-3 text-text-secondary">IT</td>
                        <td class="px-4 py-3"><span class="inline-flex px-2 py-0.5 rounded-full bg-success/10 text-success text-xs font-medium">Active</span></td>
                        <td class="px-4 py-3 text-text-secondary">756</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <button class="p-1.5 rounded hover:bg-dark-elevated text-text-secondary hover:text-text-primary transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                                </button>
                                <button class="p-1.5 rounded hover:bg-dark-elevated text-text-secondary hover:text-danger transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.061-.94-1.75-1.975-1.75H9.975C8.94 3 8 3.69 8 4.75v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

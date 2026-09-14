@extends('layouts.dashboard')

@section('title', 'Edit Employee')
@section('page-title', 'Employees / Edit')
@section('timestamp', now()->format('M d, Y — h:i A'))

@section('content')
    <form method="POST" action="{{ route('dashboard.employee.update', $employee) }}" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        @csrf
        @method('PUT')

        {{-- Kolom Kiri: Employee Information --}}
        <div class="lg:col-span-7 bg-dark-card border border-border-subtle rounded-xl p-6 space-y-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-semibold text-text-primary">Employee Information</h2>
                <a href="{{ route('dashboard.employee.index') }}" class="text-xs font-medium text-text-secondary hover:text-text-primary">Back to list</a>
            </div>

            @if ($errors->any())
                <div class="rounded-lg border border-danger/30 bg-danger/10 p-4 text-sm text-danger mb-4" role="alert">
                    <p class="font-medium">Please check the following fields:</p>
                    <ul class="mt-1 list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-4">
                <div>
                    <label for="employee_code" class="block text-sm font-medium mb-1.5">Employee ID</label>
                    <input id="employee_code" name="employee_code" type="text" value="{{ old('employee_code', $employee->employee_code) }}" required placeholder="e.g. EMP-00986" class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                    @error('employee_code') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium mb-1.5">Full Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $employee->name) }}" required placeholder="e.g. John Doe" class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                    @error('name') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium mb-1.5">Email Address</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $employee->email) }}" placeholder="john.doe@company.com" class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                    @error('email') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="department" class="block text-sm font-medium mb-1.5">Department</label>
                    <input id="department" name="department" type="text" value="{{ old('department', $employee->department) }}" required placeholder="e.g. Engineering" class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                    @error('department') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="position" class="block text-sm font-medium mb-1.5">Position / Role</label>
                    <input id="position" name="position" type="text" value="{{ old('position', $employee->position) }}" placeholder="e.g. System Architect" class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                    @error('position') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium mb-1.5">Status</label>
                    <select id="status" name="status" required class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                        <option value="active" @selected(old('status', $employee->status) === 'active')>Active</option>
                        <option value="inactive" @selected(old('status', $employee->status) === 'inactive')>Inactive</option>
                    </select>
                    @error('status') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Face Photo Management --}}
        <div class="lg:col-span-5 bg-dark-card border border-border-subtle rounded-xl p-6 space-y-6">
            <div>
                <h2 class="text-base font-semibold text-text-primary mb-4">Face Photo Management</h2>

                {{-- Foto Tersimpan --}}
                @if ($employee->photos->count())
                    <p class="text-xs font-medium text-text-secondary mb-3">Registered Photos ({{ $employee->photos->count() }})</p>
                    <div class="grid grid-cols-3 gap-3 mb-5">
                        @foreach ($employee->photos as $photo)
                            <label class="relative group cursor-pointer">
                                <img src="{{ Storage::url($photo->image_path) }}" alt="Face photo"
                                     class="w-full h-24 object-cover rounded-lg border border-border-subtle">
                                <input type="checkbox" name="delete_photo_ids[]" value="{{ $photo->id }}" class="peer hidden">
                                <span class="absolute inset-0 rounded-lg bg-danger/0 peer-checked:bg-danger/50 peer-checked:ring-2 peer-checked:ring-danger border border-transparent flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-all">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </span>
                                <span class="absolute top-1 right-1 w-5 h-5 rounded bg-dark-bg/80 border border-border-subtle flex items-center justify-center peer-checked:bg-danger">
                                    <svg class="w-3 h-3 text-text-secondary peer-checked:text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </span>
                                @if ($photo->is_primary)
                                    <span class="absolute bottom-1 left-1 px-1.5 py-0.5 rounded bg-accent-blue text-[10px] font-medium text-white">Primary</span>
                                @endif
                            </label>
                        @endforeach
                    </div>
                    <p class="text-[11px] text-text-secondary mb-5">Klik foto untuk menandai penghapusan.</p>
                @else
                    <p class="text-xs text-text-secondary mb-5">Belum ada foto terdaftar.</p>
                @endif

                {{-- Area Upload Foto Baru --}}
                <div class="border-2 border-dashed border-border-subtle rounded-xl p-6 flex flex-col items-center justify-center text-center bg-dark-elevated/50 hover:bg-dark-elevated transition-colors relative cursor-pointer">
                    <input type="file" name="photos[]" id="photos" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImages(event)">

                    <svg class="w-10 h-10 text-accent-blue mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>

                    <p class="text-sm font-medium text-text-primary">Tambah foto baru</p>
                    <p class="text-xs text-text-secondary mt-1 mb-4">Tambahkan 1-3 foto wajah yang jelas</p>

                    <span class="px-3 py-1.5 bg-dark-card border border-border-subtle rounded-lg text-xs font-medium text-text-primary">Choose Files</span>
                </div>

                {{-- Preview Container --}}
                <div class="mt-5">
                    <p class="text-xs font-medium text-text-secondary mb-3">New Photos</p>
                    <div id="image-preview" class="flex gap-3 overflow-x-auto pb-2"></div>
                </div>
            </div>

            {{-- Action & Submit --}}
            <div class="pt-4 border-t border-border-subtle">
                <button type="submit" class="w-full py-3 px-4 bg-accent-blue hover:bg-accent-blue-hover text-white text-sm font-medium rounded-lg transition-colors shadow-lg shadow-accent-blue/20">
                    Update Employee
                </button>
            </div>
        </div>
    </form>

    {{-- Script untuk Preview Foto --}}
    <script>
        function previewImages(event) {
            const container = document.getElementById('image-preview');
            container.innerHTML = '';

            const files = event.target.files;
            if (files) {
                Array.from(files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'w-16 h-20 object-cover rounded-lg border border-border-subtle';
                        container.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }
    </script>
@endsection
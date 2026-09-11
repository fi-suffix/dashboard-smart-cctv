{{-- @extends('layouts.dashboard')

@section('title', 'Add Employee')
@section('page-title', 'Add Employee')
@section('timestamp', 'Oct 24, 2025 — 10:42 AM')

@section('content')
	<div class="max-w-3xl bg-dark-card border border-border-subtle rounded-xl overflow-hidden">
		<div class="p-5 border-b border-border-subtle">
			<h2 class="text-base font-semibold">Employee Information</h2>
			<p class="text-xs text-text-secondary mt-1">Register an employee for face recognition.</p>
		</div>

		@if ($errors->any())
			<div class="mx-5 mt-5 rounded-lg border border-danger/30 bg-danger/10 p-4 text-sm text-danger" role="alert">
				<p class="font-medium">Please check the following fields:</p>
				<ul class="mt-2 list-disc pl-5 space-y-1">
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<form method="POST" action="{{ route('dashboard.employee.store') }}" class="p-5 space-y-5">
			@csrf

			<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
				<div>
					<label for="employee_code" class="block text-sm font-medium mb-2">Employee ID</label>
					<input id="employee_code" name="employee_code" type="text" value="{{ old('employee_code') }}" required maxlength="50" placeholder="EMP-001" class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3 py-2.5 text-sm text-text-primary placeholder:text-text-secondary focus:border-accent-blue focus:outline-none">
					@error('employee_code') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
				</div>

				<div>
					<label for="name" class="block text-sm font-medium mb-2">Full Name</label>
					<input id="name" name="name" type="text" value="{{ old('name') }}" required maxlength="255" placeholder="Full name" class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3 py-2.5 text-sm text-text-primary placeholder:text-text-secondary focus:border-accent-blue focus:outline-none">
					@error('name') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
				</div>

				<div>
					<label for="department" class="block text-sm font-medium mb-2">Department</label>
					<input id="department" name="department" type="text" value="{{ old('department') }}" required maxlength="255" placeholder="Security" class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3 py-2.5 text-sm text-text-primary placeholder:text-text-secondary focus:border-accent-blue focus:outline-none">
					@error('department') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
				</div>

				<div>
					<label for="status" class="block text-sm font-medium mb-2">Status</label>
					<select id="status" name="status" required class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
						<option value="active" @selected(old('status', 'active') === 'active')>Active</option>
						<option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
					</select>
					@error('status') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
				</div>
			</div>

			<div class="flex items-center justify-end gap-3 pt-2">
				<a href="{{ route('dashboard.employee.index') }}" class="px-4 py-2 text-sm font-medium text-text-secondary hover:text-text-primary">Cancel</a>
				<button type="submit" class="px-4 py-2 rounded-lg bg-accent-blue hover:bg-accent-blue-hover text-white text-sm font-medium transition-colors">Save Employee</button>
			</div>
		</form>
	</div>
@endsection --}}

@extends('layouts.dashboard')

@section('title', 'Register New Employee')
@section('page-title', 'Employees / Register New')

@section('content')
    <form method="POST" action="{{ route('dashboard.employee.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        @csrf

        {{-- Kolom Kiri: Employee Information --}}
        <div class="lg:col-span-7 bg-dark-card border border-border-subtle rounded-xl p-6 space-y-5">
            <h2 class="text-base font-semibold text-text-primary mb-4">Employee Information</h2>

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
                    <input id="employee_code" name="employee_code" type="text" value="{{ old('employee_code') }}" required placeholder="e.g. EMP-00986" class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium mb-1.5">Full Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required placeholder="e.g. John Doe" class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium mb-1.5">Email Address</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="john.doe@company.com" class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                </div>

                <div>
                    <label for="department" class="block text-sm font-medium mb-1.5">Department</label>
                    <input id="department" name="department" type="text" value="{{ old('department') }}" required placeholder="e.g. Engineering" class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                </div>

                <div>
                    <label for="position" class="block text-sm font-medium mb-1.5">Position / Role</label>
                    <input id="position" name="position" type="text" value="{{ old('position') }}" placeholder="e.g. System Architect" class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium mb-1.5">Status</label>
                    <select id="status" name="status" required class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                        <option value="active" @selected(old('status', 'active') === 'active')>Active</option>
                        <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                    </select>
                    @error('status') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Face Photo Registration --}}
        <div class="lg:col-span-5 bg-dark-card border border-border-subtle rounded-xl p-6 flex flex-col justify-between space-y-6">
            <div>
                <h2 class="text-base font-semibold text-text-primary mb-4">Face Photo Registration</h2>

                {{-- Area Upload Drag & Drop --}}
                <div class="border-2 border-dashed border-border-subtle rounded-xl p-6 flex flex-col items-center justify-center text-center bg-dark-elevated/50 hover:bg-dark-elevated transition-colors relative cursor-pointer">
                    <input type="file" name="photos[]" id="photos" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImages(event)">
                    
                    <svg class="w-10 h-10 text-accent-blue mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    
                    <p class="text-sm font-medium text-text-primary">Upload 3-5 clear face photos</p>
                    <p class="text-xs text-text-secondary mt-1 mb-4">Drag & drop high-res images here, or use the camera feed</p>

                    <div class="flex gap-2">
                        <span class="px-3 py-1.5 bg-dark-card border border-border-subtle rounded-lg text-xs font-medium text-text-primary hover:bg-border-subtle/20">Choose Files</span>
                        <button type="button" class="px-3 py-1.5 bg-dark-card border border-border-subtle rounded-lg text-xs font-medium text-text-primary hover:bg-border-subtle/20">Webcam Capture</button>
                    </div>
                </div>

                {{-- Preview Container --}}
                <div class="mt-5">
                    <p class="text-xs font-medium text-text-secondary mb-3">Uploaded Photos</p>
                    <div id="image-preview" class="flex gap-3 overflow-x-auto pb-2">
                        {{-- Thumbnail foto yang diupload akan muncul di sini via JS --}}
                    </div>
                </div>
            </div>

            {{-- Action & Submit --}}
            <div class="pt-4 border-t border-border-subtle">
                <div class="flex justify-between items-center mb-4 text-xs">
                    <span class="text-text-secondary">Model Verification</span>
                    <span class="text-emerald-500 font-medium flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Ready for processing
                    </span>
                </div>

                <button type="submit" class="w-full py-3 px-4 bg-accent-blue hover:bg-accent-blue-hover text-white text-sm font-medium rounded-lg transition-colors shadow-lg shadow-accent-blue/20">
                    Save & Process Face Data
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

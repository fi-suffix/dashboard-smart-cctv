@extends('layouts.dashboard')

@section('title', 'Edit Camera')
@section('page-title', 'Cameras / Edit')
@section('timestamp', now()->format('M d, Y — h:i A'))

@section('content')
<div class="max-w-3xl bg-dark-card border border-border-subtle rounded-xl overflow-hidden">
    <div class="p-5 border-b border-border-subtle">
        <h2 class="text-base font-semibold">Edit Camera: {{ $camera->name }}</h2>
        <p class="text-xs text-text-secondary mt-1">Update camera configuration settings.</p>
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

    <form method="POST" action="{{ route('dashboard.camera.update', $camera) }}" class="p-5 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block text-sm font-medium mb-1.5">Camera Name</label>
            <input id="name" name="name" type="text" value="{{ old('name', $camera->name) }}" required placeholder="e.g. Main Entrance Cam" class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
            @error('name') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="rtsp_url" class="block text-sm font-medium mb-1.5">RTSP URL</label>
            <input id="rtsp_url" name="rtsp_url" type="text" value="{{ old('rtsp_url', $camera->rtsp_url) }}" required placeholder="rtsp://username:password@192.168.1.100:554/stream" class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
            <p class="mt-1 text-xs text-text-secondary">RTSP stream URL (e.g., rtsp://user:pass@ip:port/stream)</p>
            @error('rtsp_url') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="location" class="block text-sm font-medium mb-1.5">Location</label>
            <input id="location" name="location" type="text" value="{{ old('location', $camera->location) }}" required placeholder="e.g. Main Entrance, Parking Lot A" class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
            @error('location') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="status" class="block text-sm font-medium mb-1.5">Status</label>
                <select id="status" name="status" required class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                    <option value="active" @selected(old('status', $camera->status) === 'active')>Active</option>
                    <option value="inactive" @selected(old('status', $camera->status) === 'inactive')>Inactive</option>
                    <option value="maintenance" @selected(old('status', $camera->status) === 'maintenance')>Maintenance</option>
                </select>
                @error('status') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="reconnect_interval" class="block text-sm font-medium mb-1.5">Reconnect Interval (seconds)</label>
                <input id="reconnect_interval" name="reconnect_interval" type="number" value="{{ old('reconnect_interval', $camera->reconnect_interval) }}" min="1" max="300" class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                @error('reconnect_interval') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="username" class="block text-sm font-medium mb-1.5">Username (Optional)</label>
                <input id="username" name="username" type="text" value="{{ old('username', $camera->username) }}" placeholder="admin" class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium mb-1.5">Password (Optional)</label>
                <input id="password" name="password" type="password" placeholder="••••••••" class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                <p class="mt-1 text-xs text-text-secondary">Leave blank to keep current password</p>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('dashboard.camera.index') }}" class="px-4 py-2 text-sm font-medium text-text-secondary hover:text-text-primary">Cancel</a>
            <button type="submit" class="px-4 py-2 rounded-lg bg-accent-blue hover:bg-accent-blue-hover text-white text-sm font-medium transition-colors">Update Camera</button>
        </div>
    </form>
</div>
@endsection
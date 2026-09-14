@extends('layouts.dashboard')

@section('title', 'Cameras')
@section('page-title', 'Cameras')
@section('timestamp', now()->format('M d, Y — h:i A'))

@section('content')
<div class="bg-dark-card border border-border-subtle rounded-xl overflow-hidden">
    <div class="flex items-center justify-between p-4 border-b border-border-subtle">
        <div>
            <h2 class="text-base font-semibold">Camera Management</h2>
            <p class="text-xs text-text-secondary">Monitor and configure CCTV cameras</p>
        </div>
        <a href="{{ route('dashboard.camera.create') }}" class="px-4 py-2 bg-accent-blue hover:bg-accent-blue-hover text-white text-sm font-medium rounded-lg transition-colors">
            Add Camera
        </a>
    </div>

    @if (session('success'))
        <div class="m-4 rounded-lg border border-success/30 bg-success/10 px-4 py-3 text-sm text-success" role="status">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
        @forelse ($cameras as $camera)
            <div class="bg-dark-elevated/40 border border-border-subtle rounded-xl p-4 hover:border-accent-blue/40 transition-colors">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-dark-card flex items-center justify-center text-text-secondary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.833-.744 1.666-1.088 2.5M15 13.5a2.25 2.25 0 01-2.25 2.25H6.75m13.5 0a2.25 2.25 0 01-2.25-2.25M6.75 15a2.25 2.25 0 002.25 2.25h.008v.008h-.008a2.25 2.25 0 01-2.25-2.25M6.75 15V6.75m9 8.25V6.75m0 0a2.25 2.25 0 012.25-2.25h.008v-.008h-.008a2.25 2.25 0 01-2.25 2.25v.008h.008a2.25 2.25 0 002.25-2.25v-.008h-.008a2.25 2.25 0 00-2.25 2.25v.008h.008a2.25 2.25 0 002.25-2.25v-.008h-.008a2.25 2.25 0 01-2.25-2.25v-.008h-.008z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium">{{ $camera->name }}</p>
                            <p class="text-[11px] text-text-secondary">{{ $camera->location }}</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full {{ $camera->status_badge_class }} text-[11px] font-medium">
                        <span class="w-1.5 h-1.5 rounded-full {{ $camera->status === 'active' ? 'bg-success' : ($camera->status === 'maintenance' ? 'bg-warning' : 'bg-text-secondary') }}"></span>
                        {{ $camera->status_label }}
                    </span>
                </div>
                <div class="aspect-video bg-dark-bg rounded-lg mb-3 flex items-center justify-center relative overflow-hidden">
                    @if ($camera->status === 'active')
                        <img src="{{ $pythonServiceUrl }}/cameras/{{ $camera->id }}/stream" 
                             alt="{{ $camera->name }}" 
                             class="w-full h-full object-cover"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="absolute inset-0 flex items-center justify-center" style="display: none;">
                            <svg class="w-8 h-8 text-text-secondary opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/>
                            </svg>
                        </div>
                    @else
                        <svg class="w-8 h-8 text-text-secondary opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/>
                        </svg>
                    @endif
                </div>
                <div class="flex items-center justify-between text-xs text-text-secondary mb-3">
                    <span>{{ $camera->rtsp_url }}</span>
                    <span>RTSP</span>
                </div>
                <div class="flex items-center justify-end gap-2">
                    <form action="{{ route('dashboard.camera.toggle-status', $camera) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors
                            {{ $camera->status === 'active' 
                                ? 'bg-warning/10 text-warning hover:bg-warning/20' 
                                : 'bg-success/10 text-success hover:bg-success/20' }}">
                            {{ $camera->status === 'active' ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                    <a href="{{ route('dashboard.camera.edit', $camera) }}" class="px-3 py-1.5 text-xs font-medium text-text-secondary hover:text-text-primary bg-dark-card border border-border-subtle rounded-lg transition-colors">
                        Edit
                    </a>
                    <form action="{{ route('dashboard.camera.destroy', $camera) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this camera?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1.5 text-xs font-medium text-danger hover:bg-danger/10 bg-dark-card border border-border-subtle rounded-lg transition-colors">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-dark-elevated/40 border border-border-subtle rounded-xl p-12 text-center">
                <svg class="w-12 h-12 mx-auto text-text-secondary opacity-40 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.833-.744 1.666-1.088 2.5M15 13.5a2.25 2.25 0 01-2.25 2.25H6.75m13.5 0a2.25 2.25 0 01-2.25-2.25M6.75 15a2.25 2.25 0 002.25 2.25h.008v.008h-.008a2.25 2.25 0 01-2.25-2.25M6.75 15V6.75m9 8.25V6.75m0 0a2.25 2.25 0 012.25-2.25h.008v-.008h-.008a2.25 2.25 0 01-2.25 2.25v.008h.008a2.25 2.25 0 002.25-2.25v-.008h-.008a2.25 2.25 0 00-2.25 2.25v.008h.008a2.25 2.25 0 002.25-2.25v-.008h-.008a2.25 2.25 0 01-2.25-2.25v-.008h-.008z"/>
                </svg>
                <p class="text-sm text-text-secondary">No cameras configured yet.</p>
                <a href="{{ route('dashboard.camera.create') }}" class="mt-4 inline-block px-4 py-2 bg-accent-blue hover:bg-accent-blue-hover text-white text-sm font-medium rounded-lg transition-colors">
                    Add Your First Camera
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
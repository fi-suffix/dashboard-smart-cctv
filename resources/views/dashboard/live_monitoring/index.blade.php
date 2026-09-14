@extends('layouts.dashboard')

@section('title', 'Live Monitoring')
@section('page-title', 'Live Monitoring')
@section('timestamp', now()->format('M d, Y — h:i A'))

@section('content')
<div class="space-y-6">
    {{-- Stats Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-dark-card border border-border-subtle rounded-xl p-5 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-sm text-text-secondary mb-1">Total Cameras</p>
                <p class="text-2xl font-semibold">{{ $stats['total_cameras'] }}</p>
            </div>
            <div class="absolute -right-3 -top-3 w-20 h-20 text-accent-blue opacity-20">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.833-.744 1.666-1.088 2.5M15 13.5a2.25 2.25 0 01-2.25 2.25H6.75m13.5 0a2.25 2.25 0 01-2.25-2.25M6.75 15a2.25 2.25 0 002.25 2.25h.008v.008h-.008a2.25 2.25 0 01-2.25-2.25M6.75 15V6.75m9 8.25V6.75m0 0a2.25 2.25 0 012.25-2.25h.008v-.008h-.008a2.25 2.25 0 01-2.25 2.25v.008h.008a2.25 2.25 0 002.25-2.25v-.008h-.008a2.25 2.25 0 00-2.25 2.25v.008h.008a2.25 2.25 0 01-2.25-2.25v-.008h-.008z"/></svg>
            </div>
        </div>

        <div class="bg-dark-card border border-border-subtle rounded-xl p-5 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-sm text-text-secondary mb-1">Active Cameras</p>
                <p class="text-2xl font-semibold text-success">{{ $stats['active_cameras'] }}</p>
            </div>
            <div class="absolute -right-3 -top-3 w-20 h-20 text-success opacity-20">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
            </div>
        </div>

        <div class="bg-dark-card border border-border-subtle rounded-xl p-5 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-sm text-text-secondary mb-1">Registered Employees</p>
                <p class="text-2xl font-semibold">{{ $stats['total_employees'] }}</p>
            </div>
            <div class="absolute -right-3 -top-3 w-20 h-20 text-accent-blue opacity-20">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.25a8.25 8.25 0 0114.997 0"/></svg>
            </div>
        </div>

        <div class="bg-dark-card border border-border-subtle rounded-xl p-5 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-sm text-text-secondary mb-1">Detections Today</p>
                <p class="text-2xl font-semibold">{{ $stats['detections_today'] }}</p>
                <div class="mt-2 flex items-center gap-2 text-xs">
                    <span class="text-success">{{ $stats['recognized_today'] }} recognized</span>
                    <span class="text-text-secondary">·</span>
                    <span class="text-danger">{{ $stats['unknown_today'] }} unknown</span>
                </div>
            </div>
            <div class="absolute -right-3 -top-3 w-20 h-20 text-success opacity-20">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M4.5 19.5a2.25 2.25 0 01-2.25-2.25V6A2.25 2.25 0 014.5 3.75h4.5a2.25 2.25 0 012.25 2.25v2.25a2.25 2.25 0 01-2.25 2.25h-.75v4.5h.75a2.25 2.25 0 012.25 2.25v2.25a2.25 2.25 0 01-2.25 2.25H4.5z"/></svg>
            </div>
        </div>
    </div>

    {{-- Camera Grid --}}
    <div class="bg-dark-card border border-border-subtle rounded-xl overflow-hidden">
        <div class="flex items-center justify-between p-4 border-b border-border-subtle">
            <div>
                <h2 class="text-base font-semibold">Camera Feeds</h2>
                <p class="text-xs text-text-secondary">Click on a camera to view full-screen live feed</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
            @forelse ($cameras as $camera)
                <a href="{{ route('dashboard.live_monitoring.show', $camera) }}" class="bg-dark-elevated/40 border border-border-subtle rounded-xl p-4 hover:border-accent-blue/40 transition-colors group">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-dark-card flex items-center justify-center text-text-secondary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.833-.744 1.666-1.088 2.5M15 13.5a2.25 2.25 0 01-2.25 2.25H6.75m13.5 0a2.25 2.25 0 01-2.25-2.25M6.75 15a2.25 2.25 0 002.25 2.25h.008v.008h-.008a2.25 2.25 0 01-2.25-2.25M6.75 15V6.75m9 8.25V6.75m0 0a2.25 2.25 0 012.25-2.25h.008v-.008h-.008a2.25 2.25 0 01-2.25 2.25v.008h.008a2.25 2.25 0 002.25-2.25v-.008h-.008a2.25 2.25 0 00-2.25 2.25v.008h.008a2.25 2.25 0 01-2.25-2.25v-.008h-.008z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium group-hover:text-accent-blue transition-colors">{{ $camera->name }}</p>
                                <p class="text-[11px] text-text-secondary">{{ $camera->location }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full {{ $camera->status_badge_class }} text-[11px] font-medium">
                            <span class="w-1.5 h-1.5 rounded-full {{ $camera->status === 'active' ? 'bg-success animate-pulse' : ($camera->status === 'maintenance' ? 'bg-warning' : 'bg-text-secondary') }}"></span>
                            {{ $camera->status_label }}
                        </span>
                    </div>
                    <div class="aspect-video bg-dark-bg rounded-lg mb-3 relative overflow-hidden group-hover:shadow-lg transition-shadow">
                        @if ($camera->status === 'active')
                            <img src="{{ $pythonServiceUrl }}/cameras/{{ $camera->id }}/stream" 
                                 alt="{{ $camera->name }}" 
                                 class="w-full h-full object-cover"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="absolute inset-0 flex items-center justify-center" style="display: none;">
                                <svg class="w-8 h-8 text-text-secondary opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/></svg>
                            </div>
                        @else
                            <svg class="w-8 h-8 text-text-secondary opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/></svg>
                        @endif
                    </div>
                    <div class="flex items-center justify-between text-xs text-text-secondary">
                        <span>RTSP Stream</span>
                        <span class="flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-accent-blue"></span>
                            Live
                        </span>
                    </div>
                </a>
            @empty
                <div class="col-span-full bg-dark-elevated/40 border border-border-subtle rounded-xl p-12 text-center">
                    <svg class="w-12 h-12 mx-auto text-text-secondary opacity-40 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/></svg>
                    <p class="text-sm text-text-secondary">No active cameras configured.</p>
                    <a href="{{ route('dashboard.camera.create') }}" class="mt-4 inline-block px-4 py-2 bg-accent-blue hover:bg-accent-blue-hover text-white text-sm font-medium rounded-lg transition-colors">
                        Add Camera
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Recent Detections --}}
    <div class="bg-dark-card border border-border-subtle rounded-xl overflow-hidden">
        <div class="p-4 border-b border-border-subtle">
            <h2 class="text-base font-semibold">Recent Detections</h2>
        </div>
        <div class="divide-y divide-border-subtle">
            @forelse ($recentDetections as $detection)
                <div class="p-4 flex items-center gap-4 hover:bg-dark-elevated/50 transition-colors">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium">{{ $detection->camera->name }}</p>
                        <p class="text-xs text-text-secondary">{{ $detection->camera->location }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        @if ($detection->employee_id)
                            <div class="w-8 h-8 rounded-full bg-accent-blue/20 flex items-center justify-center text-accent-blue text-xs font-semibold">{{ collect(explode(' ', $detection->employee_name))->map(fn ($part) => substr($part, 0, 1))->take(2)->join('') }}</div>
                            <div>
                                <p class="text-sm font-medium">{{ $detection->employee_name }}</p>
                                <p class="text-xs text-text-secondary">{{ $detection->employee->department }}</p>
                            </div>
                        @else
                            <div class="w-8 h-8 rounded-full bg-danger/20 flex items-center justify-center text-danger text-xs font-semibold">?</div>
                            <div>
                                <p class="text-sm font-medium text-danger">Unknown Person</p>
                                <p class="text-xs text-text-secondary">Not registered</p>
                            </div>
                        @endif
                        <div class="text-right ml-4">
                            <p class="text-sm font-semibold {{ $detection->status === 'recognized' ? 'text-success' : 'text-danger' }}">{{ $detection->confidence_percentage }}</p>
                            <p class="text-[11px] text-text-secondary">{{ $detection->detected_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-text-secondary">No recent detections</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('timestamp', now()->format('M d, Y — h:i A'))

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-dark-card border border-border-subtle rounded-xl p-5 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-sm text-text-secondary mb-1">Total Registered Employees</p>
                <p class="text-2xl font-semibold">{{ $totalEmployees }}</p>
                <div class="mt-3 flex items-center gap-1 text-xs font-medium text-success">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"/></svg>
                    <span>{{ $activeEmployees }} active</span>
                </div>
            </div>
            <div class="absolute -right-3 -top-3 w-20 h-20 text-accent-blue opacity-20">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M15 19.5a3 3 0 00-6 0m9-9a3 3 0 11-6 0 3 3 0 016 0zm-8.25 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zM3 19.5a3 3 0 015.25-1.98"/></svg>
            </div>
        </div>

        <div class="bg-dark-card border border-border-subtle rounded-xl p-5 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-sm text-text-secondary mb-1">Recognized Today</p>
                <p class="text-2xl font-semibold">{{ $recognizedToday }}</p>
                <div class="mt-3 flex items-center gap-1 text-xs font-medium text-success">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"/></svg>
                    <span>{{ $detectionsToday }} total detections</span>
                </div>
            </div>
            <div class="absolute -right-3 -top-3 w-20 h-20 text-success opacity-20">
                <svg viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-2.19a.75.75 0 10-1.22-.88l-3.64 5.04-1.9-1.9a.75.75 0 00-1.06 1.06l2.5 2.5a.75.75 0 001.14-.09l4.18-5.73z" clip-rule="evenodd"/></svg>
            </div>
        </div>

        <div class="bg-dark-card border border-border-subtle rounded-xl p-5 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-sm text-text-secondary mb-1">Unknown Detections</p>
                <p class="text-2xl font-semibold">{{ $unknownToday }}</p>
                <div class="mt-3 flex items-center gap-1 text-xs font-medium text-danger">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    <span>Check alerts</span>
                </div>
            </div>
            <div class="absolute -right-3 -top-3 w-20 h-20 text-danger opacity-20">
                <svg viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5zm-2.25 6a2.25 2.25 0 114.5 0 2.25 2.25 0 01-4.5 0zM7.5 17.25a4.5 4.5 0 019 0H7.5z" clip-rule="evenodd"/><path d="M18.75 3.75a.75.75 0 011.5 0v3a.75.75 0 01-1.5 0v-3zm0 4.5a.75.75 0 011.5 0v.008a.75.75 0 01-1.5 0V8.25z"/></svg>
            </div>
        </div>

        <div class="bg-dark-card border border-border-subtle rounded-xl p-5 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-sm text-text-secondary mb-1">Active Cameras</p>
                <p class="text-2xl font-semibold">{{ $activeCameras->count() }}/{{ $totalCameras }}</p>
                <div class="mt-3 flex items-center gap-1 text-xs font-medium text-success">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"/></svg>
                    <span>Avg confidence: {{ number_format($avgConfidenceToday * 100, 1) }}%</span>
                </div>
            </div>
            <div class="absolute -right-3 -top-3 w-20 h-20 text-accent-blue opacity-20">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.833-.744 1.666-1.088 2.5M15 13.5a2.25 2.25 0 01-2.25 2.25H6.75m13.5 0a2.25 2.25 0 01-2.25-2.25M6.75 15a2.25 2.25 0 002.25 2.25h.008v.008h-.008a2.25 2.25 0 01-2.25-2.25M6.75 15V6.75m9 8.25V6.75m0 0a2.25 2.25 0 012.25-2.25h.008v-.008h-.008a2.25 2.25 0 01-2.25 2.25v.008h.008a2.25 2.25 0 002.25-2.25v-.008h-.008a2.25 2.25 0 00-2.25 2.25v.008h.008a2.25 2.25 0 01-2.25-2.25v-.008h-.008z"/></svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-dark-card border border-border-subtle rounded-xl overflow-hidden">
            <div class="flex items-center justify-between p-4 border-b border-border-subtle">
                <div>
                    <h2 class="text-base font-semibold">Live Video Feed</h2>
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M4.5 6.75A2.25 2.25 0 016.75 4.5h10.5a2.25 2.25 0 012.25 2.25v7.5a2.25 2.25 0 01-2.25 2.25h-4.5l-3.75 3v-3H6.75A2.25 2.25 0 014.5 14.25v-7.5z"/><path d="M8.25 8.25h7.5v1.5h-7.5v-1.5zm0 3h5.25v1.5H8.25v-1.5z"/></svg>
                </div>
                <a href="{{ route('dashboard.live_monitoring.index') }}" class="px-3 py-1.5 text-xs font-medium text-accent-blue hover:text-accent-blue-hover">View All Cameras</a>
            </div>
            @if ($activeCameras->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4">
                    @foreach ($activeCameras->take(6) as $camera)
                        <a href="{{ route('dashboard.live_monitoring.show', $camera) }}" class="group">
                            <div class="relative aspect-video bg-dark-bg rounded-lg overflow-hidden border border-border-subtle group-hover:border-accent-blue/40 transition-colors">
                                <img src="{{ $pythonServiceUrl }}/cameras/{{ $camera->id }}/snapshot?t={{ time() }}"
                                     alt="{{ $camera->name }}"
                                     class="w-full h-full object-cover"
                                     loading="lazy"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="absolute inset-0 items-center justify-center" style="display: none;">
                                    <div class="text-center space-y-2">
                                        <svg class="w-8 h-8 mx-auto text-text-secondary opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/></svg>
                                        <p class="text-xs text-text-secondary">Stream offline</p>
                                    </div>
                                </div>
                                <div class="absolute bottom-1.5 left-1.5 flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-dark-bg/80 backdrop-blur-sm border border-border-subtle">
                                    <span class="w-1.5 h-1.5 rounded-full bg-success animate-pulse"></span>
                                    <span class="text-[10px] font-medium text-text-primary">{{ $camera->name }}</span>
                                </div>
                                <div class="absolute top-1.5 right-1.5 px-1.5 py-0.5 rounded bg-dark-bg/80 backdrop-blur-sm text-[10px] font-mono text-text-secondary" data-stream-clock></div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="relative aspect-video bg-dark-bg">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center space-y-3">
                            <svg class="w-12 h-12 mx-auto text-text-secondary opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/></svg>
                            <p class="text-sm text-text-secondary">No active cameras</p>
                            <a href="{{ route('dashboard.camera.create') }}" class="inline-block px-3 py-1.5 bg-accent-blue hover:bg-accent-blue-hover text-white text-xs font-medium rounded-lg transition-colors">Add Camera</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="bg-dark-card border border-border-subtle rounded-xl flex flex-col">
            <div class="p-4 border-b border-border-subtle">
                <h2 class="text-base font-semibold">Recent Detections</h2>
            </div>
            <div class="flex-1 overflow-y-auto divide-y divide-border-subtle">
                @forelse ($recentDetections as $detection)
                    <div class="p-3 flex items-center gap-3 hover:bg-dark-elevated/50 transition-colors">
                        <div class="w-9 h-9 rounded-full {{ $detection->status === 'recognized' ? 'bg-accent-blue/20 text-accent-blue' : 'bg-danger/20 text-danger' }} flex items-center justify-center text-xs font-semibold">
                            @if ($detection->employee_id)
                                {{ collect(explode(' ', $detection->employee_name))->map(fn ($part) => substr($part, 0, 1))->take(2)->join('') }}
                            @else
                                ?
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate">{{ $detection->employee_name ?? 'Unknown Person' }}</p>
                            <p class="text-xs text-text-secondary">{{ $detection->camera->name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold {{ $detection->status === 'recognized' ? 'text-success' : 'text-danger' }}">{{ $detection->confidence_percentage }}</p>
                            <p class="text-[11px] text-text-secondary">{{ $detection->detected_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="p-3 text-center text-sm text-text-secondary">No recent detections</div>
                @endforelse
            </div>
        </div>
    </div>

    @include('dashboard.partials.detection-chart', ['stats' => $filledStats, 'title' => '7-Day Recognition Activity'])
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Stream clock overlay
    function updateStreamClocks() {
        const now = new Date();
        const time = now.toLocaleTimeString();
        document.querySelectorAll('[data-stream-clock]').forEach(el => el.textContent = time);
    }
    setInterval(updateStreamClocks, 1000);
    updateStreamClocks();
    
</script>
@endpush
@endsection
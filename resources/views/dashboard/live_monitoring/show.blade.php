@extends('layouts.dashboard')

@section('title', 'Live Monitoring - {{ $camera->name }}')
@section('page-title', 'Live Monitoring / {{ $camera->name }}')
@section('timestamp', now()->format('M d, Y — h:i A'))

@section('content')
<div class="space-y-6">
    {{-- Header with back button --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('dashboard.live_monitoring.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-text-secondary hover:text-text-primary mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back to All Cameras
            </a>
            <h2 class="text-base font-semibold">{{ $camera->name }}</h2>
            <p class="text-xs text-text-secondary">{{ $camera->location }} · {{ $camera->rtsp_url }}</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full {{ $camera->status === 'active' ? 'bg-danger/10 border border-danger/20 text-danger' : 'bg-text-secondary/10 border border-text-secondary/20 text-text-secondary' }} text-xs font-medium">
                <span class="w-1.5 h-1.5 rounded-full {{ $camera->status === 'active' ? 'bg-danger animate-pulse' : 'bg-text-secondary' }}"></span>
                {{ $camera->status === 'active' ? 'LIVE' : 'OFFLINE' }}
            </span>
        </div>
    </div>

    {{-- Main Video Feed --}}
    <div class="bg-dark-card border border-border-subtle rounded-xl overflow-hidden">
        <div class="relative aspect-video bg-dark-bg">
            @if ($camera->status === 'active')
                <img id="camera-stream" 
                     src="http://localhost:8001/cameras/{{ $camera->id }}/stream" 
                     alt="{{ $camera->name }}" 
                     class="w-full h-full object-cover"
                     onerror="handleStreamError()">
                <div id="stream-error" class="absolute inset-0 flex items-center justify-center hidden">
                    <div class="text-center space-y-3">
                        <svg class="w-12 h-12 mx-auto text-text-secondary opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/></svg>
                        <p class="text-sm text-text-secondary">Unable to load stream</p>
                        <button onclick="reloadStream()" class="px-3 py-1.5 bg-accent-blue hover:bg-accent-blue-hover text-white text-xs font-medium rounded-lg transition-colors">Retry</button>
                    </div>
                </div>
                
                {{-- Detection Overlay --}}
                <div id="detection-overlay" class="absolute inset-0 pointer-events-none"></div>
            @else
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center space-y-3">
                        <svg class="w-12 h-12 mx-auto text-text-secondary opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/></svg>
                        <p class="text-sm text-text-secondary">Camera is offline</p>
                    </div>
                </div>
            @endif

            {{-- Timestamp --}}
            <div class="absolute bottom-4 left-4 flex items-center gap-2">
                <div class="bg-dark-bg/80 backdrop-blur-sm text-[11px] text-text-secondary px-2 py-1 rounded border border-border-subtle font-mono" id="stream-time">--:--:--</div>
            </div>
        </div>
    </div>

    {{-- Camera Info & Recent Detections --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-dark-card border border-border-subtle rounded-xl p-5">
            <h3 class="text-sm font-semibold mb-4">Recent Detections on This Camera</h3>
            <div class="divide-y divide-border-subtle" id="camera-detections">
                @forelse ($recentDetections as $detection)
                    <div class="py-3 flex items-center gap-3 hover:bg-dark-elevated/50 transition-colors">
                        @if ($detection->employee_id)
                            <div class="w-8 h-8 rounded-full bg-accent-blue/20 flex items-center justify-center text-accent-blue text-xs font-semibold">{{ collect(explode(' ', $detection->employee_name))->map(fn ($part) => substr($part, 0, 1))->take(2)->join('') }}</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium">{{ $detection->employee_name }}</p>
                                <p class="text-xs text-text-secondary">{{ $detection->employee->department }}</p>
                            </div>
                        @else
                            <div class="w-8 h-8 rounded-full bg-danger/20 flex items-center justify-center text-danger text-xs font-semibold">?</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-danger">Unknown Person</p>
                                <p class="text-xs text-text-secondary">Not registered in system</p>
                            </div>
                        @endif
                        <div class="text-right">
                            <p class="text-sm font-semibold {{ $detection->status === 'recognized' ? 'text-success' : 'text-danger' }}">{{ $detection->confidence_percentage }}</p>
                            <p class="text-[11px] text-text-secondary">{{ $detection->detected_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-text-secondary">No recent detections on this camera</div>
                @endforelse
            </div>
        </div>

        <div class="bg-dark-card border border-border-subtle rounded-xl p-5">
            <h3 class="text-sm font-semibold mb-4">Camera Details</h3>
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="text-text-secondary">Name</dt>
                    <dd class="font-medium">{{ $camera->name }}</dd>
                </div>
                <div>
                    <dt class="text-text-secondary">Location</dt>
                    <dd class="font-medium">{{ $camera->location }}</dd>
                </div>
                <div>
                    <dt class="text-text-secondary">RTSP URL</dt>
                    <dd class="font-medium text-xs truncate">{{ $camera->rtsp_url }}</dd>
                </div>
                <div>
                    <dt class="text-text-secondary">Status</dt>
                    <dd>
                        <span class="inline-flex px-2 py-0.5 rounded-full {{ $camera->status_badge_class }} text-xs font-medium">{{ $camera->status_label }}</span>
                    </dd>
                </div>
                <div>
                    <dt class="text-text-secondary">Reconnect Interval</dt>
                    <dd class="font-medium">{{ $camera->reconnect_interval }} seconds</dd>
                </div>
                <div>
                    <dt class="text-text-secondary">Last Connected</dt>
                    <dd class="font-medium">{{ $camera->last_connected_at ? $camera->last_connected_at->diffForHumans() : 'Never' }}</dd>
                </div>
            </dl>
            
            <div class="mt-4 pt-4 border-t border-border-subtle">
                <a href="{{ route('dashboard.camera.edit', $camera) }}" class="w-full px-4 py-2 text-center text-sm font-medium text-text-secondary hover:text-text-primary bg-dark-elevated/50 border border-border-subtle rounded-lg transition-colors block">
                    Edit Camera Settings
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function handleStreamError() {
        document.getElementById('camera-stream').style.display = 'none';
        document.getElementById('stream-error').style.display = 'flex';
    }

    function reloadStream() {
        const img = document.getElementById('camera-stream');
        img.src = img.src.split('?')[0] + '?t=' + Date.now();
        img.style.display = 'block';
        document.getElementById('stream-error').style.display = 'none';
    }

    // Update timestamp
    function updateTime() {
        const now = new Date();
        document.getElementById('stream-time').textContent = now.toLocaleTimeString();
    }
    setInterval(updateTime, 1000);
    updateTime();

    // Auto-reload stream every 30 seconds if error
    setInterval(() => {
        const img = document.getElementById('camera-stream');
        if (img.style.display === 'none') {
            reloadStream();
        }
    }, 30000);
</script>
@endpush
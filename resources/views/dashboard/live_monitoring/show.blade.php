@extends('layouts.dashboard')

@section('title')
    Live Monitoring - {{ $camera->name }}
@endsection

@section('page-title')
    Live Monitoring / {{ $camera->name }}
@endsection
@section('timestamp', now()->format('M d, Y — h:i A'))

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard.live_monitoring.index') }}" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-dark-card border border-border-subtle text-text-secondary hover:text-text-primary hover:border-accent-blue/40 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            </a>
            <div>
                <h2 class="text-base font-semibold">{{ $camera->name }}</h2>
                <p class="text-xs text-text-secondary">{{ $camera->location }}</p>
            </div>
        </div>
        <span id="stream-status-badge" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-text-secondary/10 border border-border-subtle text-text-secondary text-xs font-medium w-fit">
            <span id="stream-status-dot" class="w-1.5 h-1.5 rounded-full bg-text-secondary"></span>
            <span id="stream-status-text">Connecting...</span>
        </span>
    </div>

    {{-- Main Video Feed --}}
    <div class="bg-dark-card border border-border-subtle rounded-xl overflow-hidden">
        <div class="relative bg-dark-bg" style="aspect-ratio: 16/9;">
            <img id="camera-stream"
                 class="w-full h-full object-contain"
                 alt="{{ $camera->name }}"
                 onerror="handleStreamError()">

            <div id="stream-error" class="absolute inset-0 flex items-center justify-center hidden">
                <div class="text-center space-y-3">
                    <svg class="w-12 h-12 mx-auto text-text-secondary opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/></svg>
                    <p class="text-sm text-text-secondary">Unable to load stream</p>
                    <button onclick="reloadStream()" class="px-3 py-1.5 bg-accent-blue hover:bg-accent-blue-hover text-white text-xs font-medium rounded-lg transition-colors">Retry</button>
                </div>
            </div>

            <div class="absolute top-3 right-3 inline-flex items-center gap-1.5 px-2 py-1 rounded-md bg-dark-bg/70 backdrop-blur-sm text-[11px] font-medium text-danger border border-border-subtle">
                <span class="w-1.5 h-1.5 rounded-full bg-danger animate-pulse"></span>
                REC
            </div>
            <div class="absolute bottom-3 left-3 inline-flex items-center gap-2">
                <div class="bg-dark-bg/70 backdrop-blur-sm text-[11px] text-text-secondary px-2 py-1 rounded border border-border-subtle font-mono" id="stream-time">--:--:--</div>
            </div>
        </div>
    </div>

    {{-- Details + Recent Detections --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-dark-card border border-border-subtle rounded-xl overflow-hidden">
            <div class="flex items-center justify-between p-4 border-b border-border-subtle">
                <div>
                    <h3 class="text-sm font-semibold">Recent Detections</h3>
                    <p class="text-xs text-text-secondary">Latest activity on this camera</p>
                </div>
                <a href="{{ route('dashboard.detection_history.index', ['camera_id' => $camera->id]) }}" class="text-xs font-medium text-accent-blue hover:text-accent-blue-hover hover:underline">
                    View all
                </a>
            </div>
            <div class="divide-y divide-border-subtle" id="camera-detections">
                @forelse ($recentDetections as $detection)
                    <a href="{{ route('dashboard.detection_history.show', $detection) }}" class="flex items-center gap-4 p-4 hover:bg-dark-elevated/50 transition-colors">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-semibold shrink-0 {{ $detection->employee_id ? 'bg-accent-blue/20 text-accent-blue' : 'bg-danger/20 text-danger' }}">
                            @if ($detection->employee_id)
                                {{ collect(explode(' ', $detection->employee_name))->map(fn ($part) => substr($part, 0, 1))->take(2)->join('') }}
                            @else
                                ?
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate">
                                @if ($detection->employee_id)
                                    {{ $detection->employee_name }}
                                @else
                                    <span class="text-danger">Unknown Person</span>
                                @endif
                            </p>
                            <p class="text-xs text-text-secondary">{{ $detection->detected_at->diffForHumans() }}</p>
                        </div>
                        <span class="inline-flex px-2 py-0.5 rounded-full {{ $detection->status_badge_class }} text-xs font-medium shrink-0">{{ $detection->status_label }}</span>
                        <span class="text-sm font-semibold {{ $detection->status === 'recognized' ? 'text-success' : 'text-text-secondary' }} shrink-0">{{ $detection->confidence_percentage }}</span>
                    </a>
                @empty
                    <div class="py-12 text-center text-text-secondary text-sm">No detections recorded on this camera yet.</div>
                @endforelse
            </div>
        </div>

        <div class="bg-dark-card border border-border-subtle rounded-xl p-5 h-fit">
            <h3 class="text-sm font-semibold mb-4">Camera Details</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex items-start justify-between gap-3">
                    <dt class="text-text-secondary shrink-0">Location</dt>
                    <dd class="font-medium text-right">{{ $camera->location ?: '—' }}</dd>
                </div>
                <div class="flex items-start justify-between gap-3">
                    <dt class="text-text-secondary shrink-0">Status</dt>
                    <dd><span class="inline-flex px-2 py-0.5 rounded-full {{ $camera->status_badge_class }} text-xs font-medium">{{ $camera->status_label }}</span></dd>
                </div>
                <div class="flex items-start justify-between gap-3">
                    <dt class="text-text-secondary shrink-0">RTSP URL</dt>
                    <dd class="font-mono text-xs text-text-secondary text-right break-all">{{ $camera->rtsp_url }}</dd>
                </div>
                <div class="flex items-start justify-between gap-3">
                    <dt class="text-text-secondary shrink-0">Reconnect</dt>
                    <dd class="font-medium text-right">{{ $camera->reconnect_interval }} seconds</dd>
                </div>
                <div class="flex items-start justify-between gap-3">
                    <dt class="text-text-secondary shrink-0">Last Connected</dt>
                    <dd class="font-medium text-right">{{ $camera->last_connected_at ? $camera->last_connected_at->diffForHumans() : 'Never' }}</dd>
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
    const streamUrl = "{{ $pythonServiceUrl }}/cameras/{{ $camera->id }}/snapshot";

    function loadStream() {
        const img = document.getElementById('camera-stream');
        img.src = streamUrl + '?t=' + Date.now();
    }

    function handleStreamError() {
        document.getElementById('camera-stream').style.display = 'none';
        document.getElementById('stream-error').style.display = 'flex';
        setStatus('offline');
    }

    function onStreamLoad() {
        document.getElementById('camera-stream').style.display = 'block';
        document.getElementById('stream-error').style.display = 'none';
        setStatus('online');
    }

    function reloadStream() {
        const img = document.getElementById('camera-stream');
        loadStream();
        img.style.display = 'block';
        document.getElementById('stream-error').style.display = 'none';
    }

    function setStatus(state) {
        const dot = document.getElementById('stream-status-dot');
        const text = document.getElementById('stream-status-text');
        const badge = document.getElementById('stream-status-badge');
        if (state === 'online') {
            dot.className = 'w-1.5 h-1.5 rounded-full bg-success animate-pulse';
            text.textContent = 'LIVE';
            badge.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-success/10 border border-success/20 text-success text-xs font-medium w-fit';
        } else {
            dot.className = 'w-1.5 h-1.5 rounded-full bg-danger';
            text.textContent = 'OFFLINE';
            badge.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-danger/10 border border-danger/20 text-danger text-xs font-medium w-fit';
        }
    }

    // Initial load
    const streamImg = document.getElementById('camera-stream');
    streamImg.addEventListener('load', onStreamLoad);
    loadStream();

    // Update timestamp
    const el = document.getElementById('stream-time');
    function updateTime() {
        el.textContent = new Date().toLocaleTimeString();
    }
    setInterval(updateTime, 1000);
    updateTime();

    // Poll snapshot every 800ms for near-realtime playback (lighter than 500ms)
    setInterval(loadStream, 800);

    // Auto-retry when offline
    setInterval(() => {
        const img = document.getElementById('camera-stream');
        if (img.style.display === 'none') {
            reloadStream();
        }
    }, 5000);
</script>
@endpush
@endsection
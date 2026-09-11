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
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.25a8.25 8.25 0 0114.997 0"/></svg>
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
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M4.5 19.5a2.25 2.25 0 01-2.25-2.25V6A2.25 2.25 0 014.5 3.75h4.5a2.25 2.25 0 012.25 2.25v2.25a2.25 2.25 0 01-2.25 2.25h-.75v4.5h.75a2.25 2.25 0 012.25 2.25v2.25a2.25 2.25 0 01-2.25 2.25H4.5z"/></svg>
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
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
            </div>
        </div>

        <div class="bg-dark-card border border-border-subtle rounded-xl p-5 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-sm text-text-secondary mb-1">Active Cameras</p>
                <p class="text-2xl font-semibold">{{ $activeCameras }}/{{ $totalCameras }}</p>
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
                    <p class="text-xs text-text-secondary">Select a camera from Live Monitoring</p>
                </div>
                <a href="{{ route('dashboard.live_monitoring.index') }}" class="px-3 py-1.5 text-xs font-medium text-accent-blue hover:text-accent-blue-hover">View All Cameras</a>
            </div>
            <div class="relative aspect-video bg-dark-bg">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center space-y-3">
                        <svg class="w-12 h-12 mx-auto text-text-secondary opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/></svg>
                        <p class="text-sm text-text-secondary">No camera selected</p>
                        <a href="{{ route('dashboard.live_monitoring.index') }}" class="inline-block px-3 py-1.5 bg-accent-blue hover:bg-accent-blue-hover text-white text-xs font-medium rounded-lg transition-colors">Go to Live Monitoring</a>
                    </div>
                </div>
            </div>
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

    <div class="bg-dark-card border border-border-subtle rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-base font-semibold">7-Day Recognition Activity</h2>
                <p class="text-xs text-text-secondary">Daily recognized vs unknown detections</p>
            </div>
            <div class="flex items-center gap-4 text-xs">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-accent-blue"></span>
                    <span class="text-text-secondary">Recognized</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-danger"></span>
                    <span class="text-text-secondary">Unknown</span>
                </div>
            </div>
        </div>
        <div class="relative w-full h-64">
            <canvas id="detectionChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const dailyStats = @json($filledStats);
    
    const ctx = document.getElementById('detectionChart').getContext('2d');
    
    const labels = dailyStats.map(d => {
        const date = new Date(d.date);
        return date.toLocaleDateString('en-US', { weekday: 'short' });
    });
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Recognized',
                    data: dailyStats.map(d => d.recognized),
                    borderColor: '#2563EB',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                },
                {
                    label: 'Unknown',
                    data: dailyStats.map(d => d.unknown),
                    borderColor: '#EF4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        color: '#94a3b8',
                        font: { size: 11 },
                        usePointStyle: true,
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748b', font: { size: 10 } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(255,255,255,0.06)' },
                    ticks: { color: '#64748b', font: { size: 10 } }
                }
            }
        }
    });
</script>
@endpush
@endsection
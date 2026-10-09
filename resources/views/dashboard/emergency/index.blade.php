@extends('layouts.dashboard')

@section('title', 'Emergency Events')

@push('styles')
<style>
    .emergency-card {
        border-left: 4px solid;
    }
    .emergency-card.critical {
        border-left-color: #ef4444;
    }
    .emergency-card.warning {
        border-left-color: #f59e0b;
    }
    .emergency-card.active {
        animation: pulse-red 2s infinite;
    }
    @keyframes pulse-red {
        0%, 100% { background-color: rgba(239, 68, 68, 0.05); }
        50% { background-color: rgba(239, 68, 68, 0.15); }
    }
    .pulse-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        animation: pulse 1.5s infinite;
    }
    .pulse-dot.active {
        background-color: #ef4444;
    }
    .pulse-dot.acknowledged {
        background-color: #f59e0b;
    }
    .pulse-dot.resolved {
        background-color: #22c55e;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.2); }
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-text-primary">Emergency Events</h1>
            <p class="text-text-secondary mt-1">Fall detection and emergency alerts</p>
        </div>
        @if($activeCount > 0)
        <form action="{{ route('dashboard.emergency.acknowledge-all') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="px-4 py-2 bg-yellow-500 text-text-primary rounded-lg hover:bg-yellow-600 transition font-medium">
                Acknowledge All ({{ $activeCount }})
            </button>
        </form>
        @endif
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-dark-card rounded-xl p-4 border border-border-subtle">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-danger/20 rounded-lg">
                    <svg class="w-6 h-6 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-text-primary">{{ $activeCount }}</p>
                    <p class="text-sm text-text-secondary">Active Alerts</p>
                </div>
            </div>
        </div>

        <div class="bg-dark-card rounded-xl p-4 border border-border-subtle">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-orange-900/50 rounded-lg">
                    <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-text-primary">{{ $todayCount }}</p>
                    <p class="text-sm text-text-secondary">Today</p>
                </div>
            </div>
        </div>

        <div class="bg-dark-card rounded-xl p-4 border border-border-subtle">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-danger/20 rounded-lg">
                    <svg class="w-6 h-6 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-text-primary">{{ $events->total() }}</p>
                    <p class="text-sm text-text-secondary">Total Events</p>
                </div>
            </div>
        </div>

        <div class="bg-dark-card rounded-xl p-4 border border-border-subtle">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-accent-blue/20 rounded-lg">
                    <svg class="w-6 h-6 text-accent-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-text-primary">{{ $cameras->count() }}</p>
                    <p class="text-sm text-text-secondary">Cameras</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-dark-card rounded-xl p-4 border border-border-subtle">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-text-secondary mb-1">Status</label>
                <select name="status" class="px-3 py-2 border border-border-subtle rounded-lg bg-dark-elevated text-text-primary focus:ring-2 focus:ring-accent-blue focus:border-accent-blue">
                    <option value="">All</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="acknowledged" {{ request('status') === 'acknowledged' ? 'selected' : '' }}>Acknowledged</option>
                    <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-text-secondary mb-1">Type</label>
                <select name="type" class="px-3 py-2 border border-border-subtle rounded-lg bg-dark-elevated text-text-primary focus:ring-2 focus:ring-accent-blue focus:border-accent-blue">
                    <option value="">All</option>
                    <option value="fall" {{ request('type') === 'fall' ? 'selected' : '' }}>Fall</option>
                    <option value="immobility" {{ request('type') === 'immobility' ? 'selected' : '' }}>Immobility</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-text-secondary mb-1">Camera</label>
                <select name="camera" class="px-3 py-2 border border-border-subtle rounded-lg bg-dark-elevated text-text-primary focus:ring-2 focus:ring-accent-blue focus:border-accent-blue">
                    <option value="">All Cameras</option>
                    @foreach($cameras as $camera)
                        <option value="{{ $camera->id }}" {{ request('camera') == $camera->id ? 'selected' : '' }}>
                            {{ $camera->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-text-secondary mb-1">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="px-3 py-2 border border-border-subtle rounded-lg bg-dark-elevated text-text-primary focus:ring-2 focus:ring-accent-blue focus:border-accent-blue">
            </div>

            <div>
                <label class="block text-sm font-medium text-text-secondary mb-1">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="px-3 py-2 border border-border-subtle rounded-lg bg-dark-elevated text-text-primary focus:ring-2 focus:ring-accent-blue focus:border-accent-blue">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-accent-blue text-text-primary rounded-lg hover:bg-accent-blue-hover transition font-medium">
                    Filter
                </button>
                <a href="{{ route('dashboard.emergency.index') }}" class="px-4 py-2 bg-dark-elevated text-text-secondary rounded-lg hover:bg-dark-elevated/70 transition font-medium">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Events List -->
    <div class="space-y-4">
        @forelse($events as $event)
        <div class="emergency-card bg-dark-card rounded-xl border border-border-subtle {{ $event->status === 'active' ? 'active' : '' }} {{ $event->severity === 'critical' ? 'critical' : 'warning' }} p-4">
            <div class="flex items-start gap-4">
                <!-- Snapshot -->
                <div class="flex-shrink-0">
                    @if($event->snapshot_url)
                        <img src="{{ $event->snapshot_url }}" alt="Emergency snapshot"
                            class="w-24 h-24 object-cover rounded-lg bg-dark-bg">
                    @else
                        <div class="w-24 h-24 bg-dark-bg rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Info -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="pulse-dot {{ $event->status }}"></span>
                        <span class="font-semibold text-text-primary">{{ $event->type_label }}</span>
                        <span class="px-2 py-0.5 rounded text-xs font-medium {{ $event->severity_badge_class }}">
                            {{ ucfirst($event->severity) }}
                        </span>
                    </div>

                    <div class="text-sm text-text-secondary space-y-1">
                        <p>
                            <span class="font-medium text-text-secondary">Camera:</span>
                            {{ $event->camera->name ?? 'Unknown' }}
                            @if($event->camera)
                                <span class="text-text-secondary">({{ $event->camera->location }})</span>
                            @endif
                        </p>
                        <p>
                            <span class="font-medium text-text-secondary">Time:</span>
                            {{ $event->occurred_at->diffForHumans() }}
                            <span class="text-text-secondary">({{ $event->occurred_at->format('M d, Y H:i:s') }})</span>
                        </p>
                        @if($event->fallen_duration)
                            <p>
                                <span class="font-medium text-text-secondary">Fallen Duration:</span>
                                {{ number_format($event->fallen_duration, 1) }}s
                            </p>
                        @endif
                        @if($event->confidence)
                            <p>
                                <span class="font-medium text-text-secondary">Confidence:</span>
                                {{ number_format($event->confidence * 100, 1) }}%
                            </p>
                        @endif
                    </div>

                    @if($event->acknowledged_at && $event->acknowledgedBy)
                        <p class="text-xs text-text-secondary mt-2">
                            Acknowledged by {{ $event->acknowledgedBy->name }} at
                            {{ $event->acknowledged_at->format('H:i:s') }}
                        </p>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex-shrink-0 flex flex-col gap-2">
                    <a href="{{ route('dashboard.emergency.show', $event) }}"
                        class="px-3 py-1.5 bg-dark-elevated text-text-secondary rounded-lg hover:bg-dark-elevated/70 transition text-sm text-center">
                        View
                    </a>

                    @if($event->status === 'active')
                        <form action="{{ route('dashboard.emergency.acknowledge', $event) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full px-3 py-1.5 bg-yellow-500 text-text-primary rounded-lg hover:bg-yellow-600 transition text-sm">
                                Acknowledge
                            </button>
                        </form>
                    @elseif($event->status === 'acknowledged')
                        <form action="{{ route('dashboard.emergency.resolve', $event) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full px-3 py-1.5 bg-success text-text-primary rounded-lg hover:bg-success/90 transition text-sm">
                                Resolve
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-dark-card rounded-xl border border-border-subtle p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-text-secondary mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-lg font-medium text-text-primary mb-1">No Emergency Events</h3>
            <p class="text-text-secondary">There are no emergency events matching your criteria.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($events->hasPages())
        <div class="mt-6">
            {{ $events->links() }}
        </div>
    @endif
</div>
@endsection

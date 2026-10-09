@extends('layouts.dashboard')

@section('title', 'Emergency Event - ' . $event->event_uuid)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('dashboard.emergency.index') }}" class="text-text-secondary hover:text-text-primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-text-primary">Emergency Event Details</h1>
    </div>
    <p class="text-text-secondary">{{ $event->event_uuid }}</p>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Main Content -->
        <div class="space-y-6">
            <!-- Snapshot -->
            <div class="bg-dark-card rounded-xl border border-border-subtle p-4">
                <h2 class="font-semibold text-text-primary mb-4">Snapshot</h2>
                @if($event->snapshot_url)
                    <img src="{{ $event->snapshot_url }}" alt="Emergency snapshot"
                        class="w-full max-h-96 object-contain rounded-lg bg-dark-bg">
                @else
                    <div class="w-full h-64 bg-dark-bg rounded-lg flex items-center justify-center">
                        <svg class="w-12 h-12 text-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Event Info -->
            <div class="bg-dark-card rounded-xl border border-border-subtle p-4">
                <h2 class="font-semibold text-text-primary mb-4">Event Information</h2>
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-text-secondary">Type</dt>
                        <dd class="font-medium text-text-primary">{{ $event->type_label }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-text-secondary">Severity</dt>
                        <dd>
                            <span class="{{ $event->severity_badge_class }} px-2 py-0.5 rounded text-xs font-medium">
                                {{ ucfirst($event->severity) }}
                            </span>
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-text-secondary">Status</dt>
                        <dd>
                            <span class="{{ $event->status_badge_class }} px-2 py-0.5 rounded text-xs font-medium">
                                {{ ucfirst($event->status) }}
                            </span>
                        </dd>
                    </div>
                    @if($event->confidence)
                    <div class="flex justify-between">
                        <dt class="text-text-secondary">Confidence</dt>
                        <dd class="font-medium text-text-primary">{{ number_format($event->confidence * 100, 1) }}%</dd>
                    </div>
                    @endif
                    @if($event->fallen_duration)
                    <div class="flex justify-between">
                        <dt class="text-text-secondary">Fallen Duration</dt>
                        <dd class="font-medium text-text-primary">{{ number_format($event->fallen_duration, 2) }}s</dd>
                    </div>
                    @endif
                    @if($event->body_angle)
                    <div class="flex justify-between">
                        <dt class="text-text-secondary">Body Angle</dt>
                        <dd class="font-medium text-text-primary">{{ number_format($event->body_angle, 1) }}°</dd>
                    </div>
                    @endif
                    @if($event->track_id)
                    <div class="flex justify-between">
                        <dt class="text-text-secondary">Track ID</dt>
                        <dd class="font-medium text-text-primary font-mono text-sm">{{ $event->track_id }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Camera Info -->
            <div class="bg-dark-card rounded-xl border border-border-subtle p-4">
                <h2 class="font-semibold text-text-primary mb-4">Camera</h2>
                @if($event->camera)
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-text-secondary">Name</dt>
                            <dd class="font-medium text-text-primary">{{ $event->camera->name }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-text-secondary">Location</dt>
                            <dd class="font-medium text-text-primary">{{ $event->camera->location }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-text-secondary">Camera ID</dt>
                            <dd class="font-medium text-text-primary">{{ $event->camera->id }}</dd>
                        </div>
                    </dl>
                    <a href="{{ route('dashboard.live_monitoring.show', $event->camera) }}"
                        class="mt-4 block w-full px-4 py-2 bg-accent-blue text-text-primary text-center rounded-lg hover:bg-accent-blue-hover transition font-medium">
                        View Live Feed
                    </a>
                @else
                    <p class="text-text-secondary">Camera not found</p>
                @endif
            </div>

            <!-- Timeline -->
            <div class="bg-dark-card rounded-xl border border-border-subtle p-4">
                <h2 class="font-semibold text-text-primary mb-4">Timeline</h2>
                <dl class="space-y-4">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 bg-danger rounded-full"></div>
                        <div>
                            <dt class="text-sm font-medium text-text-primary">Event Occurred</dt>
                            <dd class="text-sm text-text-secondary">{{ $event->occurred_at->format('M d, Y H:i:s') }}</dd>
                            <dd class="text-xs text-text-secondary">{{ $event->occurred_at->diffForHumans() }}</dd>
                        </div>
                    </div>

                    @if($event->acknowledged_at)
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 bg-yellow-500 rounded-full"></div>
                        <div>
                            <dt class="text-sm font-medium text-text-primary">Acknowledged</dt>
                            <dd class="text-sm text-text-secondary">{{ $event->acknowledged_at->format('M d, Y H:i:s') }}</dd>
                            @if($event->acknowledgedBy)
                                <dd class="text-xs text-text-secondary">by {{ $event->acknowledgedBy->name }}</dd>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 bg-gray-500 rounded-full"></div>
                        <div>
                            <dt class="text-sm font-medium text-text-primary">Record Created</dt>
                            <dd class="text-sm text-text-secondary">{{ $event->created_at->format('M d, Y H:i:s') }}</dd>
                        </div>
                    </div>
                </dl>
            </div>

            <!-- Actions -->
            <div class="bg-dark-card rounded-xl border border-border-subtle p-4">
                <h2 class="font-semibold text-text-primary mb-4">Actions</h2>
                <div class="space-y-3">
                    @if($event->status === 'active')
                        <form action="{{ route('dashboard.emergency.acknowledge', $event) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full px-4 py-2 bg-yellow-500 text-text-primary rounded-lg hover:bg-yellow-600 transition font-medium">
                                Acknowledge Event
                            </button>
                        </form>
                    @elseif($event->status === 'acknowledged')
                        <form action="{{ route('dashboard.emergency.resolve', $event) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full px-4 py-2 bg-success text-text-primary rounded-lg hover:bg-success/90 transition font-medium">
                                Resolve Event
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('dashboard.emergency.index') }}"
                        class="block w-full px-4 py-2 bg-dark-elevated text-text-secondary text-center rounded-lg hover:bg-dark-elevated/70 transition font-medium">
                        Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

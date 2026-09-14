@extends('layouts.dashboard')

@section('title', 'Detection Detail')
@section('page-title', 'Detection History / Detail')
@section('timestamp', now()->format('M d, Y — h:i A'))

@section('content')
<div class="max-w-4xl bg-dark-card border border-border-subtle rounded-xl overflow-hidden">
    <div class="flex items-center justify-between p-4 border-b border-border-subtle">
        <div>
            <h2 class="text-base font-semibold">Detection Detail</h2>
            <p class="text-xs text-text-secondary">{{ $detectionLog->detected_at->format('M d, Y H:i:s') }}</p>
        </div>
        <a href="{{ route('dashboard.detection_history.index') }}" class="px-4 py-2 text-sm font-medium text-text-secondary hover:text-text-primary">Back to List</a>
    </div>

    <div class="p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-sm font-medium text-text-secondary mb-3">Detection Info</h3>
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-text-secondary">Camera</dt>
                        <dd class="font-medium">{{ $detectionLog->camera->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-text-secondary">Location</dt>
                        <dd class="font-medium">{{ $detectionLog->camera->location }}</dd>
                    </div>
                    <div>
                        <dt class="text-text-secondary">Status</dt>
                        <dd>
                            <span class="inline-flex px-2 py-0.5 rounded-full {{ $detectionLog->status_badge_class }} text-xs font-medium">{{ $detectionLog->status_label }}</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-text-secondary">Confidence</dt>
                        <dd class="font-medium">{{ $detectionLog->confidence_percentage }}</dd>
                    </div>
                    <div>
                        <dt class="text-text-secondary">Detected At</dt>
                        <dd class="font-medium">{{ $detectionLog->detected_at->format('M d, Y H:i:s') }}</dd>
                    </div>
                </dl>
            </div>

            <div>
                <h3 class="text-sm font-medium text-text-secondary mb-3">Person Info</h3>
                <dl class="space-y-3 text-sm">
                    @if ($detectionLog->employee_id)
                        <div>
                            <dt class="text-text-secondary">Employee</dt>
                            <dd class="font-medium">{{ $detectionLog->employee_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-text-secondary">Employee ID</dt>
                            <dd class="font-medium">{{ $detectionLog->employee->employee_code }}</dd>
                        </div>
                        <div>
                            <dt class="text-text-secondary">Department</dt>
                            <dd class="font-medium">{{ $detectionLog->employee->department }}</dd>
                        </div>
                        <div>
                            <dt class="text-text-secondary">Position</dt>
                            <dd class="font-medium">{{ $detectionLog->employee->position ?? '—' }}</dd>
                        </div>
                    @else
                        <div>
                            <dt class="text-text-secondary">Status</dt>
                            <dd class="font-medium text-danger">Unknown Person</dd>
                        </div>
                        <div>
                            <dt class="text-text-secondary">Note</dt>
                            <dd class="text-text-secondary">Not registered in the system</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>

        <div>
            <h3 class="text-sm font-medium text-text-secondary mb-3">Snapshot</h3>
            @if ($detectionLog->snapshot_url)
                <div class="aspect-video bg-dark-bg rounded-lg overflow-hidden">
                    <img src="{{ $detectionLog->snapshot_url }}" alt="Detection snapshot" class="w-full h-full object-contain">
                </div>
            @else
                <div class="aspect-video bg-dark-bg rounded-lg flex items-center justify-center">
                    <p class="text-text-secondary">No snapshot available</p>
                </div>
            @endif
        </div>

        @if ($detectionLog->metadata)
            <div>
                <h3 class="text-sm font-medium text-text-secondary mb-3">Additional Data</h3>
                <pre class="bg-dark-bg border border-border-subtle rounded-lg p-4 text-xs text-text-secondary overflow-auto">{{ json_encode($detectionLog->metadata, JSON_PRETTY_PRINT) }}</pre>
            </div>
        @endif
    </div>
</div>
@endsection
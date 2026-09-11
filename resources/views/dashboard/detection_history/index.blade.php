@extends('layouts.dashboard')

@section('title', 'Detection History')
@section('page-title', 'Detection History')
@section('timestamp', now()->format('M d, Y — h:i A'))

@section('content')
<div class="space-y-6">
    {{-- Filters --}}
    <div class="bg-dark-card border border-border-subtle rounded-xl p-4">
        <form method="GET" action="{{ route('dashboard.detection_history.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label for="camera_id" class="block text-sm font-medium mb-1.5">Camera</label>
                <select id="camera_id" name="camera_id" class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                    <option value="">All Cameras</option>
                    @foreach ($cameras as $camera)
                        <option value="{{ $camera->id }}" @selected(request('camera_id') == $camera->id)>{{ $camera->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium mb-1.5">Status</label>
                <select id="status" name="status" class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                    <option value="">All Status</option>
                    <option value="recognized" @selected(request('status') === 'recognized')>Recognized</option>
                    <option value="unknown" @selected(request('status') === 'unknown')>Unknown</option>
                </select>
            </div>

            <div>
                <label for="employee_id" class="block text-sm font-medium mb-1.5">Employee</label>
                <select id="employee_id" name="employee_id" class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                    <option value="">All Employees</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}" @selected(request('employee_id') == $employee->id)>{{ $employee->name }} ({{ $employee->employee_code }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="date_from" class="block text-sm font-medium mb-1.5">Date From</label>
                <input id="date_from" name="date_from" type="date" value="{{ request('date_from') }}" class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
            </div>

            <div>
                <label for="date_to" class="block text-sm font-medium mb-1.5">Date To</label>
                <input id="date_to" name="date_to" type="date" value="{{ request('date_to') }}" class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
            </div>

            <div class="lg:col-span-5 flex items-end gap-3">
                <button type="submit" class="px-4 py-2 bg-accent-blue hover:bg-accent-blue-hover text-white text-sm font-medium rounded-lg transition-colors">Filter</button>
                <a href="{{ route('dashboard.detection_history.index') }}" class="px-4 py-2 text-sm font-medium text-text-secondary hover:text-text-primary">Reset</a>
            </div>
        </form>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4" id="stats-cards">
        <div class="bg-dark-card border border-border-subtle rounded-xl p-5">
            <p class="text-sm text-text-secondary mb-1">Total Detections</p>
            <p class="text-2xl font-semibold" id="stat-total">-</p>
        </div>
        <div class="bg-dark-card border border-border-subtle rounded-xl p-5">
            <p class="text-sm text-text-secondary mb-1">Recognized</p>
            <p class="text-2xl font-semibold text-success" id="stat-recognized">-</p>
        </div>
        <div class="bg-dark-card border border-border-subtle rounded-xl p-5">
            <p class="text-sm text-text-secondary mb-1">Unknown</p>
            <p class="text-2xl font-semibold text-danger" id="stat-unknown">-</p>
        </div>
        <div class="bg-dark-card border border-border-subtle rounded-xl p-5">
            <p class="text-sm text-text-secondary mb-1">Avg Confidence</p>
            <p class="text-2xl font-semibold" id="stat-confidence">-</p>
        </div>
    </div>

    {{-- Chart --}}
    <div class="bg-dark-card border border-border-subtle rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-base font-semibold">7-Day Detection Activity</h2>
                <p class="text-xs text-text-secondary">Daily recognized vs unknown detections</p>
            </div>
        </div>
        <div class="relative h-64" id="detection-chart">
            <canvas id="detectionChart"></canvas>
        </div>
    </div>

    {{-- Detection Logs Table --}}
    <div class="bg-dark-card border border-border-subtle rounded-xl overflow-hidden">
        <div class="p-4 border-b border-border-subtle">
            <h2 class="text-base font-semibold">Detection Logs</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-dark-elevated/50 text-text-secondary text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3 font-medium">Time</th>
                        <th class="px-4 py-3 font-medium">Camera</th>
                        <th class="px-4 py-3 font-medium">Person</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium">Confidence</th>
                        <th class="px-4 py-3 font-medium">Snapshot</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-subtle" id="logs-table-body">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-dark-elevated/30 transition-colors">
                            <td class="px-4 py-3 text-text-secondary">{{ $log->detected_at->format('M d, Y H:i:s') }}</td>
                            <td class="px-4 py-3 text-text-secondary">{{ $log->camera->name }}</td>
                            <td class="px-4 py-3">
                                @if ($log->employee_id)
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-accent-blue/20 flex items-center justify-center text-accent-blue text-xs font-semibold">{{ collect(explode(' ', $log->employee_name))->map(fn ($part) => substr($part, 0, 1))->take(2)->join('') }}</div>
                                        <span class="font-medium">{{ $log->employee_name }}</span>
                                    </div>
                                @else
                                    <span class="text-text-secondary">Unknown Person</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2 py-0.5 rounded-full {{ $log->status_badge_class }} text-xs font-medium">{{ $log->status_label }}</span>
                            </td>
                            <td class="px-4 py-3 text-text-secondary">{{ $log->confidence_percentage }}</td>
                            <td class="px-4 py-3">
                                @if ($log->snapshot_path)
                                    <a href="{{ asset('storage/' . $log->snapshot_path) }}" target="_blank" class="text-accent-blue hover:underline text-xs">View</a>
                                @else
                                    <span class="text-text-secondary text-xs">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-text-secondary">No detection logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($logs->hasPages())
            <div class="p-4 border-t border-border-subtle">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Load stats
    async function loadStats() {
        const params = new URLSearchParams(window.location.search);
        const response = await fetch(`{{ route('dashboard.detection_history.stats') }}?${params.toString()}`);
        const data = await response.json();
        
        document.getElementById('stat-total').textContent = data.total.toLocaleString();
        document.getElementById('stat-recognized').textContent = data.recognized.toLocaleString();
        document.getElementById('stat-unknown').textContent = data.unknown.toLocaleString();
        document.getElementById('stat-confidence').textContent = data.avg_confidence + '%';
        
        // Update chart
        updateChart(data.daily_stats);
    }

    function updateChart(dailyStats) {
        const ctx = document.getElementById('detectionChart').getContext('2d');
        
        if (window.detectionChart) {
            window.detectionChart.destroy();
        }
        
        const labels = dailyStats.map(d => {
            const date = new Date(d.date);
            return date.toLocaleDateString('en-US', { weekday: 'short' });
        });
        
        window.detectionChart = new Chart(ctx, {
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
    }

    // Load on page load
    document.addEventListener('DOMContentLoaded', loadStats);
</script>
@endpush
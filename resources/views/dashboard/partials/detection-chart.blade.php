@props(['stats'])

<div class="bg-dark-card border border-border-subtle rounded-xl p-5">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-base font-semibold">{{ $title ?? '7-Day Detection Activity' }}</h2>
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
    <div class="relative w-full h-64" id="detection-chart">
        <canvas id="detectionChart"></canvas>
        <p id="chart-fallback" class="hidden absolute inset-0 items-center justify-center text-sm text-text-secondary text-center px-6">Chart library could not be loaded. Statistik tetap tampil di atas.</p>
    </div>
</div>

@once
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush
@endonce

@push('scripts')
<script>
    const dailyStats = @json($stats);

    function renderDetectionChart() {
        const canvas = document.getElementById('detectionChart');
        const fallback = document.getElementById('chart-fallback');
        if (!canvas) return;

        if (typeof window.Chart === 'undefined') {
            canvas.style.display = 'none';
            fallback.classList.remove('hidden');
            fallback.classList.add('flex');
            return;
        }

        const labels = dailyStats.map((day) => {
            const date = new Date(`${day.date}T00:00:00`);
            return date.toLocaleDateString('en-US', { weekday: 'short' });
        });

        new Chart(canvas, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Recognized',
                        data: dailyStats.map((day) => day.recognized),
                        borderColor: '#2563EB',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    },
                    {
                        label: 'Unknown',
                        data: dailyStats.map((day) => day.unknown),
                        borderColor: '#EF4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#64748b', font: { size: 10 } },
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255,255,255,0.06)' },
                        ticks: { color: '#64748b', font: { size: 10 } },
                    },
                },
            },
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (typeof window.Chart !== 'undefined') {
            renderDetectionChart();
        } else {
            window.addEventListener('load', renderDetectionChart, { once: true });
        }
    });
</script>
@endpush
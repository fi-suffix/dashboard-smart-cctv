@extends('layouts.dashboard')

@section('title', 'Settings')
@section('page-title', 'Settings')
@section('timestamp', now()->format('M d, Y — h:i A'))

@section('content')
<div class="space-y-6">
    <form method="POST" action="{{ route('dashboard.setting.save') }}">
        @csrf

        <!-- Face Recognition Settings -->
        <div class="bg-dark-card border border-border-subtle rounded-xl p-5">
            <h2 class="text-base font-semibold text-text-primary mb-4">Face Recognition Settings</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-1.5">Recognition Threshold</label>
                    <input type="range" name="face_recognition[threshold]" min="0.3" max="0.9" step="0.05" value="{{ $faceRecognition['threshold'] ?? 0.6 }}" class="w-full" id="threshold">
                    <p class="text-xs text-text-secondary mt-1">Current: <span id="threshold-value" class="text-text-primary">{{ number_format($faceRecognition['threshold'] ?? 0.6, 2) }}</span> (Lower = more matches, Higher = stricter)</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-1.5">Minimum Face Size</label>
                    <input type="number" name="face_recognition[min_face_size]" value="{{ $faceRecognition['min_face_size'] ?? 30 }}" min="20" max="100" class="w-full max-w-xs rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                    <p class="text-xs text-text-secondary mt-1">Minimum face size in pixels for detection</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-1.5">Frame Processing Rate</label>
                    <input type="number" name="face_recognition[frame_rate]" value="{{ $faceRecognition['frame_rate'] ?? 5 }}" min="1" max="30" class="w-full max-w-xs rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                    <p class="text-xs text-text-secondary mt-1">Process every N frames (higher = less CPU, lower = more responsive)</p>
                </div>
            </div>
        </div>

        <!-- Camera Defaults -->
        <div class="bg-dark-card border border-border-subtle rounded-xl p-5">
            <h2 class="text-base font-semibold text-text-primary mb-4">Camera Defaults</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-1.5">Default Reconnect Interval (seconds)</label>
                    <input type="number" name="camera_defaults[reconnect_interval]" value="{{ $cameraDefaults['reconnect_interval'] ?? 5 }}" min="1" max="300" class="w-full max-w-xs rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-1.5">Default Status for New Cameras</label>
                    <select name="camera_defaults[default_status]" class="w-full max-w-xs rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                        <option value="active" {{ ($cameraDefaults['default_status'] ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ ($cameraDefaults['default_status'] ?? 'active') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="maintenance" {{ ($cameraDefaults['default_status'] ?? 'active') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Storage & Cleanup -->
        <div class="bg-dark-card border border-border-subtle rounded-xl p-5">
            <h2 class="text-base font-semibold text-text-primary mb-4">Storage & Cleanup</h2>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-5">
                <div class="bg-dark-elevated/50 border border-border-subtle rounded-lg p-4">
                    <p class="text-sm text-text-secondary mb-1">Detection Logs</p>
                    <p class="text-2xl font-semibold text-text-primary">{{ number_format($logCount) }}</p>
                </div>
                <div class="bg-dark-elevated/50 border border-border-subtle rounded-lg p-4">
                    <p class="text-sm text-text-secondary mb-1">Emergency Events</p>
                    <p class="text-2xl font-semibold text-text-primary">{{ number_format($emergencyEventCount) }}</p>
                </div>
                <div class="bg-dark-elevated/50 border border-border-subtle rounded-lg p-4">
                    <p class="text-sm text-text-secondary mb-1">Laravel Snapshots</p>
                    <p class="text-2xl font-semibold text-text-primary">{{ number_format($snapshotFiles) }}</p>
                    <p class="text-xs text-text-secondary">{{ $snapshotSizeFormatted }}</p>
                </div>
                <div class="bg-dark-elevated/50 border border-border-subtle rounded-lg p-4">
                    <p class="text-sm text-text-secondary mb-1">Python Snapshots</p>
                    <p class="text-2xl font-semibold text-text-primary">{{ number_format($pythonSnapshotFiles) }}</p>
                    <p class="text-xs text-text-secondary">{{ $pythonSnapshotSizeFormatted }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('dashboard.setting.cleanup') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="retention_days" class="block text-sm font-medium text-text-secondary mb-1.5">Log Retention (days)</label>
                        <input type="number" id="retention_days" name="retention_days" value="{{ old('retention_days', 30) }}" min="1" max="365" class="w-full max-w-xs rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                        <p class="text-xs text-text-secondary mt-1">Hapus log deteksi & emergency event yang lebih tua dari ini</p>
                    </div>
                    <div>
                        <label for="snapshot_retention" class="block text-sm font-medium text-text-secondary mb-1.5">Laravel Snapshot Retention (days)</label>
                        <input type="number" id="snapshot_retention" name="snapshot_retention" value="{{ old('snapshot_retention', 7) }}" min="1" max="90" class="w-full max-w-xs rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                        <p class="text-xs text-text-secondary mt-1">Hapus snapshot 'yatim' yang lebih tua dari ini</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-dark-elevated/30 border border-border-subtle rounded-lg p-4">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="clean_python_snapshots" value="1" class="w-5 h-5 rounded border-border-subtle bg-dark-elevated accent-accent-blue focus:ring-accent-blue">
                            <span class="text-sm font-medium text-text-secondary">Bersihkan Python Snapshots</span>
                        </label>
                        <p class="text-xs text-text-secondary mt-2 ml-8">Hapus file snapshot di folder Facial-recognition-cctv/snapshots</p>
                    </div>
                    <div>
                        <label for="python_retention_days" class="block text-sm font-medium text-text-secondary mb-1.5">Python Snapshot Retention (days)</label>
                        <input type="number" id="python_retention_days" name="python_retention_days" value="{{ old('python_retention_days', 7) }}" min="1" max="90" class="w-full max-w-xs rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                        <p class="text-xs text-text-secondary mt-1">Hapus file snapshot Python yang lebih tua dari ini</p>
                    </div>
                </div>

                <button type="submit" class="px-4 py-2 bg-danger hover:bg-danger/90 text-text-primary text-sm font-medium rounded-lg transition-colors">
                    Run Cleanup Now
                </button>
            </form>
        </div>

        <!-- API Integration -->
        <div class="bg-dark-card border border-border-subtle rounded-xl p-5">
            <h2 class="text-base font-semibold text-text-primary mb-4">API Integration</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-1.5">Python Service URL</label>
                    <input type="text" name="api_integration[python_service_url]" value="{{ $apiIntegration['python_service_url'] ?? 'http://localhost:8001' }}" class="w-full max-w-md rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-1.5">API Key</label>
                    <input type="password" name="api_integration[api_key]" value="{{ $apiIntegration['api_key'] ?? 'your-secret-api-key-here' }}" class="w-full max-w-md rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                </div>

                <button type="button" id="test-connection" class="px-4 py-2 bg-accent-blue hover:bg-accent-blue-hover text-text-primary text-sm font-medium rounded-lg transition-colors">Test Connection</button>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('dashboard') }}" class="px-4 py-2 text-sm font-medium text-text-secondary hover:text-text-primary">Cancel</a>
            <button type="submit" class="px-4 py-2 rounded-lg bg-accent-blue hover:bg-accent-blue-hover text-text-primary text-sm font-medium transition-colors">Save Settings</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.getElementById('threshold').addEventListener('input', function() {
        document.getElementById('threshold-value').textContent = parseFloat(this.value).toFixed(2);
    });

    document.getElementById('test-connection').addEventListener('click', async function() {
        const btn = this;
        const originalText = btn.textContent;
        const url = document.querySelector('input[name="api_integration[python_service_url]"]').value;

        btn.textContent = 'Testing...';
        btn.disabled = true;

        try {
            const response = await fetch(url + '/health');
            if (response.ok) {
                alert('Connection successful!');
            } else {
                alert('Connection failed: ' + response.status);
            }
        } catch (e) {
            alert('Connection error: ' + e.message);
        }

        btn.textContent = originalText;
        btn.disabled = false;
    });
</script>
@endpush
@endsection

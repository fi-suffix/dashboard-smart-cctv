@extends('layouts.dashboard')

@section('title', 'Settings')
@section('page-title', 'Settings')
@section('timestamp', now()->format('M d, Y — h:i A'))

@section('content')
<div class="space-y-6">
    <div class="bg-dark-card border border-border-subtle rounded-xl p-5">
        <h2 class="text-base font-semibold mb-4">Face Recognition Settings</h2>
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1.5">Recognition Threshold</label>
                <input type="range" id="threshold" min="0.3" max="0.9" step="0.05" value="0.6" class="w-full">
                <p class="text-xs text-text-secondary mt-1">Current: <span id="threshold-value">0.60</span> (Lower = more matches, Higher = stricter)</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium mb-1.5">Minimum Face Size</label>
                <input type="number" id="min-face-size" value="30" min="20" max="100" class="w-full max-w-xs rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                <p class="text-xs text-text-secondary mt-1">Minimum face size in pixels for detection</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium mb-1.5">Frame Processing Rate</label>
                <input type="number" id="frame-rate" value="5" min="1" max="30" class="w-full max-w-xs rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                <p class="text-xs text-text-secondary mt-1">Process every N frames (higher = less CPU, lower = more responsive)</p>
            </div>
        </div>
    </div>

    <div class="bg-dark-card border border-border-subtle rounded-xl p-5">
        <h2 class="text-base font-semibold mb-4">Camera Defaults</h2>
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1.5">Default Reconnect Interval (seconds)</label>
                <input type="number" id="reconnect-interval" value="5" min="1" max="300" class="w-full max-w-xs rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
            </div>
            
            <div>
                <label class="block text-sm font-medium mb-1.5">Default Status for New Cameras</label>
                <select id="default-camera-status" class="w-full max-w-xs rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>
    </div>

    <div class="bg-dark-card border border-border-subtle rounded-xl p-5">
        <h2 class="text-base font-semibold mb-4">Storage & Cleanup</h2>
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1.5">Retention Period (days)</label>
                <input type="number" id="retention-days" value="30" min="1" max="365" class="w-full max-w-xs rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                <p class="text-xs text-text-secondary mt-1">Auto-delete detection logs older than this</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium mb-1.5">Snapshot Retention (days)</label>
                <input type="number" id="snapshot-retention" value="7" min="1" max="90" class="w-full max-w-xs rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
                <p class="text-xs text-text-secondary mt-1">Auto-delete snapshot images older than this</p>
            </div>
            
            <button class="px-4 py-2 bg-danger/10 text-danger hover:bg-danger/20 text-sm font-medium rounded-lg transition-colors">Run Cleanup Now</button>
        </div>
    </div>

    <div class="bg-dark-card border border-border-subtle rounded-xl p-5">
        <h2 class="text-base font-semibold mb-4">API Integration</h2>
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1.5">Python Service URL</label>
                <input type="text" id="python-service-url" value="http://localhost:8001" class="w-full max-w-md rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
            </div>
            
            <div>
                <label class="block text-sm font-medium mb-1.5">API Key</label>
                <input type="password" id="api-key" value="your-secret-api-key-here" class="w-full max-w-md rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none">
            </div>
            
            <button id="test-connection" class="px-4 py-2 bg-accent-blue hover:bg-accent-blue-hover text-white text-sm font-medium rounded-lg transition-colors">Test Connection</button>
        </div>
    </div>

    <div class="flex justify-end gap-3">
        <button class="px-4 py-2 text-sm font-medium text-text-secondary hover:text-text-primary">Cancel</button>
        <button class="px-4 py-2 rounded-lg bg-accent-blue hover:bg-accent-blue-hover text-white text-sm font-medium transition-colors">Save Settings</button>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('threshold').addEventListener('input', function() {
        document.getElementById('threshold-value').textContent = parseFloat(this.value).toFixed(2);
    });

    document.getElementById('test-connection').addEventListener('click', async function() {
        const btn = this;
        const originalText = btn.textContent;
        btn.textContent = 'Testing...';
        btn.disabled = true;

        try {
            const response = await fetch('http://localhost:8001/health');
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
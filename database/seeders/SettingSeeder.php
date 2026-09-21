<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        // Face Recognition Settings
        Setting::setValue('face_recognition.threshold', 0.6, 'float', 'face_recognition', 'Recognition Threshold', 'Lower = more matches, Higher = stricter (0.3-0.9)');
        Setting::setValue('face_recognition.min_face_size', 30, 'integer', 'face_recognition', 'Minimum Face Size', 'Minimum face size in pixels for detection (20-100)');
        Setting::setValue('face_recognition.frame_rate', 5, 'integer', 'face_recognition', 'Frame Processing Rate', 'Process every N frames (higher = less CPU, lower = more responsive) (1-30)');

        // Camera Defaults
        Setting::setValue('camera_defaults.reconnect_interval', 5, 'integer', 'camera_defaults', 'Default Reconnect Interval', 'Default reconnect interval in seconds (1-300)');
        Setting::setValue('camera_defaults.default_status', 'active', 'string', 'camera_defaults', 'Default Status for New Cameras', 'Default status when creating new cameras');

        // API Integration (synced from .env)
        Setting::setValue('api_integration.python_service_url', env('PYTHON_SERVICE_URL', 'http://localhost:8001'), 'string', 'api_integration', 'Python Service URL', 'Base URL of the Python FastAPI service');
        Setting::setValue('api_integration.api_key', env('FACE_RECOGNITION_API_KEY', 'your-secret-api-key-here'), 'string', 'api_integration', 'API Key', 'API key for Python service authentication');
    }
}

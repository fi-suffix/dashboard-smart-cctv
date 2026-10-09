<?php

use App\Http\Controllers\Api\EmergencyEventController;
use App\Http\Controllers\Api\FaceRecognitionController;
use App\Http\Controllers\Api\RecognitionEventController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\AdminUserController;
use App\Http\Controllers\Dashboard\CameraController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\DetectionLogController;
use App\Http\Controllers\Dashboard\EmployeeController;
use App\Http\Controllers\Dashboard\EmergencyEventController as DashboardEmergencyEventController;
use App\Http\Controllers\Dashboard\LiveMonitoringController;
use App\Http\Controllers\Dashboard\RecognitionEventController as DashboardRecognitionEventController;
use App\Http\Controllers\Dashboard\SettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::prefix('dashboard')->name('dashboard.')->middleware('auth')->group(function () {
    Route::resource('employee', EmployeeController::class)->except(['show']);

    Route::resource('camera', CameraController::class)->except(['show']);
    Route::post('camera/test-connection', [CameraController::class, 'testConnection'])->name('camera.test-connection');
    Route::post('camera/{camera}/toggle-status', [CameraController::class, 'toggleStatus'])->name('camera.toggle-status');

    Route::resource('admin', AdminUserController::class)->except(['show']);

    Route::get('live_monitoring', [LiveMonitoringController::class, 'index'])->name('live_monitoring.index');
    Route::get('live_monitoring/{camera}', [LiveMonitoringController::class, 'show'])->name('live_monitoring.show');

    Route::get('detection_history', [DetectionLogController::class, 'index'])->name('detection_history.index');
    Route::get('detection_history/stats', [DetectionLogController::class, 'stats'])->name('detection_history.stats');
    Route::get('detection_history/{detectionLog}', [DetectionLogController::class, 'show'])->name('detection_history.show');

    Route::get('setting', [SettingsController::class, 'index'])->name('setting.index');
    Route::post('setting', [SettingsController::class, 'save'])->name('setting.save');
    Route::post('setting/cleanup', [SettingsController::class, 'cleanup'])->name('setting.cleanup');

    Route::get('recognition_events/{recognitionEvent}/snapshot', [DashboardRecognitionEventController::class, 'snapshot'])
        ->name('recognition_events.snapshot');

    // Emergency Events Routes
    Route::get('emergency', [DashboardEmergencyEventController::class, 'index'])->name('emergency.index');
    Route::get('emergency/{emergencyEvent}', [DashboardEmergencyEventController::class, 'show'])->name('emergency.show');
    Route::post('emergency/{emergencyEvent}/acknowledge', [DashboardEmergencyEventController::class, 'acknowledge'])->name('emergency.acknowledge');
    Route::post('emergency/{emergencyEvent}/resolve', [DashboardEmergencyEventController::class, 'resolve'])->name('emergency.resolve');
    Route::post('emergency/acknowledge-all', [DashboardEmergencyEventController::class, 'acknowledgeAll'])->name('emergency.acknowledge-all');
    Route::get('emergency/{emergencyEvent}/snapshot', [DashboardEmergencyEventController::class, 'snapshot'])->name('emergency.snapshot');
});

// Internal API for the Python AI service (protected by X-Internal-Token)
Route::prefix('api/internal')->middleware(['internal.token', 'throttle:internal-events'])->group(function () {
    Route::post('recognition-events', [RecognitionEventController::class, 'store']);
    Route::post('emergency-events', [EmergencyEventController::class, 'store']);
});

// API routes for Python service (protected by API key)
Route::prefix('api/face-recognition')->middleware('api.key')->group(function () {
    Route::get('cameras', [FaceRecognitionController::class, 'cameras']);
    Route::get('cameras/{id}', [FaceRecognitionController::class, 'camera']);
    Route::get('face-embeddings', [FaceRecognitionController::class, 'faceEmbeddings']);
    Route::post('detection-logs', [FaceRecognitionController::class, 'storeDetectionLog']);
    Route::put('employee-photos/{photoId}/embedding', [FaceRecognitionController::class, 'updateEmbedding']);
});

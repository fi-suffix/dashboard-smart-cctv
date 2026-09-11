<?php

use App\Http\Controllers\Dashboard\CameraController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\DetectionLogController;
use App\Http\Controllers\Dashboard\EmployeeController;
use App\Http\Controllers\Dashboard\LiveMonitoringController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::resource('employee', EmployeeController::class)->only(['index', 'create', 'store']);
    
    Route::resource('camera', CameraController::class)->except(['show']);
    Route::post('camera/{camera}/toggle-status', [CameraController::class, 'toggleStatus'])->name('camera.toggle-status');
    
    Route::get('live_monitoring', [LiveMonitoringController::class, 'index'])->name('live_monitoring.index');
    Route::get('live_monitoring/{camera}', [LiveMonitoringController::class, 'show'])->name('live_monitoring.show');
    
    Route::get('detection_history', [DetectionLogController::class, 'index'])->name('detection_history.index');
    Route::get('detection_history/{detectionLog}', [DetectionLogController::class, 'show'])->name('detection_history.show');
    Route::get('detection_history/stats', [DetectionLogController::class, 'stats'])->name('detection_history.stats');
    
    Route::get('setting', function () {
        return view('dashboard.setting.index');
    })->name('setting.index');
});

// API routes for Python service (protected by API key)
Route::prefix('api/face-recognition')->middleware('api.key')->group(function () {
    Route::get('cameras', [\App\Http\Controllers\Api\FaceRecognitionController::class, 'cameras']);
    Route::get('cameras/{id}', [\App\Http\Controllers\Api\FaceRecognitionController::class, 'camera']);
    Route::get('face-embeddings', [\App\Http\Controllers\Api\FaceRecognitionController::class, 'faceEmbeddings']);
    Route::post('detection-logs', [\App\Http\Controllers\Api\FaceRecognitionController::class, 'storeDetectionLog']);
    Route::put('employee-photos/{photoId}/embedding', [\App\Http\Controllers\Api\FaceRecognitionController::class, 'updateEmbedding']);
});
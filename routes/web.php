<?php

use App\Http\Controllers\Dashboard\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard.index');
})->name('dashboard');

Route::get('/hai', function () {
    return view('dashboard.coba');
})->name('dashboard.coba');


Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::resource('employee', EmployeeController::class)->only(['index', 'create', 'store']);

    Route::get('/camera', function () {
        return view('dashboard.camera.index');
    })->name('camera.index');

    Route::get('/live_monitoring', function () {
    return view('dashboard.live_monitoring.index');
    })->name('live_monitoring.index');

    Route::get('/detection_history', function () {
    return view('dashboard.detection_history.index');
    })->name('detection_history.index');

    Route::get('/setting', function () {
    return view('dashboard.setting.index');
    })->name('setting.index');
});

<?php

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
    Route::get('/employees', function () {
        return view('dashboard.employee.index');
    })->name('employees.index');

    Route::get('/cameras', function () {
        return view('dashboard.camera.index');
    })->name('cameras.index');

    Route::get('/live_monitoring', function () {
    return view('dashboard.live_monitoring.index');
    })->name('live_monitoring.index');

    Route::get('/setting', function () {
    return view('dashboard.setting.index');
    })->name('setting.index');
});

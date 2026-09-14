<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Camera;
$camera = Camera::find(6);
if ($camera) {
    $camera->rtsp_url = '0'; // Use webcam index 0 directly
    $camera->save();
    echo "Camera 6 updated to use webcam directly\n";
}
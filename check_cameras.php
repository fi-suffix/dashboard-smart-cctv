<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Camera;
echo "Cameras in DB:\n";
foreach (Camera::all() as $c) {
    echo "ID:{$c->id} Name:{$c->name} RTSP:{$c->rtsp_url} Status:{$c->status}\n";
}
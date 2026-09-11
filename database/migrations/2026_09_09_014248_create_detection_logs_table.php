<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detection_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('camera_id')->constrained()->onDelete('cascade');
            $table->foreignId('employee_id')->nullable()->constrained()->onDelete('set null');
            $table->string('employee_name')->nullable();
            $table->decimal('confidence', 5, 4)->default(0);
            $table->string('status', 20); // recognized, unknown
            $table->timestamp('detected_at');
            $table->string('snapshot_path')->nullable();
            $table->json('metadata')->nullable(); // bbox, additional info
            $table->timestamps();
            
            $table->index(['camera_id', 'detected_at']);
            $table->index(['employee_id', 'detected_at']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detection_logs');
    }
};
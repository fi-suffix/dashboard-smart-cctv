<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recognition_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_uuid', 64)->unique();
            $table->foreignId('camera_id')->constrained()->onDelete('cascade');
            $table->foreignId('employee_id')->nullable()->constrained()->onDelete('set null');
            $table->string('type', 10); // known | unknown
            $table->float('similarity')->nullable();
            $table->string('track_id', 64);
            $table->string('snapshot_path')->nullable();
            $table->json('bbox')->nullable();
            $table->timestamp('occurred_at')->index();
            $table->timestamps();

            $table->index(['camera_id', 'occurred_at']);
            $table->index(['type', 'occurred_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recognition_events');
    }
};

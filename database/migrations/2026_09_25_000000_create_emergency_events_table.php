<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('emergency_events', function (Blueprint $table) {
            $table->id();
            $table->uuid('event_uuid')->unique();
            $table->foreignId('camera_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['fall', 'immobility', 'other'])->default('fall');
            $table->enum('severity', ['warning', 'critical'])->default('critical');
            $table->decimal('confidence', 4, 3)->nullable();
            $table->string('snapshot_path', 500)->nullable();
            $table->json('bbox')->nullable();
            $table->decimal('fallen_duration', 6, 2)->nullable()->comment('Duration person was lying down in seconds');
            $table->decimal('body_angle', 5, 2)->nullable()->comment('Body angle when fall detected');
            $table->string('track_id', 100)->nullable();
            $table->enum('status', ['active', 'acknowledged', 'resolved'])->default('active');
            $table->timestamp('acknowledged_at')->nullable();
            $table->foreignId('acknowledged_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('occurred_at');
            $table->timestamps();

            // Indexes for common queries
            $table->index(['camera_id', 'status']);
            $table->index(['type', 'status']);
            $table->index('occurred_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emergency_events');
    }
};

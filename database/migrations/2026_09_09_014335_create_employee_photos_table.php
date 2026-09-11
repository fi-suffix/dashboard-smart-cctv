<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->json('embedding')->nullable(); // Store face embedding as JSON array
            $table->string('source')->default('upload'); // upload, webcam, capture
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            
            $table->index('employee_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_photos');
    }
};
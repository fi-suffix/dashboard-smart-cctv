<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cameras', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('rtsp_url');
            $table->string('location');
            $table->string('status', 20)->default('active'); // active, inactive, maintenance
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->integer('reconnect_interval')->default(5);
            $table->timestamp('last_connected_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cameras');
    }
};
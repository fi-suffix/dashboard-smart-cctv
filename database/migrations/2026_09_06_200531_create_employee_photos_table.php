<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code', 50)->unique();
            $table->string('name');
            $table->string('email')->nullable(); // Ditambahkan
            $table->string('department');
            $table->string('position')->nullable(); // Ditambahkan
            $table->string('status', 20)->default('active');
            $table->unsignedInteger('recognitions')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
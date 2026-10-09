<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cameras', function (Blueprint $table) {
            $table->string('rtsp_url_main')->nullable()->after('rtsp_url');
            $table->string('rtsp_url_sub')->nullable()->after('rtsp_url_main');
        });
    }

    public function down(): void
    {
        Schema::table('cameras', function (Blueprint $table) {
            $table->dropColumn(['rtsp_url_sub']);
            $table->dropColumn(['rtsp_url_main']);
        });
    }
};

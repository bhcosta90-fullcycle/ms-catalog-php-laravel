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
        Schema::create('genre_video', function (Blueprint $table) {
            $table->foreignUuid('genre_id')->on('genres');
            $table->foreignUuid('video_id')->on('videos');

            $table->primary(['genre_id', 'video_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genre_video');
    }
};

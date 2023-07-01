<?php

use App\Enums\ImageTypes;
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
        Schema::create('images_video', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('video_id')->on('videos');
            $table->string('path');
            $table->enum('type', array_keys(ImageTypes::cases()));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images_video');
    }
};

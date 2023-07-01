<?php

use App\Enums\MediaTypes;
use BRCas\MV\Domain\Enum\MediaStatus;
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
        Schema::create('medias_video', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('video_id')->on('videos');
            $table->string('file_path');
            $table->string('encoded_path')->nullable();
            $table->enum('media_status', array_keys(MediaStatus::cases()))
                    ->default(MediaStatus::PENDING->value);
            $table->enum('type', array_keys(MediaTypes::cases()));
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medias_video');
    }
};

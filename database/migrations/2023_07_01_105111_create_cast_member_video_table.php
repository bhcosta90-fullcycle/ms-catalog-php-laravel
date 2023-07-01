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
        Schema::create('cast_member_video', function (Blueprint $table) {
            $table->foreignUuid('cast_member_id')->on('cast_members');
            $table->foreignUuid('video_id')->on('videos');

            $table->primary(['cast_member_id', 'video_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cast_member_video');
    }
};

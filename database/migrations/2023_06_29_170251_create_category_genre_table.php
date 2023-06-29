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
        Schema::create('category_genre', function (Blueprint $table) {
            $table->foreignUuid('category_id')->on('categories');
            $table->foreignUuid('genre_id')->on('genres');

            $table->primary(['category_id', 'genre_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_genre');
    }
};

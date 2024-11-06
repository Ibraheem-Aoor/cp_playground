<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            // $table->unsignedBigInteger('album_id');
            // $table->unsignedBigInteger('artist_id');
            // $table->unsignedBigInteger('mood_id');
            // $table->unsignedBigInteger('genre_id');
            // $table->unsignedBigInteger('playlist_id');
            // $table->enum('status' , ['completed' , 'pending' , 'not_played'])->default('not_played');  $table->string('title');
            $table->string('artist');
            $table->string('url'); // URL where the song is stored
            $table->unsignedBigInteger('playlist_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('songs');
    }
};

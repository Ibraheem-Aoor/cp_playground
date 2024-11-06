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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('playlist_id');
            $table->unsignedBigInteger('zone_id');
            $table->dateTime('start_time');
            $table->boolean('is_shuffle')->default(false);
            $table->integer('weight')->default(1); // For playlist priority/mixing
            $table->boolean('is_active')->default(true);

            $table->foreign('playlist_id')->references('id')->on('playlists')->onDelete('cascade');
            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};

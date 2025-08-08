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
       Schema::create('exercises', function (Blueprint $table) {
    $table->id();
    $table->foreignId('video_id')->constrained('videos')->cascadeOnDelete();
    $table->enum('question_type', ['multiple_choice', 'true_false']);
    $table->json('content')->nullable();
    $table->integer('pause_time');
    $table->integer('display_duration');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};

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
       Schema::create('course_progress', function (Blueprint $table) {
    $table->id();

    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('course_id')->constrained()->cascadeOnDelete();

    $table->unsignedInteger('total_exams')->default(0);      // عدد اختبارات الفيديوهات في الكورس
    $table->unsignedInteger('passed_exams')->default(0);     // عدد الاختبارات التي نجح فيها الطالب
    $table->unsignedInteger('progress_percent')->default(0); // نسبة النجاح = (passed / total) * 100

    $table->boolean('course_passed')->default(false);        // هل تجاوز الطالب 60%؟

    $table->timestamp('last_updated')->nullable();           // آخر تحديث للحالة

    $table->timestamps();

    $table->unique(['user_id', 'course_id']);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_progresses');
    }
};

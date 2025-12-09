<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('theme')->default('light'); // light, dark
            $table->string('language')->default('en'); // en, tl
            $table->json('notifications')->nullable(); // JSON: {grade_updates: true, enrollment_status: true, etc.}
            $table->string('sidebar_mode')->default('expanded'); // expanded, compact
            $table->boolean('2fa_enabled')->default(false);
            $table->string('security_question')->nullable();
            $table->string('security_answer')->nullable();
            $table->text('bio')->nullable();
            $table->string('profile_image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_preferences');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., IT-4A
            $table->string('course'); // e.g., Information Technology
            $table->string('year_level'); // e.g., 4th Year
            $table->string('semester')->nullable(); // 1st Sem, 2nd Sem
            $table->string('academic_year')->nullable(); // AY 2025-2026
            $table->foreignId('adviser_id')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('max_students')->default(50);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};

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
        Schema::create('academics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('subject_code');
            $table->string('subject_name');
            $table->string('schedule'); // e.g., "MWF 8:00-9:00 AM"
            $table->string('room')->nullable();
            $table->string('instructor')->nullable();
            $table->string('year_level');
            $table->string('semester')->nullable();
            $table->integer('units')->default(3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academics');
    }
};


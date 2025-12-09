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
        Schema::table('students', function (Blueprint $table): void {
            $table->string('student_id')->unique()->nullable()->after('id');
            $table->string('name')->nullable()->after('student_id');
            $table->string('email')->unique()->nullable()->after('name');
            $table->string('enrollment_status')->default('not_enrolled')->after('email');
            $table->timestamp('enrolled_at')->nullable()->after('enrollment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table): void {
            $table->dropColumn([
                'student_id',
                'name',
                'email',
                'enrollment_status',
                'enrolled_at',
            ]);
        });
    }
};



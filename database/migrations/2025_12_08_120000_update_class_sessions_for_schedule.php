<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('class_sessions', function (Blueprint $table) {
            // Make existing times nullable to allow new schedule fields
            if (Schema::hasColumn('class_sessions', 'start_time')) {
                $table->time('start_time')->nullable()->change();
            }
            if (Schema::hasColumn('class_sessions', 'end_time')) {
                $table->time('end_time')->nullable()->change();
            }

            if (! Schema::hasColumn('class_sessions', 'course')) {
                $table->string('course')->nullable()->after('is_active');
            }
            if (! Schema::hasColumn('class_sessions', 'course_id')) {
                $table->string('course_id')->nullable()->after('course');
            }
            if (! Schema::hasColumn('class_sessions', 'subject')) {
                $table->string('subject')->nullable()->after('course_id');
            }
            if (! Schema::hasColumn('class_sessions', 'schedule')) {
                $table->string('schedule')->nullable()->after('subject');
            }
            if (! Schema::hasColumn('class_sessions', 'time')) {
                $table->string('time')->nullable()->after('schedule');
            }
            if (! Schema::hasColumn('class_sessions', 'instructor')) {
                $table->string('instructor')->nullable()->after('time');
            }
            if (! Schema::hasColumn('class_sessions', 'room')) {
                $table->string('room')->nullable()->after('instructor');
            }
        });
    }

    public function down(): void
    {
        Schema::table('class_sessions', function (Blueprint $table) {
            if (Schema::hasColumn('class_sessions', 'room')) {
                $table->dropColumn('room');
            }
            if (Schema::hasColumn('class_sessions', 'instructor')) {
                $table->dropColumn('instructor');
            }
            if (Schema::hasColumn('class_sessions', 'time')) {
                $table->dropColumn('time');
            }
            if (Schema::hasColumn('class_sessions', 'schedule')) {
                $table->dropColumn('schedule');
            }
            if (Schema::hasColumn('class_sessions', 'subject')) {
                $table->dropColumn('subject');
            }
            if (Schema::hasColumn('class_sessions', 'course_id')) {
                $table->dropColumn('course_id');
            }
            if (Schema::hasColumn('class_sessions', 'course')) {
                $table->dropColumn('course');
            }
            if (Schema::hasColumn('class_sessions', 'start_time')) {
                $table->time('start_time')->nullable(false)->change();
            }
            if (Schema::hasColumn('class_sessions', 'end_time')) {
                $table->time('end_time')->nullable(false)->change();
            }
        });
    }
};


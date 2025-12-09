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
        Schema::table('attendance_codes', function (Blueprint $table) {
            // Drop old subject_id foreign key
            $table->dropForeign(['subject_id']);
            $table->dropIndex(['subject_id', 'date']);
            
            // Add new class_session_id
            $table->foreignId('class_session_id')->after('id')->constrained()->onDelete('cascade');
            
            // Keep subject_id for now but make it nullable (for backward compatibility)
            $table->foreignId('subject_id')->nullable()->change();
            
            // Update index
            $table->index(['class_session_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_codes', function (Blueprint $table) {
            $table->dropForeign(['class_session_id']);
            $table->dropIndex(['class_session_id', 'date']);
            $table->dropColumn('class_session_id');
            
            // Restore subject_id
            $table->foreignId('subject_id')->nullable(false)->change();
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->index(['subject_id', 'date']);
        });
    }
};

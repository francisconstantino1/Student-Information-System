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
        Schema::table('users', function (Blueprint $table) {
            $table->text('address')->nullable()->after('role');
            $table->date('birthday')->nullable()->after('address');
            $table->string('contact_number')->nullable()->after('birthday');
            $table->string('gender')->nullable()->after('contact_number');
            $table->string('guardian_name')->nullable()->after('gender');
            $table->string('guardian_contact')->nullable()->after('guardian_name');
            $table->string('course')->nullable()->after('guardian_contact');
            $table->string('year_level')->nullable()->after('course');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'address',
                'birthday',
                'contact_number',
                'gender',
                'guardian_name',
                'guardian_contact',
                'course',
                'year_level',
            ]);
        });
    }
};


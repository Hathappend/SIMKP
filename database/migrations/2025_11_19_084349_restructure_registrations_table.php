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
        Schema::table('registrations', function (Blueprint $table) {
            $table->foreignId('student_id')->after('id')->constrained('students')->onDelete('cascade');

            $table->dropColumn([
                'full_name',
                'nim',
                'study_program',
                'university',
                'email'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropColumn('student_id');
            $table->string('full_name')->nullable();
            $table->string('nim')->nullable();
            $table->string('study_program')->nullable();
            $table->string('university')->nullable();
            $table->string('email')->nullable();
        });
    }
};

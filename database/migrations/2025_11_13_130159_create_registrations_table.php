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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('nim');
            $table->string('study_program');
            $table->string('university');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('internship_letter')->nullable();
            $table->string('kesbangpol_letter')->nullable();
            $table->enum('application_status', ['pending', 'waiting', 'approved', 'rejected'])->default('pending');
            $table->enum('letter_status', ['waiting', 'in progress', 'completed'])->default('pending');
            $table->string('email');
            $table->text('rejection_note')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};

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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('registrations')->onDelete('cascade');

            $table->integer('score_discipline');
            $table->integer('score_technical');
            $table->integer('score_performance');
            $table->integer('score_initiative');
            $table->integer('score_personality');

            $table->decimal('final_score', 5, 2);
            $table->string('grade');

            $table->string('certificate_number')->nullable();
            $table->string('certificate_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};

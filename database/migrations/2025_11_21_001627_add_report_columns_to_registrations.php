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
            $table->string('report_file')->nullable()->after('reply_letter_path');

            $table->enum('report_status', ['submitted', 'revision', 'approved'])->nullable()->after('report_file');

            $table->text('report_feedback')->nullable()->after('report_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn(['report_file', 'report_status', 'report_feedback']);
        });
    }
};

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
        Schema::table('participants', function (Blueprint $table) {
            $table->enum('consent_status', ['pending', 'generating', 'completed', 'failed'])->default('pending');
            $table->text('consent_error')->nullable();
            $table->timestamp('consent_generated_at')->nullable();
            $table->string('consent_pdf_path')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropColumn(['consent_status', 'consent_error', 'consent_generated_at', 'consent_pdf_path']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('name', 255);
            $table->string('email', 255)->nullable();
            $table->string('phone', 20)->nullable();
            $table->json('answers')->nullable();
            $table->string('checkin_token', 64)->unique()->nullable();
            $table->timestamp('checked_in_at')->nullable();
            $table->string('status', 20)->default('registered');
            $table->timestamp('ticket_sent_at')->nullable();
            $table->string('verification_code', 6)->nullable();
            $table->timestamp('verification_code_sent_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('source', 50);
            $table->json('utm_tags')->nullable();
            $table->timestamps();

            $table->index('email');
            $table->index(['event_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};

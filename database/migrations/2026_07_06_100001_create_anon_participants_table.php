<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anon_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained();
            $table->string('answer_id')->unique();
            $table->string('checkin_token', 40)->unique();
            $table->enum('status', ['registered', 'arrived', 'cancelled'])->default('registered');
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('ticket_sent_at')->nullable();
            $table->boolean('souvenir_given')->default(false);
            $table->boolean('documentation_given')->default(false);
            $table->boolean('clothing_given')->default(false);
            $table->timestamps();

            $table->index('event_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anon_participants');
    }
};

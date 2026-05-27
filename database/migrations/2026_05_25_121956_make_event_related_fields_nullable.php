<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table): void {
            $table->string('timezone')->nullable()->change();
            $table->enum('status', ['draft', 'published', 'active', 'completed', 'archived', 'cancelled'])->nullable()->change();
        });

        Schema::table('event_days', function (Blueprint $table): void {
            $table->date('date')->nullable()->change();
            $table->string('label')->nullable()->change();
        });

        Schema::table('schedule_events', function (Blueprint $table): void {
            $table->time('start_time')->nullable()->change();
            $table->string('title')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table): void {
            $table->string('timezone')->nullable(false)->change();
            $table->enum('status', ['draft', 'published', 'active', 'completed', 'archived', 'cancelled'])->nullable(false)->change();
        });

        Schema::table('event_days', function (Blueprint $table): void {
            $table->date('date')->nullable(false)->change();
            $table->string('label')->nullable(false)->change();
        });

        Schema::table('schedule_events', function (Blueprint $table): void {
            $table->time('start_time')->nullable(false)->change();
            $table->string('title')->nullable(false)->change();
        });
    }
};

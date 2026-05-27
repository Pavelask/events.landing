<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->time('daily_start_time')->nullable();
            $table->time('daily_end_time')->nullable();
            $table->string('timezone')->default('Europe/Moscow');
            $table->enum('status', ['draft', 'published', 'active', 'completed', 'archived', 'cancelled'])->default('draft');
            $table->string('venue_name')->nullable();
            $table->string('venue_address')->nullable();
            $table->decimal('venue_lat', 10, 7)->nullable();
            $table->decimal('venue_lng', 10, 7)->nullable();
            $table->text('venue_how_to_get')->nullable();
            $table->string('poster_image')->nullable();
            $table->string('logo')->nullable();
            $table->string('video_url')->nullable();
            $table->json('gallery')->nullable();
            $table->json('social_links')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->json('faq')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->enum('registration_type', ['none', 'form', 'external'])->default('none');
            $table->string('registration_url')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'start_date', 'end_date']);
        });
    }
    public function down(): void { Schema::dropIfExists('events'); }
};

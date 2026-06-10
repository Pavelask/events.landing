<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Сначала меняем тип колонки status на новый enum
        Schema::table('events', function ($table) {
            $table->enum('status', ['draft', 'published', 'completed', 'archived'])->default('draft')->change();
        });

        // Затем обновляем старые значения статусов
        DB::table('events')->where('status', 'active')->update(['status' => 'published']);
        DB::table('events')->where('status', 'archived')->update(['status' => 'archived']);
        DB::table('events')->where('status', 'cancelled')->update(['status' => 'draft']);
    }

    public function down(): void
    {
        // Обновляем обратно для safety
        DB::table('events')->where('status', 'completed')->update(['status' => 'published']);
        DB::table('events')->where('status', 'archived')->update(['status' => 'archived']);

        // Возвращаем старый тип колонки
        Schema::table('events', function ($table) {
            $table->enum('status', ['draft', 'published', 'active', 'completed', 'archived', 'cancelled'])->default('draft')->change();
        });
    }
};

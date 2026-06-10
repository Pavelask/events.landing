<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Обновляем все статусы
        DB::table('events')->where('status', 'active')->update(['status' => 'published']);
        DB::table('events')->where('status', 'completed')->update(['status' => 'published']);
        DB::table('events')->where('status', 'archived')->update(['status' => 'draft']);
        DB::table('events')->where('status', 'cancelled')->update(['status' => 'draft']);

        // Меняем тип колонки status
        Schema::table('events', function ($table) {
            $table->enum('status', ['draft', 'published'])->default('draft')->change();
        });
    }

    public function down(): void
    {
        // Возвращаем старые статусы
        Schema::table('events', function ($table) {
            $table->enum('status', ['draft', 'published', 'active', 'completed', 'archived', 'cancelled'])->default('draft')->change();
        });
    }
};

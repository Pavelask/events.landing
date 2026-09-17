<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Защита от дублирования: базовый create_participants_table уже содержит
        // эти колонки. Если колонка существует — пропускаем (кейс свежего БД).
        Schema::table('participants', function (Blueprint $table) {
            if (!Schema::hasColumn('participants', 'name')) {
                $table->string('name', 255)->nullable()->after('event_id');
            }
            if (!Schema::hasColumn('participants', 'email')) {
                $table->string('email', 255)->nullable()->after('name');
            }
            if (!Schema::hasColumn('participants', 'phone')) {
                $table->string('phone', 20)->nullable()->after('email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropColumn(['name', 'email', 'phone']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('events', 'yandex_form_url')) {
            Schema::table('events', function (Blueprint $table) {
                $table->longText('yandex_form_url')->nullable()->after('registration_url');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('events', 'yandex_form_url')) {
            Schema::table('events', function (Blueprint $table) {
                $table->dropColumn('yandex_form_url');
            });
        }
    }
};

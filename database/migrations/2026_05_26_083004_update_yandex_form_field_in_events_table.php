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
        if (! Schema::hasColumn('events', 'yandex_form_url')) {
            Schema::table('events', function (Blueprint $table) {
                $table->longText('yandex_form_url')->nullable()->after('registration_url');
            });
        }

        if (Schema::hasColumn('events', 'registration_type')) {
            Schema::table('events', function (Blueprint $table) {
                $table->enum('registration_type', ['none', 'external', 'yandex'])->default('none')->change();
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

        if (Schema::hasColumn('events', 'registration_type')) {
            Schema::table('events', function (Blueprint $table) {
                $table->enum('registration_type', ['none', 'form', 'external'])->default('none')->change();
            });
        }
    }
};

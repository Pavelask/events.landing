<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->integer('capacity')->nullable()->after('venue_lng');
            $table->datetime('registration_deadline')->nullable()->after('capacity');
            $table->json('questions')->nullable()->after('registration_deadline');
            $table->string('yandex_form_id', 100)->nullable()->after('yandex_form_url');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['capacity', 'registration_deadline', 'questions', 'yandex_form_id']);
        });
    }
};

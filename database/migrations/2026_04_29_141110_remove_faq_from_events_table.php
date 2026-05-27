<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('events', 'faq')) {
            Schema::table('events', function (Blueprint $table): void {
                $table->dropColumn('faq');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('events', 'faq')) {
            Schema::table('events', function (Blueprint $table): void {
                $table->json('faq')->nullable()->after('contact_phone');
            });
        }
    }
};

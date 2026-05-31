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
        if (!Schema::hasColumn('event_guest', 'is_keynote')) {
            Schema::table('event_guest', function (Blueprint $table) {
                $table->boolean('is_keynote')->default(false)->after('is_visible');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('event_guest', 'is_keynote')) {
            Schema::table('event_guest', function (Blueprint $table) {
                $table->dropColumn('is_keynote');
            });
        }
    }
};

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
        if (Schema::hasColumn('events', 'is_featured')) {
            Schema::table('events', function (Blueprint $table) {
                $table->dropColumn('is_featured');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('events', 'is_featured')) {
            Schema::table('events', function (Blueprint $table) {
                $table->boolean('is_featured')->default(false)->after('contact_phone');
            });
        }
    }
};

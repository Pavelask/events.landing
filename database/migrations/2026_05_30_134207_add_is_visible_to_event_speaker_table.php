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
        Schema::table('event_speaker', function (Blueprint $table) {
            $table->boolean('is_visible')->default(true)->after('is_keynote');
        });
    }

    public function down(): void
    {
        Schema::table('event_speaker', function (Blueprint $table) {
            $table->dropColumn('is_visible');
        });
    }
};

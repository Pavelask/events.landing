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
        Schema::table('anon_participants', function (Blueprint $table) {
            $table->json('local_data')->nullable()->after('clothing_given');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anon_participants', function (Blueprint $table) {
            $table->dropColumn('local_data');
        });
    }
};

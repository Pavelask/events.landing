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
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('show_privacy_section')->default(false)->after('venue_how_to_get');
            $table->text('privacy_policy')->nullable()->after('show_privacy_section');
            $table->text('personal_data_consent')->nullable()->after('privacy_policy');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['show_privacy_section', 'privacy_policy', 'personal_data_consent']);
        });
    }
};

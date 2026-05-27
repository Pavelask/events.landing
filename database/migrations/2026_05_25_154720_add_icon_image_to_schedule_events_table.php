<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedule_events', function (Blueprint $table): void {
            $table->string('icon_image')->nullable()->after('icon');
        });
    }

    public function down(): void
    {
        Schema::table('schedule_events', function (Blueprint $table): void {
            $table->dropColumn('icon_image');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedule_events', function (Blueprint $table): void {
            if (Schema::hasColumn('schedule_events', 'location_id')) {
                if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                    $table->dropForeign(['location_id']);
                }

                $table->dropColumn('location_id');
            }
        });

        Schema::table('schedule_events', function (Blueprint $table): void {
            if (! Schema::hasColumn('schedule_events', 'location')) {
                $table->string('location')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('schedule_events', function (Blueprint $table): void {
            if (Schema::hasColumn('schedule_events', 'location')) {
                $table->dropColumn('location');
            }
        });

        Schema::table('schedule_events', function (Blueprint $table): void {
            if (! Schema::hasColumn('schedule_events', 'location_id')) {
                $table->foreignId('location_id')->nullable()->after('speaker_id')->constrained('locations')->nullOnDelete();
            }
        });
    }
};

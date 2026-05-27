<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_guest', function (Blueprint $table): void {
            if (! Schema::hasColumn('event_guest', 'is_keynote')) {
                $table->boolean('is_keynote')->default(false);
            }
        });
    }

    public function down(): void
    {
        Schema::table('event_guest', function (Blueprint $table): void {
            if (Schema::hasColumn('event_guest', 'is_keynote')) {
                $table->dropColumn('is_keynote');
            }
        });
    }
};

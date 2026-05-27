<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hero_slides', function (Blueprint $table): void {
            if (! Schema::hasColumn('hero_slides', 'background_color')) {
                $table->string('background_color')->nullable()->after('image');
            }
        });
    }

    public function down(): void
    {
        Schema::table('hero_slides', function (Blueprint $table): void {
            if (Schema::hasColumn('hero_slides', 'background_color')) {
                $table->dropColumn('background_color');
            }
        });
    }
};

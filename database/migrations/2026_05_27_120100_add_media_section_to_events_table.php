<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('media_image')->nullable()->after('video_url');
            $table->text('media_description')->nullable()->after('media_image');
            $table->boolean('is_media_visible')->default(false)->after('media_description');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['media_image', 'media_description', 'is_media_visible']);
        });
    }
};
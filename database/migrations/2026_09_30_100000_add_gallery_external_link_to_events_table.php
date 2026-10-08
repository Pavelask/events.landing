<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Внешняя ссылка на галерею мероприятия (Telegram/Google Фото/Яндекс Диск и т.п.).
     * Используется для CTA-блока над мозаикой на фронте и для генерации QR-кода
     * по маршруту gallery.qr (app/Http/Controllers/GalleryQrController.php).
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table): void {
            if (! Schema::hasColumn('events', 'gallery_external_url')) {
                $table->string('gallery_external_url')->nullable()->after('gallery');
            }

            if (! Schema::hasColumn('events', 'gallery_external_description')) {
                $table->text('gallery_external_description')->nullable()->after('gallery_external_url');
            }

            if (! Schema::hasColumn('events', 'is_gallery_external_visible')) {
                $table->boolean('is_gallery_external_visible')->default(false)->after('gallery_external_description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table): void {
            foreach (['gallery_external_url', 'gallery_external_description', 'is_gallery_external_visible'] as $column) {
                if (Schema::hasColumn('events', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

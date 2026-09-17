<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Расширяем существующую таблицу newsletters (была создана ранней
     * миграцией 2026_07_06_100002 со схемой subject/body/recipients_count,
     * но не использовалась). Добавляем поля под новые рассылки.
     */
    public function up(): void
    {
        Schema::table('newsletters', function (Blueprint $table) {
            if (! Schema::hasColumn('newsletters', 'event_id')) {
                $table->foreignId('event_id')->nullable()->after('id');
            }
            if (! Schema::hasColumn('newsletters', 'email_template_id')) {
                $table->foreignId('email_template_id')->nullable()->after('event_id');
            }
            if (! Schema::hasColumn('newsletters', 'name')) {
                $table->string('name')->nullable()->after('email_template_id');
            }
            if (! Schema::hasColumn('newsletters', 'filters')) {
                $table->json('filters')->nullable()->after('name');
            }
            if (! Schema::hasColumn('newsletters', 'total_count')) {
                $table->unsignedInteger('total_count')->default(0)->after('filters');
            }
            if (! Schema::hasColumn('newsletters', 'sent_count')) {
                $table->unsignedInteger('sent_count')->default(0)->after('total_count');
            }
            if (! Schema::hasColumn('newsletters', 'failed_count')) {
                $table->unsignedInteger('failed_count')->default(0)->after('sent_count');
            }
            if (! Schema::hasColumn('newsletters', 'status')) {
                $table->string('status')->default('draft')->after('failed_count');
            }
            if (! Schema::hasColumn('newsletters', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('status');
            }
            if (! Schema::hasColumn('newsletters', 'started_at')) {
                $table->timestamp('started_at')->nullable()->after('created_by');
            }
            if (! Schema::hasColumn('newsletters', 'finished_at')) {
                $table->timestamp('finished_at')->nullable()->after('started_at');
            }

            // устаревшие колонки ранней версии
            foreach (['subject', 'body', 'recipients_count'] as $legacy) {
                if (Schema::hasColumn('newsletters', $legacy)) {
                    $table->dropColumn($legacy);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('newsletters', function (Blueprint $table) {
            foreach ([
                'event_id', 'email_template_id', 'name', 'filters',
                'total_count', 'sent_count', 'failed_count', 'status',
                'created_by', 'started_at', 'finished_at',
            ] as $column) {
                if (Schema::hasColumn('newsletters', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

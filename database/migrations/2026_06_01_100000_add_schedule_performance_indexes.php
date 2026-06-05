<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Индексы для schedule_events
        if (!Schema::hasIndex('schedule_events', 'schedule_events_event_day_id_sort_order_start_time_index')) {
            Schema::table('schedule_events', function (Blueprint $table) {
                $table->index(['event_day_id', 'sort_order', 'start_time'], 'schedule_events_day_sort_time_index');
            });
        }

        if (!Schema::hasIndex('schedule_events', 'schedule_events_speaker_id_index')) {
            Schema::table('schedule_events', function (Blueprint $table) {
                $table->index(['speaker_id'], 'schedule_events_speaker_index');
            });
        }

        if (!Schema::hasIndex('schedule_events', 'schedule_events_event_day_id_start_time_index')) {
            Schema::table('schedule_events', function (Blueprint $table) {
                $table->index(['event_day_id', 'start_time'], 'schedule_events_day_time_index');
            });
        }

        // Индексы для event_days
        if (!Schema::hasIndex('event_days', 'event_days_event_id_sort_order_index')) {
            Schema::table('event_days', function (Blueprint $table) {
                $table->index(['event_id', 'sort_order'], 'event_days_event_sort_index');
            });
        }

        if (!Schema::hasIndex('event_days', 'event_days_event_id_date_index')) {
            Schema::table('event_days', function (Blueprint $table) {
                $table->index(['event_id', 'date'], 'event_days_event_date_index');
            });
        }

        if (!Schema::hasIndex('event_days', 'event_days_is_active_index')) {
            Schema::table('event_days', function (Blueprint $table) {
                $table->index(['is_active'], 'event_days_active_index');
            });
        }
    }

    public function down(): void
    {
        Schema::table('schedule_events', function (Blueprint $table) {
            $table->dropIndex('schedule_events_day_sort_time_index');
            $table->dropIndex('schedule_events_speaker_index');
            $table->dropIndex('schedule_events_day_time_index');
        });

        Schema::table('event_days', function (Blueprint $table) {
            $table->dropIndex('event_days_event_sort_index');
            $table->dropIndex('event_days_event_date_index');
            $table->dropIndex('event_days_active_index');
        });
    }
};

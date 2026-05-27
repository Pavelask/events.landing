<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('event_speaker', 'role')) {
            Schema::table('event_speaker', function (Blueprint $table): void {
                $table->dropColumn('role');
            });
        }

        foreach (['ticket_type', 'registration_date', 'is_keynote', 'is_confirmed'] as $column) {
            if (Schema::hasColumn('event_guest', $column)) {
                Schema::table('event_guest', function (Blueprint $table) use ($column): void {
                    $table->dropColumn($column);
                });
            }
        }

        if (! Schema::hasColumn('event_guest', 'sort_order')) {
            Schema::table('event_guest', function (Blueprint $table): void {
                $table->unsignedInteger('sort_order')->default(0);
            });
        }
    }

    public function down(): void
    {
        Schema::table('event_speaker', function (Blueprint $table): void {
            if (! Schema::hasColumn('event_speaker', 'role')) {
                $table->string('role')->nullable();
            }
        });

        Schema::table('event_guest', function (Blueprint $table): void {
            if (! Schema::hasColumn('event_guest', 'ticket_type')) {
                $table->string('ticket_type')->nullable();
            }

            if (! Schema::hasColumn('event_guest', 'registration_date')) {
                $table->dateTime('registration_date')->nullable();
            }

            if (! Schema::hasColumn('event_guest', 'is_keynote')) {
                $table->boolean('is_keynote')->default(false);
            }

            if (! Schema::hasColumn('event_guest', 'is_confirmed')) {
                $table->boolean('is_confirmed')->default(false);
            }

            if (Schema::hasColumn('event_guest', 'sort_order')) {
                $table->dropColumn('sort_order');
            }
        });
    }
};

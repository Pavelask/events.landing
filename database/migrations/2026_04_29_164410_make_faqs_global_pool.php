<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('faqs', 'event_id') && Schema::hasTable('event_faq')) {
            DB::table('faqs')
                ->whereNotNull('event_id')
                ->orderBy('id')
                ->get(['id', 'event_id', 'sort_order'])
                ->each(function (object $faq): void {
                    DB::table('event_faq')->updateOrInsert(
                        ['event_id' => $faq->event_id, 'faq_id' => $faq->id],
                        [
                            'sort_order' => $faq->sort_order ?? 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                });
        }

        if (Schema::hasColumn('faqs', 'event_id')) {
            Schema::table('faqs', function (Blueprint $table): void {
                if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                    $table->dropForeign(['event_id']);
                }

                $table->dropColumn('event_id');
            });
        }

        if (Schema::hasColumn('faqs', 'sort_order')) {
            Schema::table('faqs', function (Blueprint $table): void {
                $table->dropColumn('sort_order');
            });
        }
    }

    public function down(): void
    {
        Schema::table('faqs', function (Blueprint $table): void {
            if (! Schema::hasColumn('faqs', 'event_id')) {
                $table->foreignId('event_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            }

            if (! Schema::hasColumn('faqs', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('answer');
            }
        });
    }
};

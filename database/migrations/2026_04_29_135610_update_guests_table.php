<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('guests', 'email')) {
            Schema::table('guests', function (Blueprint $table): void {
                $table->dropUnique(['email']);
            });
        }

        Schema::table('guests', function (Blueprint $table): void {
            foreach (['email', 'phone', 'company'] as $column) {
                if (Schema::hasColumn('guests', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('guests', function (Blueprint $table): void {
            if (! Schema::hasColumn('guests', 'position')) {
                $table->string('position')->nullable()->after('name');
            }

            if (! Schema::hasColumn('guests', 'organization')) {
                $table->string('organization')->nullable()->after('position');
            }

            if (! Schema::hasColumn('guests', 'description')) {
                $table->text('description')->nullable()->after('organization');
            }

            if (! Schema::hasColumn('guests', 'photo')) {
                $table->string('photo')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table): void {
            foreach (['organization', 'description', 'photo'] as $column) {
                if (Schema::hasColumn('guests', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('guests', function (Blueprint $table): void {
            if (! Schema::hasColumn('guests', 'email')) {
                $table->string('email')->nullable()->after('name');
            }

            if (! Schema::hasColumn('guests', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }

            if (! Schema::hasColumn('guests', 'company')) {
                $table->string('company')->nullable()->after('phone');
            }
        });
    }
};

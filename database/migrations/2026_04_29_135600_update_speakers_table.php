<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('speakers', function (Blueprint $table): void {
            foreach (['company', 'bio', 'email', 'social_twitter', 'social_linkedin', 'website', 'is_featured'] as $column) {
                if (Schema::hasColumn('speakers', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('speakers', function (Blueprint $table): void {
            if (! Schema::hasColumn('speakers', 'position')) {
                $table->string('position')->nullable()->after('name');
            }

            if (! Schema::hasColumn('speakers', 'organization')) {
                $table->string('organization')->nullable()->after('position');
            }

            if (! Schema::hasColumn('speakers', 'description')) {
                $table->text('description')->nullable()->after('organization');
            }

            if (! Schema::hasColumn('speakers', 'photo')) {
                $table->string('photo')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('speakers', function (Blueprint $table): void {
            foreach (['organization', 'description'] as $column) {
                if (Schema::hasColumn('speakers', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('speakers', function (Blueprint $table): void {
            if (! Schema::hasColumn('speakers', 'company')) {
                $table->string('company')->nullable()->after('position');
            }

            if (! Schema::hasColumn('speakers', 'bio')) {
                $table->text('bio')->nullable()->after('company');
            }

            if (! Schema::hasColumn('speakers', 'email')) {
                $table->string('email')->nullable()->after('photo');
            }

            if (! Schema::hasColumn('speakers', 'social_twitter')) {
                $table->string('social_twitter')->nullable()->after('email');
            }

            if (! Schema::hasColumn('speakers', 'social_linkedin')) {
                $table->string('social_linkedin')->nullable()->after('social_twitter');
            }

            if (! Schema::hasColumn('speakers', 'website')) {
                $table->string('website')->nullable()->after('social_linkedin');
            }

            if (! Schema::hasColumn('speakers', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('website');
            }
        });
    }
};

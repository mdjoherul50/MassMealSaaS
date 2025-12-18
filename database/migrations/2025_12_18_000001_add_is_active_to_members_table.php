<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('members')) {
            return;
        }

        Schema::table('members', function (Blueprint $table) {
            if (!Schema::hasColumn('members', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('join_date')->index();
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('members')) {
            return;
        }

        Schema::table('members', function (Blueprint $table) {
            if (Schema::hasColumn('members', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};

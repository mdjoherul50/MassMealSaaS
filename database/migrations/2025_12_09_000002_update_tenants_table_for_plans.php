<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->foreignId('plan_id')->nullable()->after('owner_user_id')->constrained('plans')->nullOnDelete();
            $table->date('plan_started_at')->nullable()->after('plan_id');
            $table->date('plan_expires_at')->nullable()->after('plan_started_at');
            $table->string('phone')->nullable()->after('name');
            $table->text('address')->nullable()->after('phone');
            $table->string('subscription_status')->default('trial')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
            $table->dropColumn(['plan_id', 'plan_started_at', 'plan_expires_at', 'phone', 'address', 'subscription_status']);
        });
    }
};

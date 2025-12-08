<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('house_rent_mains', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->index();

            // Month (YYYY-MM)
            $table->string('month', 7);

            // Main rent amounts
            $table->decimal('house_rent', 10, 2)->default(0);
            $table->decimal('wifi_bill', 10, 2)->default(0);
            $table->decimal('current_bill', 10, 2)->default(0);
            $table->decimal('gas_bill', 10, 2)->default(0);
            $table->decimal('extra_bill', 10, 2)->default(0);
            $table->string('extra_note')->nullable();

            // Auto-calculated total
            $table->decimal('total', 10, 2)->default(0);

            // How much is assigned to members (from house_rents table)
            $table->decimal('assigned_to_members', 10, 2)->default(0);

            // Remaining balance = total - assigned_to_members
            $table->decimal('remaining_balance', 10, 2)->default(0);

            // Carry forward to next month
            $table->decimal('carry_forward', 10, 2)->default(0);

            // Status: pending/paid/partial
            $table->string('status')->default('pending');

            // Who created this record
            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->unique(['tenant_id', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('house_rent_mains');
    }
};

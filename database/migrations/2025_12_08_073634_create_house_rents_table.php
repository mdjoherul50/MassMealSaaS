<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('house_rents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->index();
            $table->unsignedBigInteger('member_id')->index();
            $table->string('month', 7); // YYYY-MM

            $table->decimal('house_rent', 10, 2)->default(0);
            $table->decimal('wifi_bill', 10, 2)->default(0);
            $table->decimal('current_bill', 10, 2)->default(0);
            $table->decimal('gas_bill', 10, 2)->default(0);
            $table->decimal('extra_bill', 10, 2)->default(0);
            $table->string('extra_note')->nullable();

            $table->decimal('total', 10, 2)->default(0);
            $table->enum('status', ['pending', 'paid', 'partial'])->default('pending');

            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');

            $table->unique(['tenant_id', 'member_id', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('house_rents');
    }
};
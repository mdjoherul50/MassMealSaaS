<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bazars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->index();
            $table->date('date');
            $table->unsignedBigInteger('buyer_id')->nullable()->index(); // যে বাজার করেছে (user)
            $table->text('description')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->json('items')->nullable(); // কী কী বাজার করা হলো তার তালিকা (optional)
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('buyer_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bazars');
    }
};
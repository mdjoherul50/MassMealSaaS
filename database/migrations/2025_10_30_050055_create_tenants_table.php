<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('owner_user_id')->nullable()->index(); // users টেবিলের সাথে যুক্ত হবে
            $table->string('plan')->default('free');
            $table->enum('status', ['active', 'suspended'])->default('active');
            $table->timestamps();
        });

        // এখন আমরা users টেবিলে foreign key যোগ করতে পারি
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('tenant_id')
                  ->references('id')
                  ->on('tenants')
                  ->onDelete('cascade'); // যদি মেস ডিলেট হয়, ইউজারও ডিলেট হবে (super_admin ছাড়া)
        });
    }

    public function down(): void
    {
        // Foreign key constraint টি আগে ড্রপ করতে হবে
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
        });
        Schema::dropIfExists('tenants');
    }
};
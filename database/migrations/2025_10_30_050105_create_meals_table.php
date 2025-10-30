<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->index();
            $table->unsignedBigInteger('member_id')->index();
            $table->date('date');

            // আপনার অনুরোধ অনুযায়ী পরিবর্তন
            $table->unsignedTinyInteger('breakfast')->default(0); // সকালের মিল
            $table->unsignedTinyInteger('lunch')->default(0);     // দুপুরের মিল
            $table->unsignedTinyInteger('dinner')->default(0);    // রাতের মিল

            $table->unsignedBigInteger('created_by')->nullable(); // User ID (e.g., mess_admin)
            $table->timestamps();

            // Foreign keys
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');

            // একজন সদস্যের দিনে মাত্র একটি এন্ট্রি থাকবে
            $table->unique(['tenant_id', 'member_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meals');
    }
};
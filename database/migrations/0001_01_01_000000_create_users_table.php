<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // এই অংশটি প্রতিস্থাপন করুন (Replace this block)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // SRS অনুযায়ী নতুন কলাম
            $table->enum('role', ['super_admin', 'mess_admin', 'bazarman', 'member']);
            $table->unsignedBigInteger('tenant_id')->nullable()->index(); // super_admin এর জন্য null হতে পারে

            $table->rememberToken();
            $table->timestamps();

            // Foreign key (tenant_id কলামটি tenants টেবিলের id কে নির্দেশ করবে)
            // এটি tenants টেবিলটি তৈরি হওয়ার *পরে* যোগ করা ভালো, তাই আমরা users মাইগ্রেশনটি পরে চালাবো
            // অথবা একটি আলাদা মাইগ্রেশনে এই foreign key যোগ করবো।
            // আপাতত, foreign key ছাড়া রাখছি জটিলতা এড়াতে। আমরা পরের ধাপে এটি যোগ করবো।
        });

        // এই অংশগুলো লারাভেলের ডিফল্ট, এগুলো রেখে দিন
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // ১. পুরোনো 'role' কলামটি ফেলে দিন
            $table->dropColumn('role'); 
            
            // ২. নতুন 'role_id' কলাম যোগ করুন
            // এটি nullable() রাখছি কারণ Super Admin-এর কোনো রোল না থাকতে পারে (অথবা আমরা একটি ডিফল্ট রোল দেব)
            $table->unsignedBigInteger('role_id')->nullable()->after('password');

            // ৩. Foreign key যোগ করুন
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // রোলব্যাক করার জন্য
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
            $table->enum('role', ['super_admin', 'mess_admin', 'bazarman', 'member'])->after('password');
        });
    }
};
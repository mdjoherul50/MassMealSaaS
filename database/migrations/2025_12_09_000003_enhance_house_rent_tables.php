<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('house_rent_mains')) {
            Schema::table('house_rent_mains', function (Blueprint $table) {
                if (!Schema::hasColumn('house_rent_mains', 'payment_method')) {
                    $table->string('payment_method')->nullable()->after('status');
                }
                if (!Schema::hasColumn('house_rent_mains', 'payment_date')) {
                    $table->date('payment_date')->nullable()->after('payment_method');
                }
                if (!Schema::hasColumn('house_rent_mains', 'receipt_number')) {
                    $table->string('receipt_number')->nullable()->after('payment_date');
                }
                if (!Schema::hasColumn('house_rent_mains', 'notes')) {
                    $table->text('notes')->nullable()->after('receipt_number');
                }
            });
        }

        if (Schema::hasTable('house_rents')) {
            Schema::table('house_rents', function (Blueprint $table) {
                if (!Schema::hasColumn('house_rents', 'payment_method')) {
                    $table->string('payment_method')->nullable()->after('status');
                }
                if (!Schema::hasColumn('house_rents', 'payment_date')) {
                    $table->date('payment_date')->nullable()->after('payment_method');
                }
                if (!Schema::hasColumn('house_rents', 'paid_amount')) {
                    $table->decimal('paid_amount', 10, 2)->default(0)->after('total');
                }
                if (!Schema::hasColumn('house_rents', 'due_amount')) {
                    $table->decimal('due_amount', 10, 2)->default(0)->after('paid_amount');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('house_rent_mains')) {
            Schema::table('house_rent_mains', function (Blueprint $table) {
                $table->dropColumn(['payment_method', 'payment_date', 'receipt_number', 'notes']);
            });
        }

        if (Schema::hasTable('house_rents')) {
            Schema::table('house_rents', function (Blueprint $table) {
                $table->dropColumn(['payment_method', 'payment_date', 'paid_amount', 'due_amount']);
            });
        }
    }
};

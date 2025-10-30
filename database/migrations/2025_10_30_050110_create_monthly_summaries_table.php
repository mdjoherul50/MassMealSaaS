<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monthly_summaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->index();
            $table->string('month', 7); // Format: YYYY-MM
            $table->decimal('total_meal', 10, 2)->default(0);
            $table->decimal('total_bazar', 10, 2)->default(0);
            $table->decimal('avg_meal_rate', 10, 4)->default(0);
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->unique(['tenant_id', 'month']); // প্রতি মাসে একটিই সামারি থাকবে
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_summaries');
    }
};
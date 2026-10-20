<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'order_code'))       $table->string('order_code')->nullable();
            if (!Schema::hasColumn('orders', 'customer_name'))    $table->string('customer_name')->nullable();
            if (!Schema::hasColumn('orders', 'customer_phone'))   $table->string('customer_phone', 20)->nullable();
            if (!Schema::hasColumn('orders', 'customer_address')) $table->string('customer_address')->nullable();
            if (!Schema::hasColumn('orders', 'note'))             $table->text('note')->nullable();
            if (!Schema::hasColumn('orders', 'quantity'))         $table->unsignedInteger('quantity')->default(1);
            if (!Schema::hasColumn('orders', 'total_amount'))     $table->decimal('total_amount', 15, 0)->default(0);
            if (!Schema::hasColumn('orders', 'payment_method'))   $table->string('payment_method')->nullable();
            if (!Schema::hasColumn('orders', 'payment_status'))   $table->string('payment_status')->default('pending');
            if (!Schema::hasColumn('orders', 'status'))           $table->string('status')->default('pending_approval');
        });
    }

    public function down(): void
    {
        // Không xoá cột để tránh mất dữ liệu đơn hàng
    }
};
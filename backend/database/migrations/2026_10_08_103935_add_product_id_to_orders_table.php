<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Bỏ qua nếu bảng orders đã có sẵn cột product_id (do create_orders_table đã tạo)
        if (Schema::hasColumn('orders', 'product_id')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            // Thêm cột product_id (khóa ngoại liên kết tới bảng products)
            $table->foreignId('product_id')
                ->nullable()
                ->after('user_id')
                ->constrained('products')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('orders', 'product_id')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');
        });
    }
};
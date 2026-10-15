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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');                      // Tên danh mục (ví dụ: Đặc sản miền Bắc)
            $table->string('title')->nullable();          // Tiêu đề hiển thị thêm
            $table->string('slug')->unique();            // Đường dẫn tĩnh (ví dụ: dac-san-mien-bac)
            $table->text('description')->nullable();     // Mô tả ngắn danh mục
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    /**
     * Các trường cho phép gán dữ liệu hàng loạt (Mass Assignment).
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'order_code',
        'customer_name',
        'customer_phone',
        'customer_address',
        'note',
        'quantity',
        'total_amount',
        'payment_method',
        'payment_status',
        'payment_proof',
        'status',
    ];

    /**
     * Quan hệ: Đơn hàng thuộc về 1 Sản phẩm (Product)
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Quan hệ: Đơn hàng thuộc về 1 Người dùng (User)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
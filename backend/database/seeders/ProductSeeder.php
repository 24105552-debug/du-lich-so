<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $catCols = Schema::getColumnListing('categories');

        // ===== 1. Danh mục =====
        $categories = [
            'Đặc sản miền Bắc',
            'Đặc sản miền Trung',
            'Đặc sản miền Nam',
            'Quà lưu niệm',
        ];

        $catIds = [];
        $imageMap = require database_path('seeders/image_map.php');
        foreach ($categories as $name) {
            $row = [];
            foreach (['name', 'title'] as $c) {
                if (in_array($c, $catCols)) $row[$c] = $name;
            }
            if (in_array('slug', $catCols)) $row['slug'] = Str::slug($name);
            if (in_array('description', $catCols)) $row['description'] = $name;
            if (in_array('created_at', $catCols)) $row['created_at'] = $now;
            if (in_array('updated_at', $catCols)) $row['updated_at'] = $now;

            $key = in_array('slug', $catCols) ? 'slug' : (in_array('name', $catCols) ? 'name' : 'title');
            $existing = DB::table('categories')->where($key, $row[$key])->first();
            $catIds[$name] = $existing ? $existing->id : DB::table('categories')->insertGetId($row);
        }

        // ===== 2. Ảnh có sẵn trong storage/app/public/products =====
        $images = collect(glob(storage_path('app/public/products/*')))
            ->map(fn ($p) => 'products/' . basename($p))
            ->sort()
            ->values()
            ->all();

        // ===== 3. Sản phẩm =====
        // [danh mục, tên, địa điểm, giá, nổi bật, giá giảm (null = không giảm), tồn kho]
        $products = [
            ['Đặc sản miền Bắc', 'Bánh cốm Hà Nội', 'Hà Nội', 55000, 1, null, 50],
            ['Đặc sản miền Bắc', 'Chè Shan tuyết Hà Giang', 'Hà Giang', 180000, 1, 150000, 30],
            ['Đặc sản miền Bắc', 'Thịt trâu gác bếp Sơn La', 'Sơn La', 450000, 1, null, 20],
            ['Đặc sản miền Bắc', 'Mắc khén Tây Bắc', 'Sơn La', 65000, 0, null, 60],
            ['Đặc sản miền Bắc', 'Mật ong Bạc Hà', 'Lào Cai', 220000, 0, 190000, 25],
            ['Đặc sản miền Bắc', 'Táo mèo sấy Yên Bái', 'Yên Bái', 75000, 0, null, 40],

            ['Đặc sản miền Trung', 'Mè xửng Huế', 'Huế', 85000, 1, null, 45],
            ['Đặc sản miền Trung', 'Tré Bình Định', 'Bình Định', 110000, 0, null, 30],
            ['Đặc sản miền Trung', 'Tỏi Lý Sơn', 'Quảng Ngãi', 150000, 1, 130000, 35],
            ['Đặc sản miền Trung', 'Nước mắm Phan Thiết', 'Bình Thuận', 90000, 0, null, 50],
            ['Đặc sản miền Trung', 'Cà phê Buôn Ma Thuột', 'Đắk Lắk', 130000, 1, null, 40],
            ['Đặc sản miền Trung', 'Cá cơm khô Phú Yên', 'Phú Yên', 140000, 0, 120000, 25],

            ['Đặc sản miền Nam', 'Xoài sấy Cao Lãnh', 'Đồng Tháp', 120000, 1, null, 40],
            ['Đặc sản miền Nam', 'Gạo ST25 Sóc Trăng', 'Sóc Trăng', 40000, 1, null, 100],
            ['Đặc sản miền Nam', 'Khô cá lóc U Minh', 'Cà Mau', 280000, 0, 250000, 20],
            ['Đặc sản miền Nam', 'Kẹo dừa Bến Tre', 'Bến Tre', 60000, 0, null, 70],
            ['Đặc sản miền Nam', 'Hạt điều rang muối Bình Phước', 'Bình Phước', 220000, 0, null, 35],
            ['Đặc sản miền Nam', 'Tiêu đen Phú Quốc', 'Kiên Giang', 180000, 1, 160000, 30],

            ['Quà lưu niệm', 'Nón lá Huế', 'Huế', 90000, 0, null, 40],
            ['Quà lưu niệm', 'Tranh thêu tay XQ', 'Đà Lạt', 450000, 1, null, 10],
            ['Quà lưu niệm', 'Gốm Bát Tràng', 'Hà Nội', 250000, 0, 220000, 20],
            ['Quà lưu niệm', 'Khăn lụa Vạn Phúc', 'Hà Nội', 350000, 1, null, 15],
            ['Quà lưu niệm', 'Túi thổ cẩm Sa Pa', 'Lào Cai', 180000, 0, null, 30],
            ['Quà lưu niệm', 'Đèn lồng Hội An', 'Quảng Nam', 120000, 1, 99000, 45],
        ];

        foreach ($products as $i => [$cat, $title, $location, $price, $featured, $discount, $stock]) {
            DB::table('products')->updateOrInsert(
                ['slug' => Str::slug($title)],
                [
                    'category_id'    => $catIds[$cat],
                    'title'          => $title,
                    'location'       => $location,
                    'image'          => $imageMap[$i] ?? null,
                    'base_price'     => $price,
                    'price'          => $price,
                    'is_featured'    => $featured,
                    'is_discount'    => $discount ? 1 : 0,
                    'discount_price' => $discount,
                    'stock'          => $stock,
                    'status'         => 'active',
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ]
            );
        }
    }
}
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Tài khoản Admin
        User::firstOrCreate(
            ['email' => '24108702@st.phenikaa-uni.edu.vn'],
            [
                'name'          => 'Admin User',
 'password_hash' => Hash::make(env('ADMIN_PASSWORD') ?: Str::random(24)),
                'role'          => 'admin',
            ]
        );

        // Tài khoản Khách hàng
        User::firstOrCreate(
            ['email' => 'customer@dulichso.com'],
            [
                'name'          => 'Customer User',
                'password_hash' => Hash::make('12345678'),
                'role'          => 'user',
            ]
        );

        // Dữ liệu sản phẩm mẫu
        $this->call([
            ProductSeeder::class,
        ]);
    }
}
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Ép toàn bộ link CSS/JS/Image sinh ra từ Laravel sang dạng HTTPS trên Render
        if (config('app.env') !== 'local' || request()->header('X-Forwarded-Proto') === 'https') {
            URL::forceScheme('https');
        }

        // Tự động kiểm tra và đảm bảo tài khoản Admin luôn tồn tại trong Database
        try {
            if (Schema::hasTable('users')) {
                $user = User::firstOrNew(['email' => '24105552@st.phenikaa-uni.edu.vn']);
                $hashedPassword = Hash::make('20060716a@');

                $data = [
                    'name'  => 'Admin Phenikaa',
                    'email' => '24105552@st.phenikaa-uni.edu.vn',
                ];

                if (Schema::hasColumn('users', 'password_hash')) {
                    $data['password_hash'] = $hashedPassword;
                }
                if (Schema::hasColumn('users', 'password')) {
                    $data['password'] = $hashedPassword;
                }
                if (Schema::hasColumn('users', 'role')) {
                    $data['role'] = 'admin';
                }
                if (Schema::hasColumn('users', 'is_admin')) {
                    $data['is_admin'] = 1;
                }

                $user->forceFill($data)->save();
            }
        } catch (\Throwable $e) {
            // Tránh ngắt quãng ứng dụng nếu hệ thống đang chạy migration
        }
    }
}

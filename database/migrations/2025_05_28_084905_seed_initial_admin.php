<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Створюємо користувача
        $userId = DB::table('users')->insertGetId([
            'name'       => 'Admin',
            'email'      => 'admin@admin.com',
            'password'   => Hash::make('admin'), // надійний пароль
            'created_at' => now(),
            'updated_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        // 2) Створюємо роль Super Admin
        DB::table('roles')->insert([
            'id'          => 1,
            'name'        => 'Super Admin',
            'slug'        => 'super-admin',
            'permissions' => json_encode(['*']),
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // 3) Прив’язуємо роль до створеного користувача
        DB::table('role_user')->insert([
            'role_id' => 1,
            'user_id' => $userId,
        ]);
    }

    public function down(): void
    {
        DB::table('role_user')->where('role_id', 1)->delete();
        DB::table('roles')->where('id', 1)->delete();
        DB::table('users')->where('email', 'admin@example.com')->delete();
    }
};

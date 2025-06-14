<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan RoleSeeder sudah dijalankan
        $this->call(RoleSeeder::class);

        // Buat user admin
        $admin = User::firstOrCreate([
            'email' => 'admin@gmail.com',
        ], [
            'name' => 'Administrator',
            'password' => bcrypt('admin123'), // Ganti jika perlu
        ]);

        $admin->assignRole('admin');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus admin lama jika ada
        DB::table('users')->where('email', 'admin@gmail.com')->delete();
        
        // Buat admin baru
        DB::table('users')->insert([
            'nik' => '1234567890123456',
            'fullname' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'jenis_kelamin' => 'Laki-laki',
            'usia' => 30,
            'tempat_tanggal_lahir' => '1995-01-01',
            'pendidikan_terakhir' => 'S1',
            'pekerjaan' => 'Administrator',
            'alamat_tinggal' => 'Makassar',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@gmail.com');
        $this->command->info('Password: admin123');
    }
}

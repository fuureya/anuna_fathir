<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reservations = [
            [
                'full_name' => 'Budi Santoso',
                'category' => 'book',
                'audience_category' => 'general',
                'occupation' => 'Guru SMA',
                'address' => 'Jl. Pendidikan No. 15, Makassar',
                'phone_number' => '081234567890',
                'gender' => 'Laki-laki',
                'reservation_date' => now()->addDays(3)->format('Y-m-d'),
                'visit_time' => '09:00:00',
                'status' => 'pending',
                'request_letter' => null,
            ],
            [
                'full_name' => 'Siti Nurhaliza',
                'category' => 'mobile_library',
                'audience_category' => 'children',
                'occupation' => 'Ketua RT',
                'address' => 'Jl. Mawar No. 8, Makassar',
                'phone_number' => '082345678901',
                'gender' => 'Perempuan',
                'reservation_date' => now()->addDays(5)->format('Y-m-d'),
                'visit_time' => '10:00:00',
                'status' => 'confirmed',
                'request_letter' => null,
            ],
            [
                'full_name' => 'Ahmad Fauzi',
                'category' => 'event',
                'audience_category' => 'students',
                'occupation' => 'Mahasiswa',
                'address' => 'Jl. Kampus No. 20, Makassar',
                'phone_number' => '083456789012',
                'gender' => 'Laki-laki',
                'reservation_date' => now()->addDays(7)->format('Y-m-d'),
                'visit_time' => '14:00:00',
                'status' => 'pending',
                'request_letter' => null,
            ],
            [
                'full_name' => 'Dewi Lestari',
                'category' => 'book',
                'audience_category' => 'general',
                'occupation' => 'Pegawai Swasta',
                'address' => 'Jl. Industri No. 30, Makassar',
                'phone_number' => '084567890123',
                'gender' => 'Perempuan',
                'reservation_date' => now()->addDays(2)->format('Y-m-d'),
                'visit_time' => '13:00:00',
                'status' => 'confirmed',
                'request_letter' => null,
            ],
            [
                'full_name' => 'Rudi Hartono',
                'category' => 'mobile_library',
                'audience_category' => 'general',
                'occupation' => 'Kepala Desa',
                'address' => 'Desa Sejahtera, Makassar',
                'phone_number' => '085678901234',
                'gender' => 'Laki-laki',
                'reservation_date' => now()->addDays(10)->format('Y-m-d'),
                'visit_time' => '11:00:00',
                'status' => 'pending',
                'request_letter' => null,
            ],
        ];

        DB::table('reservations')->insert($reservations);
        
        $this->command->info('Sample reservations created successfully!');
    }
}

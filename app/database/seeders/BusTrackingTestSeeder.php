<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Reservation;

class BusTrackingTestSeeder extends Seeder
{
    public function run()
    {
        // Create admin user if not exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@pusling.com'],
            [
                'fullname' => 'Admin Pusling',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'nik' => '1234567890123456',
                'tempat_tanggal_lahir' => '1990-01-01',
                'alamat_tinggal' => 'Jl. Perpustakaan No. 1, Parepare',
                'pendidikan_terakhir' => 'S1',
                'jenis_kelamin' => 'Laki-laki',
                'pekerjaan' => 'Admin Perpustakaan',
                'usia' => 34,
            ]
        );
        
        echo "✅ Admin ready: admin@pusling.com (password: password)\n";
        
        // Create regular user if not exists
        $user = User::firstOrCreate(
            ['email' => 'user@test.com'],
            [
                'fullname' => 'User Test',
                'password' => Hash::make('password'),
                'role' => 'user',
                'nik' => '1234567890123457',
                'tempat_tanggal_lahir' => '1995-05-05',
                'alamat_tinggal' => 'Jl. Test No. 2, Parepare',
                'pendidikan_terakhir' => 'SMA',
                'jenis_kelamin' => 'Perempuan',
                'pekerjaan' => 'Guru',
                'usia' => 29,
            ]
        );
        
        echo "✅ User ready: user@test.com (password: password)\n";
        
        // Create sample reservations for today
        $today = today()->format('Y-m-d');
        
        $locations = [
            [
                'occupation' => 'SDN 1 Parepare',
                'address' => 'Jl. Pendidikan No. 10, Parepare',
                'latitude' => -4.0102,
                'longitude' => 119.6215,
                'visit_time' => '09:00:00',
            ],
            [
                'occupation' => 'SMP Negeri 3 Parepare',
                'address' => 'Jl. Veteran No. 25, Parepare',
                'latitude' => -4.0089,
                'longitude' => 119.6245,
                'visit_time' => '11:30:00',
            ],
            [
                'occupation' => 'Kantor Kelurahan Bukit Harapan',
                'address' => 'Jl. Harapan Indah No. 5, Parepare',
                'latitude' => -4.0120,
                'longitude' => 119.6200,
                'visit_time' => '14:00:00',
            ],
            [
                'occupation' => 'Masjid Al-Ikhlas',
                'address' => 'Jl. Mesjid Raya No. 12, Parepare',
                'latitude' => -4.0075,
                'longitude' => 119.6260,
                'visit_time' => '16:00:00',
            ],
        ];
        
        foreach ($locations as $index => $location) {
            $visitTime = \Carbon\Carbon::parse($today . ' ' . $location['visit_time']);
            $endTime = $visitTime->copy()->addHours(2);
            
            Reservation::create([
                'full_name' => 'Contact Person ' . ($index + 1),
                'email' => 'contact' . ($index + 1) . '@test.com',
                'phone_number' => '0812345678' . ($index + 1) . '0',
                'category' => 'Pendidikan',
                'occupation' => $location['occupation'],
                'audience_category' => 'Umum',
                'gender' => 'Laki-laki',
                'address' => $location['address'],
                'reservation_date' => $today,
                'visit_time' => $visitTime,
                'end_time' => $endTime,
                'latitude' => $location['latitude'],
                'longitude' => $location['longitude'],
                'status' => 'confirmed',
            ]);
            
            echo "✅ Reservation created: {$location['occupation']} at {$location['visit_time']}\n";
        }
        
        echo "\n🎉 Sample data created successfully!\n";
        echo "📅 Date: {$today}\n";
        echo "📍 Total reservations: 4 locations\n";
        echo "\nYou can now:\n";
        echo "1. Login as admin: admin@pusling.com / password\n";
        echo "2. Access admin control: /admin/bus-tracking\n";
        echo "3. View public tracking: /bus-tracking\n";
    }
}

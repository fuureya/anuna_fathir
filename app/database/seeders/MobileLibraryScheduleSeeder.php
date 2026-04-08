<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MobileLibraryScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get confirmed reservations
        $confirmedReservations = DB::table('reservations')
            ->where('status', 'confirmed')
            ->get();

        $schedules = [];
        
        foreach ($confirmedReservations as $reservation) {
            $startDateTime = $reservation->reservation_date . ' ' . $reservation->visit_time;
            $endDateTime = date('Y-m-d H:i:s', strtotime($startDateTime . ' +2 hours'));
            
            $schedules[] = [
                'reservation_id' => $reservation->id,
                'scheduled_date' => $reservation->reservation_date,
                'start_time' => $startDateTime,
                'end_time' => $endDateTime,
                'vehicle_id' => 1,
            ];
        }

        if (!empty($schedules)) {
            DB::table('mobile_library_schedule')->insert($schedules);
            $this->command->info('Mobile library schedules created successfully!');
        } else {
            $this->command->info('No confirmed reservations to schedule.');
        }
    }
}

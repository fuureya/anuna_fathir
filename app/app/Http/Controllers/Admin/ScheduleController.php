<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MobileLibrarySchedule;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->query('date');
        $showAll = $request->query('show_all', false); // Option to show all including past schedules
        
        $schedules = MobileLibrarySchedule::query()
            ->with('reservation')
            ->when($date, fn($q) => $q->whereDate('scheduled_date', $date))
            ->when(!$showAll, fn($q) => $q->where('end_time', '>=', now())) // Filter past schedules by default
            ->orderByDesc('scheduled_date')->orderBy('start_time')
            ->paginate(30)->appends($request->query());

        return view('admin.schedule.index', compact('schedules','date', 'showAll'));
    }

    public function preview(Request $request)
    {
        $date = $request->query('date', now()->toDateString());
        // confirmed reservations for the date with visit_time present
        $reservations = Reservation::query()
            ->whereDate('reservation_date', $date)
            ->where('status', 'confirmed')
            ->whereNotNull('visit_time')
            ->orderBy('visit_time')
            ->get();

        // Interval scheduling (greedy by earliest finishing time)
        $selected = [];
        $lastEnd = null;
        foreach ($reservations as $r) {
            $start = Carbon::createFromFormat('H:i:s', strlen($r->visit_time) === 5 ? $r->visit_time.':00' : $r->visit_time);
            // Default 2 jam (120 menit) untuk kunjungan perpustakaan keliling
            $duration = $r->duration_minutes ?: 120;
            $end = Carbon::parse(
                $reservation->reservation_date.' '.$reservation->end_time
            );
            if ($lastEnd === null || $start->greaterThanOrEqualTo($lastEnd)) {
                $selected[] = [
                    'reservation' => $r,
                    'start' => $start,
                    'end' => $end,
                ];
                $lastEnd = $end;
            }
        }

        return view('admin.schedule.preview', [
            'date' => $date,
            'selected' => $selected,
        ]);
    }

    public function commit(Request $request)
    {
        $request->validate(['date' => 'required|date']);
        $date = $request->input('date');

        // recompute same as preview
        $reservations = Reservation::query()
            ->whereDate('reservation_date', $date)
            ->where('status', 'confirmed')
            ->whereNotNull('visit_time')
            ->orderBy('visit_time')
            ->get();

        $selected = [];
        $lastEnd = null;
        foreach ($reservations as $r) {
            $start = Carbon::createFromFormat('H:i:s', strlen($r->visit_time) === 5 ? $r->visit_time.':00' : $r->visit_time);
            // Default 2 jam (120 menit) untuk kunjungan perpustakaan keliling
            $duration = $r->duration_minutes ?: 120;
            $end = Carbon::parse(
                $reservation->reservation_date.' '.$reservation->end_time
            );
            if ($lastEnd === null || $start->greaterThanOrEqualTo($lastEnd)) {
                $selected[] = compact('r','start','end');
                $lastEnd = $end;
            }
        }

        DB::transaction(function () use ($date, $selected) {
            // remove existing schedules for the date to avoid duplicates
            MobileLibrarySchedule::whereDate('scheduled_date', $date)->delete();
            foreach ($selected as $item) {
                $r = $item['r'];
                MobileLibrarySchedule::create([
                    'reservation_id' => $r->id,
                    'scheduled_date' => $date,
                    'start_time' => Carbon::parse($date.' '.$r->visit_time)->format('Y-m-d H:i:s'),
                    // Default 2 jam (120 menit) untuk kunjungan perpustakaan keliling
                    'end_time' => Carbon::parse($date.' '.$r->visit_time)->addMinutes($r->duration_minutes ?: 120)->format('Y-m-d H:i:s'),
                ]);
            }
        });

        return redirect()->route('admin.schedule.index', ['date' => $date])->with('status', "Jadwal berhasil disimpan untuk tanggal $date");
    }
}

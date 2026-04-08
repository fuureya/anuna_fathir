<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ReservationController extends Controller
{
    // Public schedule page - view all confirmed reservations
    public function schedule(Request $request)
    {
        $query = Reservation::where('status', 'confirmed')
            ->where(function($q) {
                // Filter: only show upcoming reservations
                // Calculate end_time = reservation_date + visit_time + duration_minutes
                $q->whereRaw('DATE_ADD(CONCAT(DATE(reservation_date), " ", visit_time), INTERVAL COALESCE(duration_minutes, 120) MINUTE) >= NOW()');
            })
            ->orderBy('reservation_date', 'asc')
            ->orderBy('visit_time', 'asc');
        
        // Apply filters
        if ($request->filled('date_from')) {
            $query->whereDate('reservation_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('reservation_date', '<=', $request->date_to);
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        $reservations = $query->paginate(10)->withQueryString();
        
        // Calculate statistics
        $allConfirmed = Reservation::where('status', 'confirmed')->get();
        $totalReservations = $allConfirmed->count();
        $confirmedCount = $allConfirmed->count();
        $uniqueDates = $allConfirmed->pluck('reservation_date')->unique()->count();
        $avgWaitingTime = $allConfirmed->where('waiting_time', '>', 0)->avg('waiting_time') ?? 0;
        
        return view('reservations.schedule', compact(
            'reservations',
            'totalReservations',
            'confirmedCount',
            'uniqueDates',
            'avgWaitingTime'
        ));
    }
    
    // Public reservation form
    public function create()
    {
        return view('reservations.create');
    }
    
    // API: Get booked time slots for a specific date
    public function getBookedSlots(Request $request)
    {
        $date = $request->query('date');
        
        if (!$date) {
            return response()->json(['error' => 'Date parameter required'], 400);
        }
        
        // Get all confirmed reservations for this date
        $bookedSlots = Reservation::where('reservation_date', $date)
            ->where('status', 'confirmed')
            ->select('visit_time', 'full_name')
            ->get()
            ->map(function($reservation) {
                return [
                    'time' => $reservation->visit_time ? \Carbon\Carbon::parse($reservation->visit_time)->format('H:i') : null,
                    'name' => $reservation->full_name
                ];
            })
            ->filter(function($slot) {
                return $slot['time'] !== null;
            })
            ->values();
        
        return response()->json([
            'date' => $date,
            'booked_slots' => $bookedSlots,
            'count' => $bookedSlots->count()
        ]);
    }

    // Store reservation submission
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'category' => 'required|string|max:100',
            'occupation' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'reservation_date' => 'required|date',
            'visit_time' => 'required|date_format:H:i',
            'request_letter' => 'required|file|mimes:pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        // Check if time slot is already taken
        $date = $request->input('reservation_date');
        $time = $request->input('visit_time');
        
        $existingReservation = Reservation::where('reservation_date', $date)
            ->where('status', 'confirmed')
            ->whereNotNull('visit_time')
            ->get()
            ->first(function($reservation) use ($time) {
                if (!$reservation->visit_time) return false;
                $bookedTime = \Carbon\Carbon::parse($reservation->visit_time)->format('H:i');
                return $bookedTime === $time;
            });
        
        if ($existingReservation) {
            return back()
                ->withInput()
                ->withErrors([
                    'visit_time' => 'Waktu ini sudah direservasi oleh ' . $existingReservation->full_name . '. Silakan pilih waktu lain.'
                ]);
        }

        // Handle upload using Laravel Storage (more secure)
        $file = $request->file('request_letter');
        $newName = 'surat_' . Str::random(16) . '.pdf';
        
        // Store file in storage/app/public/uploads directory
        $path = $file->storeAs('uploads', $newName, 'public');

        // Calculate end_time (2 hours after start)
        $visitTime = $request->input('visit_time');
        $endTime = \Carbon\Carbon::parse($visitTime)->addHours(2)->format('H:i');

        Reservation::create([
            'full_name' => $request->input('full_name'),
            'category' => $request->input('category'),
            'occupation' => $request->input('occupation'),
            'address' => $request->input('address'),
            'phone_number' => $request->input('phone_number'),
            'gender' => $request->input('gender'),
            'reservation_date' => $request->input('reservation_date'),
            'visit_time' => $visitTime,
            'end_time' => $endTime,
            'request_letter' => 'uploads/' . $newName, // Stored in storage/app/public/uploads
            'status' => 'pending',
            'email' => auth()->check() ? auth()->user()->email : $request->input('email'),
            // FCFS fields
            'arrival_time' => now(), // Record when the request was submitted (AT)
            'burst_time' => 120, // Default 2 hours (120 minutes) - BT
        ]);

        return redirect()->route('reservations.success')->with('success', 'Reservasi berhasil dikirim! Menunggu konfirmasi admin.');
    }
    
    // Success page after reservation
    public function success()
    {
        return view('reservations.success');
    }
    
    // My reservations page (for authenticated users)
    public function myReservations()
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Get reservations by user's email only
        $reservations = Reservation::query()
            ->where('email', $user->email)
            ->orderByDesc('reservation_date')
            ->orderByDesc('id')
            ->paginate(10);
        
        return view('reservations.my-reservations', compact('reservations'));
    }

    // JSON: by date (replacement for get_reservations.php)
    public function byDate(Request $request)
    {
        $request->validate(['date' => 'required|date']);
        $date = $request->query('date');

        $reservations = Reservation::query()
            ->whereDate('reservation_date', $date)
            ->select(['id', 'full_name', 'occupation', 'reservation_date', 'latitude', 'longitude', 'audience_category', 'visit_time', 'status'])
            ->get();

        if ($reservations->count() > 4) {
            return response()->json([
                'warning' => 'Hanya menampilkan 4 reservasi pertama.',
                'reservations' => $reservations->take(4)->values(),
            ]);
        }

        return response()->json($reservations);
    }

    // JSON: details (replacement for get_reservation_details.php)
    public function detailsJson(Reservation $reservation)
    {
        return response()->json($reservation);
    }
}

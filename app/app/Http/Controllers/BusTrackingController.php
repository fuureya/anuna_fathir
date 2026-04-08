<?php

namespace App\Http\Controllers;

use App\Models\BusTracking;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BusTrackingController extends Controller
{
    // Halaman tracking untuk public
    public function index(Request $request)
    {
        $date = $request->query('date', today()->format('Y-m-d'));
        
        // Get reservations for the day (confirmed only)
        $reservations = Reservation::where('reservation_date', $date)
            ->where('status', 'confirmed')
            ->orderBy('visit_time')
            ->get();
        
        // Get current bus tracking
        $tracking = BusTracking::where('tracking_date', $date)->first();
        
        return view('bus-tracking.index', compact('reservations', 'tracking', 'date'));
    }
    
    // API: Get current bus status (for real-time updates)
    public function getCurrentStatus(Request $request)
    {
        $date = $request->query('date', today()->format('Y-m-d'));
        
        $tracking = BusTracking::where('tracking_date', $date)
            ->with('currentReservation')
            ->first();
        
        $reservations = Reservation::where('reservation_date', $date)
            ->where('status', 'confirmed')
            ->orderBy('visit_time')
            ->get();
        
        return response()->json([
            'tracking' => $tracking,
            'reservations' => $reservations,
            'timestamp' => now()->toIso8601String(),
        ]);
    }
    
    // Admin: Update bus status
    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'reservation_id' => 'nullable|integer',
            'status' => 'required|in:idle,on_the_way,arrived,serving,finished',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);
        
        $tracking = BusTracking::updateOrCreate(
            ['tracking_date' => $validated['date']],
            [
                'current_reservation_id' => $validated['reservation_id'] ?? null,
                'bus_status' => $validated['status'],
                'current_latitude' => $validated['latitude'] ?? null,
                'current_longitude' => $validated['longitude'] ?? null,
                'status_updated_at' => now(),
                'updated_by' => auth()->id(),
            ]
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Status bus berhasil diupdate',
            'tracking' => $tracking,
        ]);
    }
    
    // Admin: Halaman kontrol bus
    public function adminControl(Request $request)
    {
        $date = $request->query('date', today()->format('Y-m-d'));
        
        $reservations = Reservation::where('reservation_date', $date)
            ->where('status', 'confirmed')
            ->orderBy('visit_time')
            ->get();
        
        $tracking = BusTracking::where('tracking_date', $date)->first();
        
        return view('bus-tracking.admin', compact('reservations', 'tracking', 'date'));
    }
}

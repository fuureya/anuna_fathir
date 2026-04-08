<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class QRVerificationController extends Controller
{
    public function verify(Request $request, $id)
    {
        $reservation = Reservation::find($id);
        
        if (!$reservation) {
            return view('qr.verify', [
                'found' => false,
                'message' => 'Reservasi tidak ditemukan'
            ]);
        }
        
        return view('qr.verify', [
            'found' => true,
            'reservation' => $reservation
        ]);
    }
    
    public function scan()
    {
        return view('qr.scan');
    }
}

<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReservationTemplateController extends Controller
{
    /**
     * Download reservation template PDF
     */
    public function download()
    {
        try {
            // Ensure storage/fonts directory exists for DomPDF
            $fontDir = storage_path('fonts');
            if (!file_exists($fontDir)) {
                mkdir($fontDir, 0755, true);
            }
            
            $pdf = Pdf::loadView('pdf.reservation-template');
            
            // Set paper size and orientation
            $pdf->setPaper('A4', 'portrait');
            
            return $pdf->download('Template_Reservasi_Perpustakaan.pdf');
        } catch (\Exception $e) {
            Log::error('PDF Download Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengunduh template PDF. Silakan coba lagi.');
        }
    }
    
    /**
     * Alias for download method (backward compatibility)
     */
    public function downloadTemplate()
    {
        return $this->download();
    }
}

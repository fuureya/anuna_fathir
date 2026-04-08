<?php

namespace App\Helpers;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;

class QRCodeHelper
{
    /**
     * Generate QR Code untuk reservasi
     * 
     * @param mixed $reservation
     * @return string Base64 data URI
     */
    public static function generateReservationQR($reservation): string
    {
        // QR Code berisi URL verifikasi langsung
        // Sehingga ketika di-scan dengan scanner HP apapun, langsung membuka halaman verifikasi
        $verifyUrl = route('qr.verify', $reservation->id);
        
        $qr = QrCode::create($verifyUrl)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::High)
            ->setSize(300)
            ->setMargin(10)
            ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->setForegroundColor(new Color(6, 147, 227)) // Warna biru tema perpustakaan
            ->setBackgroundColor(new Color(255, 255, 255));

        $writer = new PngWriter();
        $result = $writer->write($qr);
        
        return $result->getDataUri();
    }
    
    /**
     * Generate QR Code dengan custom data
     * 
     * @param string $data
     * @param int $size
     * @return string Base64 data URI
     */
    public static function generate(string $data, int $size = 300): string
    {
        $qr = QrCode::create($data)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::High)
            ->setSize($size)
            ->setMargin(10)
            ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        $writer = new PngWriter();
        $result = $writer->write($qr);
        
        return $result->getDataUri();
    }
}

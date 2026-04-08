<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;

    /**
     * Create a new message instance.
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reservasi Perpustakaan Disetujui - ' . $this->reservation->full_name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation-approved',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        try {
            // Generate QR Code dan attach sebagai file
            $qrDataUri = \App\Helpers\QRCodeHelper::generateReservationQR($this->reservation);
            
            // Convert data URI to binary
            $imageData = explode(',', $qrDataUri)[1];
            $decodedImage = base64_decode($imageData);
            
            // Simpan temporary file
            $tempPath = storage_path('app/temp');
            if (!is_dir($tempPath)) {
                mkdir($tempPath, 0777, true);
            }
            
            $qrFilePath = $tempPath . '/qr_' . $this->reservation->id . '_' . time() . '.png';
            file_put_contents($qrFilePath, $decodedImage);
            
            return [
                \Illuminate\Mail\Mailables\Attachment::fromPath($qrFilePath)
                    ->as('QR_Code_Reservasi.png')
                    ->withMime('image/png'),
            ];
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to generate QR code attachment: ' . $e->getMessage());
            return []; // Return empty array if QR generation fails
        }
    }
}

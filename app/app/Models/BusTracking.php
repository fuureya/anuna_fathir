<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusTracking extends Model
{
    protected $table = 'bus_tracking';
    
    protected $fillable = [
        'tracking_date',
        'current_reservation_id',
        'bus_status',
        'current_latitude',
        'current_longitude',
        'status_updated_at',
        'updated_by',
    ];
    
    protected $casts = [
        'tracking_date' => 'date',
        'status_updated_at' => 'datetime',
    ];
    
    public function currentReservation()
    {
        return $this->belongsTo(Reservation::class, 'current_reservation_id');
    }
    
    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobileLibrarySchedule extends Model
{
    protected $table = 'mobile_library_schedule';
    public $timestamps = false;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'reservation_id',
        'scheduled_date',
        'start_time',
        'end_time',
        'vehicle_id',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }
}

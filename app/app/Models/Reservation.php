<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    
    protected $table = 'reservations';
    public $timestamps = false;

    protected $fillable = [
        'full_name',
        'email',
        'category',
        'audience_category',
        'occupation',
        'address',
        'phone_number',
        'request_letter',
        'gender',
        'reservation_date',
        'latitude',
        'longitude',
        'status',
        'rejection_reason',
        'visit_time',
        'end_time',
        'visit_start',
        'visit_end',
        'duration_minutes',
        // FCFS fields
        'arrival_time',
        'burst_time',
        'start_time',
        'completion_time',
        'waiting_time',
        'turnaround_time',
        'queue_position',
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'visit_start' => 'datetime',
        'visit_end' => 'datetime',
        // FCFS casts
        'arrival_time' => 'datetime',
        'start_time' => 'datetime',
        'completion_time' => 'datetime',
    ];

    public function mobileSchedules()
    {
        return $this->hasMany(MobileLibrarySchedule::class, 'reservation_id');
    }
    
    public function mobileLibrarySchedule()
    {
        return $this->hasOne(MobileLibrarySchedule::class, 'reservation_id');
    }
    
    /**
     * Get queue position attribute (calculate dynamically if not set)
     * 
     * @param mixed $value
     * @return int
     */
    public function getQueuePositionAttribute($value)
    {
        if ($value !== null) {
            return $value;
        }
        
        // Calculate dynamically if not set
        if (!$this->arrival_time) {
            return 0;
        }
        
        return self::where('reservation_date', $this->reservation_date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereNotNull('arrival_time')
            ->where('arrival_time', '<=', $this->arrival_time)
            ->count();
    }
}

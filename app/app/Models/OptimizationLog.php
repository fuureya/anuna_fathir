<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OptimizationLog extends Model
{
    protected $table = 'optimization_logs';
    public $timestamps = false;

    protected $fillable = [
        'optimized_at',
        'before_optimization',
        'after_optimization',
        'total_distance',
        'reservations',
        'status',
    ];

    protected $casts = [
        'optimized_at' => 'datetime',
        'total_distance' => 'decimal:2',
    ];
}

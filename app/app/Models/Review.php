<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';
    public $timestamps = false;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'service_quality',
        'book_availability',
        'book_collection',
        'service_quality_sentiment',
        'book_availability_sentiment',
        'book_collection_sentiment',
    ];
}

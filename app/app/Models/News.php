<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'image',
        'category',
        'event_date',
        'event_time',
        'event_location',
        'is_featured',
        'is_published',
        'author_id',
        'published_at',
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'datetime:H:i',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($news) {
            if (empty($news->slug)) {
                $news->slug = Str::slug($news->title);
            }
            if ($news->is_published && empty($news->published_at)) {
                $news->published_at = now();
            }
        });

        static::updating(function ($news) {
            if ($news->is_published && empty($news->published_at)) {
                $news->published_at = now();
            }
        });
    }

    /**
     * Relasi ke author (User)
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Scope untuk berita yang dipublish
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                     ->where(function ($q) {
                         $q->whereNull('published_at')
                           ->orWhere('published_at', '<=', now());
                     });
    }

    /**
     * Scope untuk berita saja
     */
    public function scopeBerita($query)
    {
        return $query->where('category', 'berita');
    }

    /**
     * Scope untuk agenda saja
     */
    public function scopeAgenda($query)
    {
        return $query->where('category', 'agenda');
    }

    /**
     * Scope untuk berita featured
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return \Storage::url($this->image);
        }
        return asset('images/default-news.jpg');
    }

    /**
     * Get formatted event date
     */
    public function getFormattedEventDateAttribute()
    {
        if ($this->event_date) {
            return $this->event_date->translatedFormat('l, d F Y');
        }
        return null;
    }

    /**
     * Get formatted event time
     */
    public function getFormattedEventTimeAttribute()
    {
        if ($this->event_time) {
            return \Carbon\Carbon::parse($this->event_time)->format('H:i') . ' WITA';
        }
        return null;
    }
}

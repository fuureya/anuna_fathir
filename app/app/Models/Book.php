<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    
    protected $table = 'books';
    protected $primaryKey = 'buku_id';
    public $timestamps = false; // only created_at in schema
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'jumlah_eksemplar',
        'ISBN',
        'kategori',
        'genre',
        'cover_image',
        'deskripsi',
        'audience_category',
    ];

    public function getCoverUrlAttribute(): string
    {
        $filename = $this->cover_image ?: 'default.jpg';
        return asset('covers/' . $filename);
    }
}

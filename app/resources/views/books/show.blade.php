@extends('layouts.app-public')

@section('title', ($book->judul ?? 'Detail Buku') . ' - Detail Buku')

@push('styles')
<style>
    .book-detail-container {
        display: flex;
        max-width: 1200px;
        margin: 40px auto;
        gap: 40px;
        padding: 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    }
    .book-details {
        flex: 2;
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .book-image {
        flex: 1;
    }
    .book-image img {
        width: 100%;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    .book-details h1 {
        color: #0693E3;
        margin-bottom: 10px;
    }
    .book-details hr {
        margin: 20px 0;
        border: none;
        border-top: 1px solid #ddd;
    }
    .back-link {
        display: inline-block;
        margin-top: 20px;
        background-color: #0693E3;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
    }
    .back-link:hover {
        background-color: #057bbf;
    }
</style>
@endpush

@section('content')
<div class="book-detail-container">
    <div class="book-details">
    <h1>{{ $book->judul }}</h1>
        <p><strong>{{ $book->penulis }} - Pengarang</strong></p>
        <p><strong>Kategori:</strong> {{ $book->kategori }}</p>
        
        <hr>
        
        <h4>Deskripsi</h4>
        <p>{{ $book->deskripsi ?? 'Tidak ada deskripsi tersedia.' }}</p>
        
        <hr>
        
        <p><strong>Penerbit:</strong> {{ $book->penerbit }}</p>
        <p><strong>Tahun Terbit:</strong> {{ $book->tahun_terbit }}</p>
        <p><strong>Jumlah Tersedia:</strong> {{ $book->jumlah_eksemplar }}</p>
        <p><strong>ISBN:</strong> {{ $book->ISBN }}</p>
        
        @if($book->genre)
            <p><strong>Genre:</strong> {{ $book->genre }}</p>
        @endif
        
        @if($book->audience_category)
            <p><strong>Kategori Usia:</strong> {{ $book->audience_category }}</p>
        @endif
        
        <hr>
        
        <a href="{{ route('books.index') }}" class="back-link">Buku Rekomendasi Lainnya</a>
    </div>

    <div class="book-image">
           @if($book->cover_image && filter_var($book->cover_image, FILTER_VALIDATE_URL))
              <img src="{{ $book->cover_image }}" 
                  alt="{{ $book->judul }}">
           @elseif($book->cover_image)
              <img src="{{ asset('covers/' . $book->cover_image) }}" 
                  alt="{{ $book->judul }}">
           @else
              <img src="{{ asset('covers/default.jpg') }}" 
                  alt="{{ $book->judul }}">
           @endif
    </div>
</div>
@endsection

@extends('layouts.app-public')

@section('title', 'Berita - Perpustakaan Keliling')

@push('styles')
<style>
    .news-hero {
        background: linear-gradient(135deg, #0693E3 0%, #0056b3 100%);
        padding: 60px 30px;
        color: white;
        text-align: center;
    }
    .news-hero h1 {
        font-size: 2.5em;
        margin-bottom: 10px;
    }
    .news-hero p {
        font-size: 1.1em;
        opacity: 0.9;
    }
    
    .news-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px;
    }
    
    .search-box {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }
    .search-box form {
        display: flex;
        gap: 10px;
    }
    .search-box input {
        flex: 1;
        padding: 12px 20px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 1rem;
    }
    .search-box button {
        background: #0693E3;
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
    }
    .search-box button:hover {
        background: #0056b3;
    }
    
    /* Featured News */
    .featured-news {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        margin-bottom: 40px;
        display: grid;
        grid-template-columns: 1fr 1fr;
    }
    .featured-news img {
        width: 100%;
        height: 100%;
        min-height: 300px;
        object-fit: cover;
    }
    .featured-content {
        padding: 30px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .featured-badge {
        background: #fbbf24;
        color: #92400e;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 15px;
        width: fit-content;
    }
    .featured-content h2 {
        font-size: 1.8em;
        margin-bottom: 15px;
        color: #1f2937;
    }
    .featured-content p {
        color: #6b7280;
        line-height: 1.7;
        margin-bottom: 20px;
    }
    .featured-content .read-more {
        color: #0693E3;
        text-decoration: none;
        font-weight: 600;
    }
    .featured-content .read-more:hover {
        text-decoration: underline;
    }
    
    /* News Grid */
    .news-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 25px;
    }
    .news-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    .news-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .news-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    .news-card-content {
        padding: 20px;
    }
    .news-date {
        color: #0693E3;
        font-size: 0.85rem;
        margin-bottom: 10px;
    }
    .news-card h3 {
        font-size: 1.2em;
        margin-bottom: 10px;
        color: #1f2937;
        line-height: 1.4;
    }
    .news-card h3 a {
        color: inherit;
        text-decoration: none;
    }
    .news-card h3 a:hover {
        color: #0693E3;
    }
    .news-excerpt {
        color: #6b7280;
        font-size: 0.95rem;
        line-height: 1.6;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6b7280;
    }
    .empty-state svg {
        width: 80px;
        height: 80px;
        margin-bottom: 20px;
        opacity: 0.5;
    }
    
    /* Pagination */
    .pagination-container {
        margin-top: 40px;
        display: flex;
        justify-content: center;
    }
    
    @media (max-width: 768px) {
        .featured-news {
            grid-template-columns: 1fr;
        }
        .news-grid {
            grid-template-columns: 1fr;
        }
        .search-box form {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="news-hero">
    <h1>📰 Berita Perpustakaan</h1>
    <p>Ikuti berita dan informasi terbaru dari Perpustakaan Keliling Parepare</p>
</div>

<div class="news-container">
    <!-- Search -->
    <div class="search-box">
        <form action="{{ route('news.index') }}" method="GET">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berita...">
            <button type="submit">🔍 Cari</button>
        </form>
    </div>

    <!-- Featured News -->
    @if($featuredNews)
    <div class="featured-news">
        @if($featuredNews->image)
            <img src="{{ Storage::url($featuredNews->image) }}" alt="{{ $featuredNews->title }}">
        @else
            <div style="background: linear-gradient(135deg, #0693E3 0%, #0056b3 100%); min-height: 300px; display: flex; align-items: center; justify-content: center;">
                <span style="font-size: 4rem;">📰</span>
            </div>
        @endif
        <div class="featured-content">
            <span class="featured-badge">⭐ Berita Utama</span>
            <h2>{{ $featuredNews->title }}</h2>
            <p>{{ $featuredNews->excerpt ?: Str::limit(strip_tags($featuredNews->content), 200) }}</p>
            <div>
                <span style="color: #9ca3af; font-size: 0.9rem;">
                    {{ $featuredNews->published_at?->translatedFormat('d F Y') }}
                </span>
            </div>
            <a href="{{ route('news.show', $featuredNews->slug) }}" class="read-more" style="margin-top: 15px;">
                Baca Selengkapnya →
            </a>
        </div>
    </div>
    @endif

    <!-- News Grid -->
    @if($news->count() > 0)
    <h3 style="font-size: 1.5em; margin-bottom: 20px; color: #1f2937;">Berita Terbaru</h3>
    <div class="news-grid">
        @foreach($news as $item)
            <article class="news-card">
                @if($item->image)
                    <img src="{{ Storage::url($item->image) }}" alt="{{ $item->title }}">
                @else
                    <div style="height: 200px; background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); display: flex; align-items: center; justify-content: center;">
                        <span style="font-size: 3rem;">📄</span>
                    </div>
                @endif
                <div class="news-card-content">
                    <div class="news-date">
                        📅 {{ $item->published_at?->translatedFormat('d F Y') }}
                    </div>
                    <h3>
                        <a href="{{ route('news.show', $item->slug) }}">{{ $item->title }}</a>
                    </h3>
                    <p class="news-excerpt">
                        {{ $item->excerpt ?: Str::limit(strip_tags($item->content), 120) }}
                    </p>
                </div>
            </article>
        @endforeach
    </div>

    <div class="pagination-container">
        {{ $news->links() }}
    </div>
    @else
    <div class="empty-state">
        <div style="font-size: 4rem; margin-bottom: 20px;">📭</div>
        <h3>Belum Ada Berita</h3>
        <p>Berita akan ditampilkan di sini saat tersedia.</p>
    </div>
    @endif
</div>
@endsection

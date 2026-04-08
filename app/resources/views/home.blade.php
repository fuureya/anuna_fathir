@extends('layouts.app-public')

@section('title', 'Beranda - Perpustakaan Keliling')

@push('styles')
<style>
    /* Hero News Slider */
    .hero-news {
        max-width: 1400px;
        margin: 0 auto;
        padding: 30px;
    }
    .news-slider-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        min-height: 400px;
    }
    .main-news {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        background: #1a1a2e;
        display: block;
        text-decoration: none;
        color: inherit;
    }
    .main-news:hover {
        transform: scale(1.01);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }
    .main-news img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        min-height: 400px;
    }
    .main-news-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.5) 50%, transparent 100%);
        padding: 30px;
        color: white;
    }
    .news-category-label {
        display: inline-block;
        background: #0693E3;
        color: white;
        padding: 5px 15px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 15px;
    }
    .news-category-label.agenda {
        background: #7c3aed;
    }
    .main-news-overlay h2 {
        font-size: 1.6rem;
        margin-bottom: 10px;
        line-height: 1.3;
        color: white;
    }
    .news-meta {
        font-size: 0.85rem;
        opacity: 0.9;
    }
    .news-meta span {
        margin-right: 15px;
    }
    
    /* Side News List */
    .side-news {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    .side-news-item {
        display: flex;
        gap: 15px;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
    }
    .side-news-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.12);
    }
    .side-news-item img {
        width: 120px;
        height: 90px;
        object-fit: cover;
        flex-shrink: 0;
    }
    .side-news-content {
        padding: 12px 15px 12px 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .side-news-content .news-category-label {
        font-size: 0.65rem;
        padding: 3px 8px;
        margin-bottom: 8px;
    }
    .side-news-content h4 {
        font-size: 0.9rem;
        margin: 0;
        line-height: 1.4;
        color: #1f2937;
    }
    .side-news-date {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 5px;
    }
    
    /* News Section */
    .news-section {
        max-width: 1400px;
        margin: 0 auto;
        padding: 40px 30px;
        background: #f8fafc;
    }
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        border-bottom: 3px solid #0693E3;
        padding-bottom: 15px;
    }
    .section-title {
        font-size: 1.5em;
        color: #1f2937;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .section-title::before {
        content: '';
        width: 5px;
        height: 25px;
        background: #0693E3;
        border-radius: 3px;
    }
    .section-link {
        color: #0693E3;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        padding: 8px 20px;
        border: 2px solid #0693E3;
        border-radius: 25px;
        transition: all 0.3s;
    }
    .section-link:hover {
        background: #0693E3;
        color: white;
    }
    
    /* News Grid */
    .news-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
    }
    .news-card {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        display: block;
        text-decoration: none;
        color: inherit;
    }
    .news-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .news-card-image {
        position: relative;
        height: 180px;
        overflow: hidden;
    }
    .news-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }
    .news-card:hover .news-card-image img {
        transform: scale(1.05);
    }
    .news-card-image .news-category-label {
        position: absolute;
        top: 15px;
        left: 15px;
    }
    .news-card-content {
        padding: 20px;
    }
    .news-card h3 {
        font-size: 1rem;
        margin-bottom: 10px;
        color: #1f2937;
        line-height: 1.4;
    }
    .news-card-meta {
    }
    .news-card-meta {
        display: flex;
        align-items: center;
        gap: 15px;
        font-size: 0.8rem;
        color: #6b7280;
    }
    .news-card-meta span {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    /* Agenda Section */
    .agenda-section {
        max-width: 1400px;
        margin: 0 auto;
        padding: 40px 30px;
    }
    .section-title.agenda::before {
        background: #7c3aed;
    }
    .agenda-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
    }
    .agenda-card {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    .agenda-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .agenda-card-header {
        background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
        color: white;
        padding: 20px;
        text-align: center;
    }
    .agenda-card-header .day {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1;
    }
    .agenda-card-header .month-year {
        font-size: 0.9rem;
        text-transform: uppercase;
        margin-top: 5px;
    }
    .agenda-card-content {
        padding: 20px;
    }
    .agenda-card h3 {
        font-size: 1rem;
        margin-bottom: 12px;
        color: #1f2937;
        line-height: 1.4;
    }
    .agenda-card h3 a {
        color: inherit;
        text-decoration: none;
    }
    .agenda-card h3 a:hover {
        color: #7c3aed;
    }
    .agenda-info {
        font-size: 0.85rem;
        color: #6b7280;
    }
    .agenda-info p {
        margin: 5px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    /* Empty State */
    .empty-news {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 12px;
    }
    .empty-news-icon {
        font-size: 4rem;
        margin-bottom: 15px;
    }
    .empty-news h3 {
        color: #374151;
        margin-bottom: 10px;
    }
    .empty-news p {
        color: #6b7280;
    }
    
    /* Responsive */
    @media (max-width: 1024px) {
        .news-slider-container {
            grid-template-columns: 1fr;
        }
        .side-news {
            flex-direction: row;
            overflow-x: auto;
        }
        .side-news-item {
            min-width: 280px;
        }
        .news-grid, .agenda-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 768px) {
        .news-grid, .agenda-grid {
            grid-template-columns: 1fr;
        }
        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        .hero-news, .news-section, .agenda-section {
            padding: 20px 15px;
        }
        .main-news img {
            min-height: 250px;
        }
        .main-news-overlay h2 {
            font-size: 1.2rem;
        }
    }

    .recommendation-title {
        text-align: center;
        font-size: 2em;
        margin-top: 30px;
        color: #0693E3;
    }
    .recommendation-subtitle {
        text-align: center;
        font-size: 1.2em;
        margin-bottom: 30px;
        color: #555;
    }
    .books-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 25px;
        padding: 30px;
        max-width: 1400px;
        margin: 0 auto;
        justify-items: center;
    }
    .book {
        position: relative;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        overflow: hidden;
        transition: all 0.3s ease;
        width: 220px;
        cursor: pointer;
    }
    .book:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 8px 20px rgba(0,0,0,0.25);
    }
    .book a {
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .book img {
        width: 100%;
        height: 320px;
        object-fit: cover;
        display: block;
    }
    .book-info {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.7) 70%, transparent 100%);
        color: white;
        padding: 15px;
        transition: all 0.3s ease;
    }
    .book:hover .book-info {
        background: linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0.8) 80%, transparent 100%);
        padding: 20px 15px;
    }
    .book-info h3 {
        margin: 0 0 8px 0;
        font-size: 1rem;
        font-weight: 600;
        line-height: 1.3;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    .book-info p {
        margin: 5px 0;
        font-size: 0.85rem;
        opacity: 0.95;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
@endpush

@section('content')
<!-- Hero News Section -->
@if($latestNews->count() > 0)
<section class="hero-news">
    <div class="news-slider-container">
        <!-- Main Featured News -->
        @php $featuredNews = $latestNews->first(); @endphp
        <a href="{{ route('news.show', $featuredNews->slug) }}" class="main-news">
            @if($featuredNews->image)
                <img src="{{ Storage::url($featuredNews->image) }}" alt="{{ $featuredNews->title }}">
            @else
                <div style="width: 100%; height: 100%; min-height: 400px; background: linear-gradient(135deg, #1e3a5f 0%, #0693E3 100%); display: flex; align-items: center; justify-content: center;">
                    <span style="font-size: 6rem; opacity: 0.3;">📰</span>
                </div>
            @endif
            <div class="main-news-overlay">
                <span class="news-category-label">BERITA</span>
                <h2>{{ $featuredNews->title }}</h2>
                <div class="news-meta">
                    <span>👤 {{ $featuredNews->author->name ?? 'Admin' }}</span>
                    <span>📅 {{ $featuredNews->published_at?->translatedFormat('d F Y') }}</span>
                </div>
            </div>
        </a>
        
        <!-- Side News List -->
        @if($latestNews->count() > 1)
        <div class="side-news">
            @foreach($latestNews->skip(1) as $sideNews)
            <a href="{{ route('news.show', $sideNews->slug) }}" class="side-news-item">
                @if($sideNews->image)
                    <img src="{{ Storage::url($sideNews->image) }}" alt="{{ $sideNews->title }}">
                @else
                    <div style="width: 120px; height: 90px; background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <span style="font-size: 2rem;">📄</span>
                    </div>
                @endif
                <div class="side-news-content">
                    <span class="news-category-label">BERITA</span>
                    <h4>{{ Str::limit($sideNews->title, 60) }}</h4>
                    <div class="side-news-date">{{ $sideNews->published_at?->translatedFormat('d F Y') }}</div>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endif

<!-- Berita Grid Section -->
@if($latestNews->count() > 0)
<section class="news-section">
    <div class="section-header">
        <h2 class="section-title">Berita Terbaru</h2>
        <a href="{{ route('news.index') }}" class="section-link">Lihat Semua Berita →</a>
    </div>
    <div class="news-grid">
        @foreach($latestNews as $news)
        <a href="{{ route('news.show', $news->slug) }}" class="news-card">
            <div class="news-card-image">
                @if($news->image)
                    <img src="{{ Storage::url($news->image) }}" alt="{{ $news->title }}">
                @else
                    <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); display: flex; align-items: center; justify-content: center;">
                        <span style="font-size: 3rem;">📰</span>
                    </div>
                @endif
                <span class="news-category-label">BERITA</span>
            </div>
            <div class="news-card-content">
                <h3>{{ $news->title }}</h3>
                <div class="news-card-meta">
                    <span>👤 {{ $news->author->name ?? 'Admin' }}</span>
                    <span>📅 {{ $news->published_at?->translatedFormat('d M Y') }}</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</section>
@endif

<!-- Agenda Section -->
@if($upcomingAgenda->count() > 0)
<section class="agenda-section">
    <div class="section-header">
        <h2 class="section-title agenda">Agenda Mendatang</h2>
        <a href="{{ route('news.agenda') }}" class="section-link" style="border-color: #7c3aed; color: #7c3aed;">Lihat Semua Agenda →</a>
    </div>
    <div class="agenda-grid">
        @foreach($upcomingAgenda as $agenda)
        <article class="agenda-card">
            <div class="agenda-card-header">
                <div class="day">{{ $agenda->event_date->format('d') }}</div>
                <div class="month-year">{{ $agenda->event_date->translatedFormat('M Y') }}</div>
            </div>
            <div class="agenda-card-content">
                <h3><a href="{{ route('news.show', $agenda->slug) }}">{{ $agenda->title }}</a></h3>
                <div class="agenda-info">
                    @if($agenda->event_time)
                    <p>🕐 {{ \Carbon\Carbon::parse($agenda->event_time)->format('H:i') }} WITA</p>
                    @endif
                    @if($agenda->event_location)
                    <p>📍 {{ $agenda->event_location }}</p>
                    @endif
                </div>
            </div>
        </article>
        @endforeach
    </div>
</section>
@endif

<!-- Rekomendasi Buku Section -->
<section>
    <div class="overlay-container">
        <h3 class="recommendation-title">Rekomendasi Buku</h3>
        <h4 class="recommendation-subtitle">Tingkatkan literasi membacamu hari ini!</h4>
        
        <div class="books-container">
            @forelse($books as $book)
                <div class="book">
                    <a href="{{ route('books.show', $book->buku_id) }}">
                        @if($book->cover_image && filter_var($book->cover_image, FILTER_VALIDATE_URL))
                            <img src="{{ $book->cover_image }}" 
                                 alt="{{ $book->judul }}"
                                 loading="lazy">
                        @elseif($book->cover_image)
                            <img src="{{ asset('covers/' . $book->cover_image) }}" 
                                 alt="{{ $book->judul }}"
                                 loading="lazy">
                        @else
                            <img src="{{ asset('covers/default.jpg') }}" 
                                 alt="{{ $book->judul }}"
                                 loading="lazy">
                        @endif
                        <div class="book-info">
                            <h3>{{ $book->judul }}</h3>
                            <p><strong>Pengarang:</strong> {{ $book->penulis }}</p>
                            <p><strong>Kategori:</strong> {{ $book->kategori }}</p>
                        </div>
                    </a>
                </div>
            @empty
                <p style="text-align: center; grid-column: 1 / -1;">Belum ada buku tersedia.</p>
            @endforelse
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('books.index') }}" style="
                display: inline-block;
                background-color: #0693E3;
                color: white;
                padding: 12px 30px;
                border-radius: 5px;
                text-decoration: none;
                font-weight: bold;
            ">Lihat Semua Koleksi Buku</a>
        </div>
    </div>
</section>
@endsection

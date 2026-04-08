@extends('layouts.app-public')

@section('title', $news->title . ' - Perpustakaan Keliling')

@push('styles')
<style>
    .news-detail-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 30px;
    }
    
    .breadcrumb {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        color: #6b7280;
        font-size: 0.9rem;
    }
    .breadcrumb a {
        color: #0693E3;
        text-decoration: none;
    }
    .breadcrumb a:hover {
        text-decoration: underline;
    }
    
    .news-header {
        margin-bottom: 30px;
    }
    .news-category-badge {
        display: inline-block;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 15px;
    }
    .news-category-badge.berita {
        background: #dbeafe;
        color: #1e40af;
    }
    .news-category-badge.agenda {
        background: #ede9fe;
        color: #5b21b6;
    }
    
    .news-title {
        font-size: 2.2em;
        color: #1f2937;
        margin-bottom: 15px;
        line-height: 1.3;
    }
    
    .news-meta {
        display: flex;
        gap: 20px;
        color: #6b7280;
        font-size: 0.95rem;
        flex-wrap: wrap;
    }
    .news-meta span {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    /* Agenda Info Box */
    .agenda-info-box {
        background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
        color: white;
        padding: 20px;
        border-radius: 12px;
        margin: 20px 0;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 20px;
    }
    .agenda-info-item {
        text-align: center;
    }
    .agenda-info-item .icon {
        font-size: 1.5rem;
        margin-bottom: 5px;
    }
    .agenda-info-item .label {
        font-size: 0.8rem;
        opacity: 0.8;
    }
    .agenda-info-item .value {
        font-size: 1.1rem;
        font-weight: 600;
    }
    
    .news-featured-image {
        width: 100%;
        max-height: 500px;
        object-fit: cover;
        border-radius: 12px;
        margin-bottom: 30px;
    }
    
    .news-content {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #374151;
    }
    .news-content p {
        margin-bottom: 1.5em;
    }
    .news-content h2, .news-content h3 {
        margin-top: 1.5em;
        margin-bottom: 0.8em;
        color: #1f2937;
    }
    .news-content ul, .news-content ol {
        margin-bottom: 1.5em;
        padding-left: 1.5em;
    }
    .news-content li {
        margin-bottom: 0.5em;
    }
    .news-content img {
        max-width: 100%;
        border-radius: 8px;
        margin: 1em 0;
    }
    
    /* Share Section */
    .share-section {
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
    }
    .share-section h4 {
        margin-bottom: 15px;
        color: #374151;
    }
    .share-buttons {
        display: flex;
        gap: 10px;
    }
    .share-btn {
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    .share-btn.whatsapp {
        background: #25d366;
        color: white;
    }
    .share-btn.facebook {
        background: #1877f2;
        color: white;
    }
    .share-btn.twitter {
        background: #1da1f2;
        color: white;
    }
    .share-btn:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }
    
    /* Related News */
    .related-section {
        margin-top: 50px;
        padding-top: 30px;
        border-top: 1px solid #e5e7eb;
    }
    .related-section h3 {
        margin-bottom: 20px;
        color: #1f2937;
    }
    .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }
    .related-card {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    .related-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.12);
    }
    .related-card img {
        width: 100%;
        height: 140px;
        object-fit: cover;
    }
    .related-card-content {
        padding: 15px;
    }
    .related-card h4 {
        font-size: 1rem;
        margin-bottom: 8px;
        line-height: 1.4;
    }
    .related-card h4 a {
        color: #1f2937;
        text-decoration: none;
    }
    .related-card h4 a:hover {
        color: #0693E3;
    }
    .related-card .date {
        color: #9ca3af;
        font-size: 0.85rem;
    }
    
    @media (max-width: 768px) {
        .news-title {
            font-size: 1.6em;
        }
        .news-meta {
            flex-direction: column;
            gap: 10px;
        }
    }
</style>
@endpush

@section('content')
<div class="news-detail-container">
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Beranda</a>
        <span>›</span>
        @if($news->category == 'berita')
            <a href="{{ route('news.index') }}">Berita</a>
        @else
            <a href="{{ route('news.agenda') }}">Agenda</a>
        @endif
        <span>›</span>
        <span>{{ Str::limit($news->title, 30) }}</span>
    </div>

    <!-- Header -->
    <div class="news-header">
        <span class="news-category-badge {{ $news->category }}">
            {{ $news->category == 'berita' ? '📰 Berita' : '📅 Agenda' }}
        </span>
        <h1 class="news-title">{{ $news->title }}</h1>
        <div class="news-meta">
            <span>📅 {{ $news->published_at?->translatedFormat('l, d F Y') }}</span>
            @if($news->author)
            <span>✍️ {{ $news->author->name }}</span>
            @endif
        </div>
    </div>

    <!-- Agenda Info Box -->
    @if($news->category == 'agenda' && $news->event_date)
    <div class="agenda-info-box">
        <div class="agenda-info-item">
            <div class="icon">📆</div>
            <div class="label">Tanggal</div>
            <div class="value">{{ $news->event_date->translatedFormat('d M Y') }}</div>
        </div>
        @if($news->event_time)
        <div class="agenda-info-item">
            <div class="icon">🕐</div>
            <div class="label">Waktu</div>
            <div class="value">{{ \Carbon\Carbon::parse($news->event_time)->format('H:i') }} WITA</div>
        </div>
        @endif
        @if($news->event_location)
        <div class="agenda-info-item">
            <div class="icon">📍</div>
            <div class="label">Lokasi</div>
            <div class="value">{{ $news->event_location }}</div>
        </div>
        @endif
        <div class="agenda-info-item">
            <div class="icon">{{ $news->event_date >= now() ? '✅' : '⏰' }}</div>
            <div class="label">Status</div>
            <div class="value">{{ $news->event_date >= now() ? 'Akan Datang' : 'Sudah Berlalu' }}</div>
        </div>
    </div>
    @endif

    <!-- Featured Image -->
    @if($news->image)
    <img src="{{ Storage::url($news->image) }}" alt="{{ $news->title }}" class="news-featured-image">
    @endif

    <!-- Content -->
    <div class="news-content">
        {!! nl2br(e($news->content)) !!}
    </div>

    <!-- Share Section -->
    <div class="share-section">
        <h4>Bagikan:</h4>
        <div class="share-buttons">
            <a href="https://wa.me/?text={{ urlencode($news->title . ' - ' . route('news.show', $news->slug)) }}" 
               target="_blank" class="share-btn whatsapp">
                WhatsApp
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('news.show', $news->slug)) }}" 
               target="_blank" class="share-btn facebook">
                Facebook
            </a>
            <a href="https://twitter.com/intent/tweet?text={{ urlencode($news->title) }}&url={{ urlencode(route('news.show', $news->slug)) }}" 
               target="_blank" class="share-btn twitter">
                Twitter
            </a>
        </div>
    </div>

    <!-- Related News -->
    @if($relatedNews->count() > 0)
    <div class="related-section">
        <h3>{{ $news->category == 'berita' ? '📰 Berita Lainnya' : '📅 Agenda Lainnya' }}</h3>
        <div class="related-grid">
            @foreach($relatedNews as $related)
            <div class="related-card">
                @if($related->image)
                    <img src="{{ Storage::url($related->image) }}" alt="{{ $related->title }}">
                @else
                    <div style="height: 140px; background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); display: flex; align-items: center; justify-content: center;">
                        <span style="font-size: 2rem;">{{ $related->category == 'berita' ? '📄' : '📅' }}</span>
                    </div>
                @endif
                <div class="related-card-content">
                    <h4><a href="{{ route('news.show', $related->slug) }}">{{ Str::limit($related->title, 50) }}</a></h4>
                    <div class="date">
                        @if($related->category == 'agenda' && $related->event_date)
                            {{ $related->event_date->translatedFormat('d M Y') }}
                        @else
                            {{ $related->published_at?->translatedFormat('d M Y') }}
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

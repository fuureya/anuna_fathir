@extends('layouts.app-public')

@section('title', 'Agenda - Perpustakaan Keliling')

@push('styles')
<style>
    .agenda-hero {
        background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
        padding: 60px 30px;
        color: white;
        text-align: center;
    }
    .agenda-hero h1 {
        font-size: 2.5em;
        margin-bottom: 10px;
    }
    .agenda-hero p {
        font-size: 1.1em;
        opacity: 0.9;
    }
    
    .agenda-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px;
    }
    
    /* Filter Tabs */
    .filter-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }
    .filter-tab {
        padding: 10px 25px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .filter-tab.active {
        background: #7c3aed;
        color: white;
    }
    .filter-tab:not(.active) {
        background: white;
        color: #374151;
        border: 2px solid #e5e7eb;
    }
    .filter-tab:not(.active):hover {
        border-color: #7c3aed;
        color: #7c3aed;
    }
    
    /* Upcoming Count Badge */
    .upcoming-badge {
        background: #10b981;
        color: white;
        padding: 3px 10px;
        border-radius: 10px;
        font-size: 0.85rem;
        margin-left: 5px;
    }
    
    /* Agenda Grid */
    .agenda-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 25px;
    }
    
    .agenda-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }
    .agenda-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .agenda-card img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }
    .agenda-card-content {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    /* Date Badge */
    .date-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
        color: white;
        padding: 10px 15px;
        border-radius: 10px;
        margin-bottom: 15px;
        width: fit-content;
    }
    .date-badge .day {
        font-size: 1.8rem;
        font-weight: 700;
        line-height: 1;
    }
    .date-badge .month-year {
        font-size: 0.85rem;
        line-height: 1.3;
    }
    
    .agenda-card h3 {
        font-size: 1.15em;
        margin-bottom: 10px;
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
    
    .agenda-meta {
        display: flex;
        flex-direction: column;
        gap: 5px;
        margin-top: auto;
        padding-top: 15px;
        border-top: 1px solid #f3f4f6;
        color: #6b7280;
        font-size: 0.9rem;
    }
    .agenda-meta span {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    /* Past Event Overlay */
    .past-event {
        position: relative;
    }
    .past-event::after {
        content: 'Sudah Berlalu';
        position: absolute;
        top: 15px;
        right: 15px;
        background: #ef4444;
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6b7280;
    }
    
    /* Pagination */
    .pagination-container {
        margin-top: 40px;
        display: flex;
        justify-content: center;
    }
    
    @media (max-width: 768px) {
        .agenda-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="agenda-hero">
    <h1>📅 Agenda Kegiatan</h1>
    <p>Jangan lewatkan berbagai kegiatan menarik dari Perpustakaan Keliling Parepare</p>
</div>

<div class="agenda-container">
    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <a href="{{ route('news.agenda') }}" class="filter-tab {{ !request('filter') || request('filter') == 'upcoming' ? 'active' : '' }}">
            📆 Akan Datang
            @if($upcomingCount > 0)
                <span class="upcoming-badge">{{ $upcomingCount }}</span>
            @endif
        </a>
        <a href="{{ route('news.agenda', ['filter' => 'past']) }}" class="filter-tab {{ request('filter') == 'past' ? 'active' : '' }}">
            📋 Sudah Berlalu
        </a>
    </div>

    @if($agendas->count() > 0)
    <div class="agenda-grid">
        @foreach($agendas as $agenda)
            <article class="agenda-card {{ $agenda->event_date < now() ? 'past-event' : '' }}">
                @if($agenda->image)
                    <img src="{{ Storage::url($agenda->image) }}" alt="{{ $agenda->title }}">
                @else
                    <div style="height: 180px; background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%); display: flex; align-items: center; justify-content: center;">
                        <span style="font-size: 3rem;">📅</span>
                    </div>
                @endif
                <div class="agenda-card-content">
                    @if($agenda->event_date)
                    <div class="date-badge">
                        <span class="day">{{ $agenda->event_date->format('d') }}</span>
                        <div class="month-year">
                            <div>{{ $agenda->event_date->translatedFormat('M') }}</div>
                            <div>{{ $agenda->event_date->format('Y') }}</div>
                        </div>
                    </div>
                    @endif
                    <h3>
                        <a href="{{ route('news.show', $agenda->slug) }}">{{ $agenda->title }}</a>
                    </h3>
                    <p style="color: #6b7280; font-size: 0.95rem; line-height: 1.6; margin-bottom: 15px;">
                        {{ $agenda->excerpt ?: Str::limit(strip_tags($agenda->content), 100) }}
                    </p>
                    <div class="agenda-meta">
                        @if($agenda->event_time)
                        <span>🕐 {{ \Carbon\Carbon::parse($agenda->event_time)->format('H:i') }} WITA</span>
                        @endif
                        @if($agenda->event_location)
                        <span>📍 {{ $agenda->event_location }}</span>
                        @endif
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    <div class="pagination-container">
        {{ $agendas->links() }}
    </div>
    @else
    <div class="empty-state">
        <div style="font-size: 4rem; margin-bottom: 20px;">📅</div>
        <h3>Belum Ada Agenda</h3>
        <p>
            @if(request('filter') == 'past')
                Belum ada agenda yang sudah berlalu.
            @else
                Belum ada agenda yang akan datang.
            @endif
        </p>
    </div>
    @endif
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Kelola Berita & Agenda')

@push('styles')
<style>
    .news-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .news-header h1 {
        font-size: 1.5rem;
        font-weight: bold;
        color: #1f2937;
        margin: 0;
    }
    .btn-add {
        background: #0693E3;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        transition: background 0.3s;
    }
    .btn-add:hover {
        background: #0574b8;
    }
    .alert-success {
        background: #d1fae5;
        border: 1px solid #10b981;
        color: #065f46;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .filter-form {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .filter-form select,
    .filter-form input {
        padding: 10px 15px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
    }
    .filter-form input {
        min-width: 200px;
    }
    .btn-search {
        background: #6b7280;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
    }
    .btn-search:hover {
        background: #4b5563;
    }
    .news-table {
        width: 100%;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-collapse: collapse;
    }
    .news-table th {
        background: #f3f4f6;
        padding: 15px;
        text-align: left;
        font-weight: 600;
        color: #374151;
        font-size: 13px;
        text-transform: uppercase;
    }
    .news-table td {
        padding: 15px;
        border-top: 1px solid #e5e7eb;
        vertical-align: middle;
    }
    .news-table tr:hover td {
        background: #f9fafb;
    }
    .news-image {
        width: 80px;
        height: 50px;
        object-fit: cover;
        border-radius: 6px;
    }
    .news-image-placeholder {
        width: 80px;
        height: 50px;
        background: #e5e7eb;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #9ca3af;
        font-size: 11px;
    }
    .news-title {
        font-weight: 500;
        color: #1f2937;
    }
    .badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    .badge-featured {
        background: #fef3c7;
        color: #92400e;
        margin-top: 5px;
    }
    .badge-berita {
        background: #dbeafe;
        color: #1e40af;
    }
    .badge-agenda {
        background: #ede9fe;
        color: #5b21b6;
    }
    .badge-published {
        background: #d1fae5;
        color: #065f46;
    }
    .badge-draft {
        background: #f3f4f6;
        color: #374151;
    }
    .date-info {
        font-size: 14px;
        color: #374151;
    }
    .date-time {
        font-size: 12px;
        color: #6b7280;
    }
    .action-buttons {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .action-btn {
        padding: 6px 10px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 16px;
        transition: all 0.2s;
    }
    .action-btn:hover {
        transform: scale(1.1);
    }
    .btn-view {
        background: #dbeafe;
    }
    .btn-edit {
        background: #d1fae5;
    }
    .btn-delete {
        background: #fee2e2;
        border: none;
        cursor: pointer;
    }
    .empty-row td {
        text-align: center;
        color: #6b7280;
        padding: 40px !important;
    }
    .pagination-wrapper {
        margin-top: 20px;
    }
</style>
@endpush

@section('content')
<div class="p-6">
    <div class="news-header">
        <h1>📰 Kelola Berita & Agenda</h1>
        <a href="{{ route('admin.news.create') }}" class="btn-add">+ Tambah Berita Baru</a>
    </div>

    @if(session('success'))
        <div class="alert-success">
            ✅ {{ session('success') }}
        </div>
    @endif

    <!-- Filter -->
    <form action="{{ route('admin.news.index') }}" method="GET" class="filter-form">
        <select name="category">
            <option value="">Semua Kategori</option>
            <option value="berita" {{ request('category') == 'berita' ? 'selected' : '' }}>📰 Berita</option>
            <option value="agenda" {{ request('category') == 'agenda' ? 'selected' : '' }}>📅 Agenda</option>
        </select>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berita...">
        <button type="submit" class="btn-search">🔍 Cari</button>
    </form>

    <table class="news-table">
        <thead>
            <tr>
                <th>Gambar</th>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($news as $item)
                <tr>
                    <td>
                        @if($item->image)
                            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->title }}" class="news-image">
                        @else
                            <div class="news-image-placeholder">No Image</div>
                        @endif
                    </td>
                    <td>
                        <div class="news-title">{{ Str::limit($item->title, 50) }}</div>
                        @if($item->is_featured)
                            <span class="badge badge-featured">⭐ Featured</span>
                        @endif
                    </td>
                    <td>
                        @if($item->category == 'berita')
                            <span class="badge badge-berita">📰 Berita</span>
                        @else
                            <span class="badge badge-agenda">📅 Agenda</span>
                        @endif
                    </td>
                    <td>
                        @if($item->category == 'agenda' && $item->event_date)
                            <div class="date-info">{{ $item->event_date->format('d M Y') }}</div>
                            @if($item->event_time)
                                <div class="date-time">🕐 {{ \Carbon\Carbon::parse($item->event_time)->format('H:i') }} WITA</div>
                            @endif
                        @else
                            <div class="date-info">{{ $item->published_at ? $item->published_at->format('d M Y') : '-' }}</div>
                            @if($item->published_at)
                                <div class="date-time">{{ $item->published_at->format('H:i') }}</div>
                            @endif
                        @endif
                    </td>
                    <td>
                        @if($item->is_published)
                            <span class="badge badge-published">✅ Published</span>
                        @else
                            <span class="badge badge-draft">📝 Draft</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('news.show', $item->slug) }}" target="_blank" class="action-btn btn-view" title="Lihat">👁️</a>
                            <a href="{{ route('admin.news.edit', $item) }}" class="action-btn btn-edit" title="Edit">✏️</a>
                            <form action="{{ route('admin.news.destroy', $item) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus berita ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn btn-delete" title="Hapus">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr class="empty-row">
                    <td colspan="6">
                        <div style="font-size: 3rem; margin-bottom: 10px;">📭</div>
                        <div>Belum ada berita atau agenda.</div>
                        <a href="{{ route('admin.news.create') }}" style="color: #0693E3; margin-top: 10px; display: inline-block;">+ Tambah Berita Pertama</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination-wrapper">
        {{ $news->links() }}
    </div>
</div>
@endsection

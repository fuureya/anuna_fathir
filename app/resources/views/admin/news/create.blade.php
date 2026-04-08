@extends('layouts.admin')

@section('title', 'Tambah Berita')

@push('styles')
<style>
    .back-link {
        color: #0693E3;
        text-decoration: none;
        display: inline-block;
        margin-bottom: 20px;
    }
    .back-link:hover {
        text-decoration: underline;
    }
    .page-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: #1f2937;
        margin-bottom: 20px;
    }
    .alert-error {
        background: #fee2e2;
        border: 1px solid #ef4444;
        color: #991b1b;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .alert-error ul {
        margin: 0;
        padding-left: 20px;
    }
    .form-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 30px;
        max-width: 900px;
    }
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    .form-group {
        margin-bottom: 0;
    }
    .form-group.full-width {
        grid-column: span 2;
    }
    .form-label {
        display: block;
        font-weight: 500;
        color: #374151;
        margin-bottom: 8px;
        font-size: 14px;
    }
    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.2s, box-shadow 0.2s;
        box-sizing: border-box;
    }
    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #0693E3;
        box-shadow: 0 0 0 3px rgba(6, 147, 227, 0.1);
    }
    .form-hint {
        font-size: 12px;
        color: #6b7280;
        margin-top: 5px;
    }
    .agenda-fields {
        grid-column: span 2;
        display: none;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        padding: 20px;
        background: #f9fafb;
        border-radius: 8px;
        border: 1px dashed #d1d5db;
    }
    .agenda-fields.show {
        display: grid;
    }
    .checkbox-group {
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
    }
    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        font-size: 14px;
        color: #374151;
    }
    .checkbox-label input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }
    .form-actions {
        margin-top: 25px;
        display: flex;
        gap: 15px;
    }
    .btn {
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: 500;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.2s;
    }
    .btn-primary {
        background: #0693E3;
        color: white;
    }
    .btn-primary:hover {
        background: #0574b8;
    }
    .btn-secondary {
        background: #6b7280;
        color: white;
    }
    .btn-secondary:hover {
        background: #4b5563;
    }
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        .form-group.full-width {
            grid-column: span 1;
        }
        .agenda-fields {
            grid-column: span 1;
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="p-6">
    <a href="{{ route('admin.news.index') }}" class="back-link">← Kembali ke Daftar</a>

    <h1 class="page-title">📝 Tambah Berita/Agenda Baru</h1>

    @if($errors->any())
        <div class="alert-error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" class="form-card">
        @csrf

        <div class="form-grid">
            <!-- Judul -->
            <div class="form-group full-width">
                <label class="form-label">Judul *</label>
                <input type="text" name="title" value="{{ old('title') }}" required class="form-input" placeholder="Masukkan judul berita">
            </div>

            <!-- Kategori -->
            <div class="form-group">
                <label class="form-label">Kategori *</label>
                <select name="category" id="categorySelect" required class="form-select">
                    <option value="berita" {{ old('category') == 'berita' ? 'selected' : '' }}>📰 Berita</option>
                    <option value="agenda" {{ old('category') == 'agenda' ? 'selected' : '' }}>📅 Agenda</option>
                </select>
            </div>

            <!-- Gambar -->
            <div class="form-group">
                <label class="form-label">Gambar</label>
                <input type="file" name="image" accept="image/*" class="form-input">
                <p class="form-hint">Format: JPG, PNG, GIF, WebP. Max: 2MB</p>
            </div>

            <!-- Agenda Fields -->
            <div id="agendaFields" class="agenda-fields">
                <div class="form-group">
                    <label class="form-label">📅 Tanggal Event</label>
                    <input type="date" name="event_date" value="{{ old('event_date') }}" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">🕐 Waktu Event</label>
                    <input type="time" name="event_time" value="{{ old('event_time') }}" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">📍 Lokasi Event</label>
                    <input type="text" name="event_location" value="{{ old('event_location') }}" class="form-input" placeholder="Contoh: Aula Perpustakaan">
                </div>
            </div>

            <!-- Excerpt -->
            <div class="form-group full-width">
                <label class="form-label">Ringkasan</label>
                <textarea name="excerpt" rows="2" class="form-textarea" placeholder="Ringkasan singkat berita (maks 500 karakter)">{{ old('excerpt') }}</textarea>
            </div>

            <!-- Content -->
            <div class="form-group full-width">
                <label class="form-label">Konten *</label>
                <textarea name="content" rows="12" required class="form-textarea" placeholder="Tulis konten berita di sini...">{{ old('content') }}</textarea>
            </div>

            <!-- Options -->
            <div class="form-group full-width">
                <div class="checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                        <span>⭐ Jadikan Berita Utama (Featured)</span>
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }}>
                        <span>✅ Publish Sekarang</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Simpan</button>
            <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('categorySelect');
    const agendaFields = document.getElementById('agendaFields');
    
    function toggleAgendaFields() {
        if (categorySelect.value === 'agenda') {
            agendaFields.classList.add('show');
        } else {
            agendaFields.classList.remove('show');
        }
    }
    
    categorySelect.addEventListener('change', toggleAgendaFields);
    toggleAgendaFields();
});
</script>
@endsection

@extends('layouts.admin')

@section('title', 'Edit Buku')

@push('styles')
<style>
    .page-header {
        display: flex;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #667eea;
    }
    
    .page-header h1 {
        color: #2d3748;
        margin: 0;
        font-size: 1.875rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .page-header h1::before {
        content: "📚";
        font-size: 2rem;
    }
    
    .form-container {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.07);
        max-width: 100%;
        overflow: hidden;
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .form-grid-full {
        grid-column: 1 / -1;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
    }
    
    .form-group label {
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #374151;
        font-size: 0.875rem;
    }
    
    .form-group label .required {
        color: #ef4444;
        margin-left: 2px;
    }
    
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 0.625rem 0.875rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.2s;
        font-family: inherit;
    }
    
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .form-group textarea {
        min-height: 120px;
        resize: vertical;
        line-height: 1.5;
    }
    
    .form-group small {
        margin-top: 0.375rem;
        color: #6b7280;
        font-size: 0.75rem;
    }
    
    .cover-section {
        background: #f9fafb;
        padding: 1.25rem;
        border-radius: 8px;
        border: 2px dashed #d1d5db;
        width: 100%;
        box-sizing: border-box;
    }
    
    .current-cover-wrapper {
        margin-bottom: 1rem;
        padding: 1rem;
        background: white;
        border-radius: 8px;
        text-align: center;
        box-sizing: border-box;
    }
    
    .current-cover-wrapper p {
        margin: 0 0 0.75rem 0;
        font-weight: 600;
        color: #374151;
        font-size: 0.875rem;
    }
    
    .current-cover {
        max-width: 200px;
        max-height: 300px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        border: 3px solid #fff;
    }
    
    .cover-options {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .cover-options label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        font-weight: 500;
        color: #374151;
        padding: 0.5rem 0.875rem;
        border-radius: 6px;
        transition: all 0.2s;
    }
    
    .cover-options label:hover {
        background: rgba(102, 126, 234, 0.05);
    }
    
    .cover-options input[type="radio"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: #667eea;
    }
    
    .cover-input-wrapper {
        position: relative;
        width: 100%;
        box-sizing: border-box;
    }
    
    .cover-input-wrapper input[type="file"],
    .cover-input-wrapper input[type="url"] {
        border: 2px dashed #9ca3af;
        background: white;
        cursor: pointer;
        padding: 1rem;
        text-align: center;
        width: 100%;
        box-sizing: border-box;
    }
    
    .cover-input-wrapper input[type="file"]::-webkit-file-upload-button {
        background: #667eea;
        color: white;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        margin-right: 0.75rem;
    }
    
    .cover-input-wrapper input[type="file"]::-webkit-file-upload-button:hover {
        background: #5568d3;
    }
    
    .form-actions {
        display: flex;
        gap: 1rem;
        padding-top: 1.5rem;
        border-top: 2px solid #f3f4f6;
        margin-top: 1.5rem;
        width: 100%;
        box-sizing: border-box;
    }
    
    .btn {
        padding: 0.75rem 2rem;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.9375rem;
        font-weight: 600;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        box-sizing: border-box;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(102, 126, 234, 0.4);
    }
    
    .btn-primary::before {
        content: "💾";
        font-size: 1.125rem;
    }
    
    .btn-secondary {
        background: #6b7280;
        color: white;
    }
    
    .btn-secondary:hover {
        background: #4b5563;
    }
    
    .btn-secondary::before {
        content: "↩️";
        font-size: 1rem;
    }
    
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .page-header h1 {
            font-size: 1.5rem;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1>Edit Buku: {{ $book->judul }}</h1>
</div>

<div class="form-container">
    <form action="{{ route('admin.books.update', $book->buku_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-grid">
            <!-- Judul Buku -->
            <div class="form-group form-grid-full">
                <label for="judul">Judul Buku <span class="required">*</span></label>
                <input type="text" id="judul" name="judul" value="{{ old('judul', $book->judul) }}" required>
            </div>

            <!-- Penulis -->
            <div class="form-group">
                <label for="penulis">Penulis <span class="required">*</span></label>
                <input type="text" id="penulis" name="penulis" value="{{ old('penulis', $book->penulis) }}" required>
            </div>

            <!-- Penerbit -->
            <div class="form-group">
                <label for="penerbit">Penerbit <span class="required">*</span></label>
                <input type="text" id="penerbit" name="penerbit" value="{{ old('penerbit', $book->penerbit) }}" required>
            </div>

            <!-- Tahun Terbit -->
            <div class="form-group">
                <label for="tahun_terbit">Tahun Terbit <span class="required">*</span></label>
                <input type="number" id="tahun_terbit" name="tahun_terbit" 
                       value="{{ old('tahun_terbit', $book->tahun_terbit) }}" 
                       min="1900" max="{{ date('Y') }}" required>
            </div>

            <!-- ISBN -->
            <div class="form-group">
                <label for="ISBN">ISBN <span class="required">*</span></label>
                <input type="text" id="ISBN" name="ISBN" value="{{ old('ISBN', $book->ISBN) }}" required>
            </div>

            <!-- Kategori -->
            <div class="form-group">
                <label for="kategori">Kategori <span class="required">*</span></label>
                <select id="kategori" name="kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Novel" {{ old('kategori', $book->kategori) == 'Novel' ? 'selected' : '' }}>Novel</option>
                    <option value="Komik" {{ old('kategori', $book->kategori) == 'Komik' ? 'selected' : '' }}>Komik</option>
                    <option value="Biografi" {{ old('kategori', $book->kategori) == 'Biografi' ? 'selected' : '' }}>Biografi</option>
                    <option value="Ensiklopedia" {{ old('kategori', $book->kategori) == 'Ensiklopedia' ? 'selected' : '' }}>Ensiklopedia</option>
                </select>
            </div>

            <!-- Genre -->
            <div class="form-group">
                <label for="genre">Genre</label>
                <input type="text" id="genre" name="genre" value="{{ old('genre', $book->genre) }}" placeholder="Fiction, Non-Fiction, Education">
            </div>

            <!-- Jumlah Eksemplar -->
            <div class="form-group">
                <label for="jumlah_eksemplar">Jumlah Eksemplar <span class="required">*</span></label>
                <input type="number" id="jumlah_eksemplar" name="jumlah_eksemplar" 
                       value="{{ old('jumlah_eksemplar', $book->jumlah_eksemplar) }}" min="0" required>
            </div>

            <!-- Kategori Usia -->
            <div class="form-group">
                <label for="audience_category">Kategori Usia</label>
                <select id="audience_category" name="audience_category">
                    <option value="">-- Pilih Kategori Usia --</option>
                    <option value="general" {{ old('audience_category', $book->audience_category) == 'general' ? 'selected' : '' }}>General (Umum)</option>
                    <option value="children" {{ old('audience_category', $book->audience_category) == 'children' ? 'selected' : '' }}>Children (Anak-anak)</option>
                    <option value="students" {{ old('audience_category', $book->audience_category) == 'students' ? 'selected' : '' }}>Students (Pelajar)</option>
                </select>
            </div>

            <!-- Deskripsi -->
            <div class="form-group form-grid-full">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" placeholder="Tulis deskripsi singkat tentang buku...">{{ old('deskripsi', $book->deskripsi) }}</textarea>
            </div>

            <!-- Cover Buku -->
            <div class="form-group form-grid-full">
                <label>Cover Buku</label>
                <div class="cover-section">
                    @if($book->cover_image)
                        <div class="current-cover-wrapper">
                            <p>📷 Cover saat ini:</p>
                            <img src="{{ asset('covers/' . $book->cover_image) }}" 
                                 alt="{{ $book->judul }}" 
                                 class="current-cover"
                                 onerror="this.src='https://via.placeholder.com/200x300?text=No+Cover'">
                        </div>
                    @endif
                    
                    <div class="cover-options">
                        <label>
                            <input type="radio" name="cover_type" value="file" checked onchange="toggleCoverInput()"> 
                            📁 Upload File
                        </label>
                        <label>
                            <input type="radio" name="cover_type" value="url" onchange="toggleCoverInput()"> 
                            🔗 URL Gambar
                        </label>
                    </div>
                    
                    <div class="cover-input-wrapper">
                        <input type="file" id="cover_file" name="cover_file" accept="image/*">
                        <input type="url" id="cover_url" name="cover_url" value="{{ old('cover_url') }}" 
                               placeholder="https://example.com/gambar.jpg" style="display: none;">
                    </div>
                    <small>Upload file lokal (JPG, PNG, max 2MB) atau masukkan URL gambar. Biarkan kosong jika tidak ingin mengubah.</small>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Buku</button>
            <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
function toggleCoverInput() {
    const coverType = document.querySelector('input[name="cover_type"]:checked').value;
    const fileInput = document.getElementById('cover_file');
    const urlInput = document.getElementById('cover_url');
    
    if (coverType === 'file') {
        fileInput.style.display = 'block';
        urlInput.style.display = 'none';
        urlInput.value = '';
    } else {
        fileInput.style.display = 'none';
        urlInput.style.display = 'block';
        fileInput.value = '';
    }
}
</script>
@endsection

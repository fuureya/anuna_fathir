@extends('layouts.admin')

@section('title', 'Tambah Buku Baru')

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
        content: "➕";
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
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1>Tambah Buku Baru</h1>
</div>

<div class="form-container">
    <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-grid">
            <!-- Judul Buku -->
            <div class="form-group form-grid-full">
                <label for="judul">Judul Buku <span class="required">*</span></label>
                <input type="text" id="judul" name="judul" value="{{ old('judul') }}" required placeholder="Masukkan judul buku">
            </div>

            <!-- Penulis -->
            <div class="form-group">
                <label for="penulis">Penulis <span class="required">*</span></label>
                <input type="text" id="penulis" name="penulis" value="{{ old('penulis') }}" required placeholder="Nama penulis">
            </div>

            <!-- Penerbit -->
            <div class="form-group">
                <label for="penerbit">Penerbit <span class="required">*</span></label>
                <input type="text" id="penerbit" name="penerbit" value="{{ old('penerbit') }}" required placeholder="Nama penerbit">
            </div>

            <!-- Tahun Terbit -->
            <div class="form-group">
                <label for="tahun_terbit">Tahun Terbit <span class="required">*</span></label>
                <input type="number" id="tahun_terbit" name="tahun_terbit" 
                       value="{{ old('tahun_terbit') }}" 
                       min="1900" max="{{ date('Y') }}" required placeholder="{{ date('Y') }}">
            </div>

            <!-- ISBN -->
            <div class="form-group">
                <label for="ISBN">ISBN <span class="required">*</span></label>
                <input type="text" id="ISBN" name="ISBN" value="{{ old('ISBN') }}" required placeholder="978-xxx-xxx-xxx-x">
            </div>

            <!-- Kategori -->
            <div class="form-group">
                <label for="kategori">Kategori <span class="required">*</span></label>
                <select id="kategori" name="kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Novel" {{ old('kategori') == 'Novel' ? 'selected' : '' }}>Novel</option>
                    <option value="Komik" {{ old('kategori') == 'Komik' ? 'selected' : '' }}>Komik</option>
                    <option value="Biografi" {{ old('kategori') == 'Biografi' ? 'selected' : '' }}>Biografi</option>
                    <option value="Ensiklopedia" {{ old('kategori') == 'Ensiklopedia' ? 'selected' : '' }}>Ensiklopedia</option>
                </select>
            </div>

            <!-- Genre -->
            <div class="form-group">
                <label for="genre">Genre</label>
                <input type="text" id="genre" name="genre" value="{{ old('genre') }}" placeholder="Opsional">
            </div>

            <!-- Jumlah Eksemplar -->
            <div class="form-group">
                <label for="jumlah_eksemplar">Jumlah Eksemplar <span class="required">*</span></label>
                <input type="number" id="jumlah_eksemplar" name="jumlah_eksemplar" 
                       value="{{ old('jumlah_eksemplar', 1) }}" min="0" required>
            </div>

            <!-- Kategori Usia -->
            <div class="form-group">
                <label for="audience_category">Kategori Usia</label>
                <input type="text" id="audience_category" name="audience_category" 
                       value="{{ old('audience_category') }}" 
                       placeholder="Contoh: 7-12, 13-17, Dewasa">
            </div>

            <!-- Deskripsi -->
            <div class="form-group form-grid-full">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Deskripsi singkat tentang buku">{{ old('deskripsi') }}</textarea>
            </div>

            <!-- Cover Buku -->
            <div class="form-group form-grid-full">
                <label>Cover Buku</label>
                <div class="cover-upload-section">
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="cover_type" value="file" checked onchange="toggleCoverInput()"> 
                            <span>📁 Upload File</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="cover_type" value="url" onchange="toggleCoverInput()"> 
                            <span>🔗 URL Gambar</span>
                        </label>
                    </div>
                    <input type="file" id="cover_file" name="cover_file" accept="image/*" class="file-input">
                    <input type="url" id="cover_url" name="cover_url" value="{{ old('cover_url') }}" 
                           placeholder="https://example.com/gambar.jpg" class="url-input" style="display: none;">
                    <small class="helper-text">Upload file lokal (JPG, PNG, max 2MB) atau masukkan URL gambar</small>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Buku</button>
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

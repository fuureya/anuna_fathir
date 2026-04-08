@extends('layouts.app-public')

@section('title', 'Koleksi Buku')

@push('styles')
<style>
    * {
        box-sizing: border-box;
    }
    body {
        background-color: #f5f5f5;
    }
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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
        transform: translateY(0);
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
    .no-books-message {
        text-align: center;
        grid-column: 1 / -1;
        padding: 40px;
        font-size: 1.1em;
        color: #666;
    }
</style>
@endpush

@section('content')
<!-- Header Section -->
<div style="text-align: center; padding: 40px 20px 20px;">
    <h2 style="font-size: 2em; color: #0693E3; margin: 0;">Koleksi Buku</h2>
    <p style="font-size: 1.2em; color: #555; margin: 10px 0 20px;">Jelajahi koleksi buku perpustakaan kami</p>
    
    <!-- Filter Toggle Button -->
    <button id="toggleFilter" style="
        background: linear-gradient(135deg, #0693E3, #0056b3);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 25px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(6, 147, 227, 0.3);
        transition: all 0.3s ease;
    " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 15px rgba(6, 147, 227, 0.4)'" 
       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 10px rgba(6, 147, 227, 0.3)'">
        <span id="filterIcon">🔍</span> <span id="filterText">Tampilkan Filter</span>
    </button>
</div>

<!-- Filter Section (Hidden by default) -->
<div id="filterSection" style="
    max-width: 1000px;
    margin: 0 auto 30px;
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    display: none;
    animation: slideDown 0.3s ease;
">
    <form action="{{ route('books.index') }}" method="GET">
        <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
            <!-- Search Input -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                    🔎 Cari Judul Buku
                </label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Masukkan judul buku, pengarang, atau ISBN..." 
                       style="width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px; transition: border-color 0.3s;"
                       onfocus="this.style.borderColor='#0693E3'" 
                       onblur="this.style.borderColor='#e0e0e0'">
            </div>
            
            <!-- Category Checkboxes -->
            <div>
                <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #333;">
                    📚 Kategori Buku
                </label>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px;">
                    <label style="display: flex; align-items: center; padding: 10px; background: #f8f9fa; border-radius: 8px; cursor: pointer; transition: all 0.3s;"
                           onmouseover="this.style.background='#e3f2fd'" 
                           onmouseout="this.style.background='#f8f9fa'">
                        <input type="checkbox" name="category[]" value="Novel" 
                            {{ in_array('Novel', (array)request('category')) ? 'checked' : '' }}
                            style="margin-right: 8px; width: 18px; height: 18px; cursor: pointer;">
                        <span style="font-size: 14px;">Novel</span>
                    </label>
                    <label style="display: flex; align-items: center; padding: 10px; background: #f8f9fa; border-radius: 8px; cursor: pointer; transition: all 0.3s;"
                           onmouseover="this.style.background='#e3f2fd'" 
                           onmouseout="this.style.background='#f8f9fa'">
                        <input type="checkbox" name="category[]" value="Komik" 
                            {{ in_array('Komik', (array)request('category')) ? 'checked' : '' }}
                            style="margin-right: 8px; width: 18px; height: 18px; cursor: pointer;">
                        <span style="font-size: 14px;">Komik</span>
                    </label>
                    <label style="display: flex; align-items: center; padding: 10px; background: #f8f9fa; border-radius: 8px; cursor: pointer; transition: all 0.3s;"
                           onmouseover="this.style.background='#e3f2fd'" 
                           onmouseout="this.style.background='#f8f9fa'">
                        <input type="checkbox" name="category[]" value="Biografi" 
                            {{ in_array('Biografi', (array)request('category')) ? 'checked' : '' }}
                            style="margin-right: 8px; width: 18px; height: 18px; cursor: pointer;">
                        <span style="font-size: 14px;">Biografi</span>
                    </label>
                    <label style="display: flex; align-items: center; padding: 10px; background: #f8f9fa; border-radius: 8px; cursor: pointer; transition: all 0.3s;"
                           onmouseover="this.style.background='#e3f2fd'" 
                           onmouseout="this.style.background='#f8f9fa'">
                        <input type="checkbox" name="category[]" value="Ensiklopedia" 
                            {{ in_array('Ensiklopedia', (array)request('category')) ? 'checked' : '' }}
                            style="margin-right: 8px; width: 18px; height: 18px; cursor: pointer;">
                        <span style="font-size: 14px;">Ensiklopedia</span>
                    </label>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div style="display: flex; gap: 10px; margin-top: 20px; justify-content: center;">
            <button type="submit" style="
                background: linear-gradient(135deg, #28a745, #20c997);
                color: white;
                border: none;
                padding: 12px 30px;
                border-radius: 25px;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
                transition: all 0.3s ease;
            " onmouseover="this.style.transform='translateY(-2px)'" 
               onmouseout="this.style.transform='translateY(0)'">
                ✓ Terapkan Filter
            </button>
            <a href="{{ route('books.index') }}" style="
                background: #6c757d;
                color: white;
                border: none;
                padding: 12px 30px;
                border-radius: 25px;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                text-decoration: none;
                display: inline-block;
                box-shadow: 0 4px 10px rgba(108, 117, 125, 0.3);
                transition: all 0.3s ease;
            " onmouseover="this.style.transform='translateY(-2px)'" 
               onmouseout="this.style.transform='translateY(0)'">
                ↻ Reset
            </a>
        </div>
    </form>
</div>

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
        <div class="no-books-message">
            <p>Tidak ada buku yang ditemukan sesuai kriteria pencarian.</p>
        </div>
    @endforelse
</div>

@push('scripts')
<script>
    // Toggle Filter Section
    const toggleBtn = document.getElementById('toggleFilter');
    const filterSection = document.getElementById('filterSection');
    const filterText = document.getElementById('filterText');
    const filterIcon = document.getElementById('filterIcon');
    let isOpen = false;

    toggleBtn.addEventListener('click', function() {
        isOpen = !isOpen;
        if (isOpen) {
            filterSection.style.display = 'block';
            filterText.textContent = 'Sembunyikan Filter';
            filterIcon.textContent = '✕';
        } else {
            filterSection.style.display = 'none';
            filterText.textContent = 'Tampilkan Filter';
            filterIcon.textContent = '🔍';
        }
    });

    // Auto-open filter if there are active filters
    @if(request('search') || request('category'))
        filterSection.style.display = 'block';
        filterText.textContent = 'Sembunyikan Filter';
        filterIcon.textContent = '✕';
        isOpen = true;
    @endif
</script>
@endpush

@endsection

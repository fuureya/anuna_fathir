@extends('layouts.app-public')

@section('title', 'Berikan Ulasan')

@push('styles')
<style>
    .review-container {
        max-width: 800px;
        margin: 40px auto;
        background: white;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .review-header {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #0693E3;
    }
    .review-header h2 {
        font-size: 28px;
        color: #0693E3;
        margin-bottom: 10px;
    }
    .review-header p {
        color: #666;
        font-size: 14px;
    }
    .alert-success {
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        color: white;
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 2px 8px rgba(76, 175, 80, 0.3);
    }
    .alert-error {
        background: #f8d7da;
        color: #721c24;
        padding: 15px 20px;
        border-radius: 10px;
        border-left: 4px solid #dc3545;
        margin-bottom: 25px;
    }
    .alert-error ul {
        margin: 10px 0 0 20px;
        padding: 0;
    }
    .alert-error li {
        margin: 5px 0;
    }
    .form-group {
        margin-bottom: 25px;
    }
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }
    .form-group label .required {
        color: #dc3545;
        margin-left: 3px;
    }
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        resize: vertical;
        transition: all 0.3s ease;
    }
    .form-group textarea:focus {
        outline: none;
        border-color: #0693E3;
        box-shadow: 0 0 0 3px rgba(6, 147, 227, 0.1);
    }
    .form-group .help-text {
        font-size: 12px;
        color: #666;
        margin-top: 5px;
        font-style: italic;
    }
    .submit-btn {
        background: linear-gradient(135deg, #0693E3 0%, #0056b3 100%);
        color: white;
        padding: 14px 40px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(6, 147, 227, 0.3);
        width: 100%;
    }
    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(6, 147, 227, 0.4);
    }
    .info-box {
        background: #E3F2FD;
        border-left: 4px solid #0693E3;
        padding: 15px 20px;
        margin-bottom: 25px;
        border-radius: 8px;
    }
    .info-box h4 {
        color: #0693E3;
        margin: 0 0 10px 0;
        font-size: 14px;
        font-weight: 600;
    }
    .info-box ul {
        margin: 0;
        padding-left: 20px;
        color: #555;
        font-size: 13px;
    }
    .info-box li {
        margin: 5px 0;
    }
</style>
@endpush

@section('content')
<div class="review-container">
    <div class="review-header">
        <h2>⭐ Berikan Ulasan Anda</h2>
        <p>Bantu kami meningkatkan layanan perpustakaan dengan memberikan ulasan Anda</p>
    </div>

    @if (session('status'))
        <div class="alert-success">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert-error">
            <strong>⚠ Terjadi kesalahan:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="info-box">
        <h4>ℹ Panduan Mengisi Ulasan:</h4>
        <ul>
            <li>Berikan ulasan yang jujur dan konstruktif</li>
            <li>Jelaskan pengalaman Anda secara detail</li>
            <li>Ulasan Anda akan membantu meningkatkan kualitas layanan</li>
        </ul>
    </div>

    <form action="{{ route('reviews.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="service_quality">
                Kualitas Pelayanan
                <span class="required">*</span>
            </label>
            <textarea 
                name="service_quality" 
                id="service_quality"
                rows="4" 
                required 
                placeholder="Bagaimana pendapat Anda tentang kualitas pelayanan yang diberikan? (Contoh: ramah, responsif, profesional)">{{ old('service_quality') }}</textarea>
            <div class="help-text">Ceritakan pengalaman Anda dengan petugas perpustakaan</div>
        </div>

        <div class="form-group">
            <label for="book_availability">
                Ketersediaan Buku
                <span class="required">*</span>
            </label>
            <textarea 
                name="book_availability" 
                id="book_availability"
                rows="4" 
                required 
                placeholder="Apakah buku yang Anda cari tersedia? Bagaimana kemudahan menemukannya?">{{ old('book_availability') }}</textarea>
            <div class="help-text">Beri tahu kami tentang ketersediaan buku yang Anda butuhkan</div>
        </div>

        <div class="form-group">
            <label for="book_collection">
                Koleksi Buku
                <span class="required">*</span>
            </label>
            <textarea 
                name="book_collection" 
                id="book_collection"
                rows="4" 
                required 
                placeholder="Bagaimana pendapat Anda tentang variasi dan kualitas koleksi buku?">{{ old('book_collection') }}</textarea>
            <div class="help-text">Sampaikan saran untuk koleksi buku yang ingin ditambahkan</div>
        </div>

        <button type="submit" class="submit-btn">📝 Kirim Ulasan</button>
    </form>
</div>
@endsection

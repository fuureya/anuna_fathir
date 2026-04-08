@extends('layouts.app-public')

@section('content')
<style>
    .success-container {
        max-width: 600px;
        margin: 60px auto;
        padding: 40px 30px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        text-align: center;
    }
    
    .success-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 30px;
        background: linear-gradient(135deg, #0693E3 0%, #0056b3 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: scaleIn 0.5s ease-out;
    }
    
    .success-icon svg {
        width: 50px;
        height: 50px;
        stroke: white;
        stroke-width: 3;
        fill: none;
        stroke-linecap: round;
        stroke-linejoin: round;
        animation: checkmark 0.8s ease-out 0.3s;
    }
    
    @keyframes scaleIn {
        0% {
            transform: scale(0);
            opacity: 0;
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }
    
    @keyframes checkmark {
        0% {
            stroke-dasharray: 0, 100;
        }
        100% {
            stroke-dasharray: 100, 100;
        }
    }
    
    .success-title {
        font-size: 28px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 15px;
    }
    
    .success-message {
        font-size: 16px;
        color: #666;
        margin-bottom: 30px;
        line-height: 1.6;
    }
    
    .info-box {
        background: #f8f9fa;
        border-left: 4px solid #0693E3;
        padding: 20px;
        margin: 30px 0;
        border-radius: 8px;
        text-align: left;
    }
    
    .info-box h4 {
        margin: 0 0 10px 0;
        font-size: 16px;
        color: #2c3e50;
        font-weight: 600;
    }
    
    .info-box ul {
        margin: 0;
        padding-left: 20px;
        color: #666;
    }
    
    .info-box li {
        margin-bottom: 8px;
        line-height: 1.5;
    }
    
    .action-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 30px;
    }
    
    .btn {
        padding: 14px 30px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #0693E3 0%, #0056b3 100%);
        color: white;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(6, 147, 227, 0.4);
    }
    
    .btn-secondary {
        background: white;
        color: #0693E3;
        border: 2px solid #0693E3;
    }
    
    .btn-secondary:hover {
        background: #f0f8ff;
        transform: translateY(-2px);
    }
    
    @media (max-width: 600px) {
        .success-container {
            margin: 30px 15px;
            padding: 30px 20px;
        }
        
        .success-title {
            font-size: 24px;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="success-container">
    <div class="success-icon">
        <svg viewBox="0 0 24 24">
            <path d="M20 6L9 17l-5-5"/>
        </svg>
    </div>
    
    <h1 class="success-title">✅ Reservasi Berhasil Dikirim!</h1>
    
    <p class="success-message">
        Terima kasih atas reservasi Anda. Permohonan Anda telah kami terima dan sedang menunggu persetujuan dari admin.
    </p>
    
    <div class="info-box">
        <h4>📋 Langkah Selanjutnya:</h4>
        <ul>
            <li>Admin akan meninjau reservasi Anda dalam 1-2 hari kerja</li>
            <li>Anda akan menerima <strong>notifikasi email</strong> setelah reservasi disetujui</li>
            <li>Email berisi detail reservasi dan <strong>QR Code</strong> untuk verifikasi</li>
            <li>Anda dapat memantau status reservasi di halaman <strong>"Reservasi Saya"</strong></li>
        </ul>
    </div>
    
    <div class="action-buttons">
        <a href="{{ route('reservations.my') }}" class="btn btn-primary">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            Lihat Reservasi Saya
        </a>
        
        <a href="{{ route('home') }}" class="btn btn-secondary">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Kembali ke Beranda
        </a>
    </div>
</div>

<!-- Auto-show toast notification -->
<script>
    // Toast notification
    (function() {
        const toast = document.createElement('div');
        toast.innerHTML = `
            <div style="position: fixed; top: 20px; right: 20px; z-index: 9999; 
                        background: linear-gradient(135deg, #10b981 0%, #059669 100%); 
                        color: white; padding: 16px 24px; border-radius: 12px; 
                        box-shadow: 0 10px 40px rgba(16, 185, 129, 0.4);
                        display: flex; align-items: center; gap: 12px;
                        animation: slideIn 0.5s ease-out, slideOut 0.5s ease-out 4.5s;
                        max-width: 400px;">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div style="flex: 1;">
                    <div style="font-weight: 600; margin-bottom: 4px;">Sukses!</div>
                    <div style="font-size: 14px; opacity: 0.95;">Reservasi berhasil dikirim. Email notifikasi akan dikirim setelah disetujui.</div>
                </div>
            </div>
        `;
        
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(400px);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
        document.body.appendChild(toast);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.remove();
        }, 5000);
    })();
</script>
@endsection

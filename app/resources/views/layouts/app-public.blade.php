<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perpustakaan Keliling')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#0693E3">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Perpustakaan">
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background-color: #f5f5f5;
            color: #333;
        }
        /* Header Styles */
        header {
            background: linear-gradient(135deg, #0693E3 0%, #0056b3 100%);
            padding: 15px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        header .logo {
            height: 50px;
            cursor: pointer;
        }
        .header-search {
            flex: 1;
            max-width: 500px;
            margin: 0 30px;
        }
        .header-search input {
            width: 100%;
            padding: 10px 15px;
            border: none;
            border-radius: 25px;
            font-size: 14px;
            outline: none;
        }
        .header-links {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        .nav-menu {
            display: flex;
            gap: 20px;
            margin-right: 20px;
        }
        .nav-menu a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .nav-menu a:hover {
            background: rgba(255,255,255,0.2);
        }
        /* Dropdown Menu */
        .nav-dropdown {
            position: relative;
        }
        .nav-dropdown-toggle {
            color: white;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .nav-dropdown-toggle:hover {
            background: rgba(255,255,255,0.2);
        }
        .nav-dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            min-width: 180px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            z-index: 1000;
            overflow: hidden;
            padding-top: 10px;
        }
        .nav-dropdown-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 10px;
            background: transparent;
        }
        .nav-dropdown:hover .nav-dropdown-content {
            display: block;
        }
        .nav-dropdown-content a {
            color: #333 !important;
            padding: 12px 15px !important;
            display: block;
            border-radius: 0 !important;
            font-size: 14px;
            background: white;
        }
        .nav-dropdown-content a:hover {
            background: #f3f4f6 !important;
            color: #0693E3 !important;
        }
        .log-button, .reg-button {
            padding: 8px 20px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .log-button {
            background: white;
            color: #0693E3;
        }
        .log-button:hover {
            background: #f0f0f0;
        }
        .reg-button {
            background: #28a745;
            color: white;
        }
        .reg-button:hover {
            background: #218838;
        }
        /* Main Content */
        main {
            min-height: calc(100vh - 200px);
        }
        /* Footer Styles */
        footer {
            background: linear-gradient(135deg, #0056b3 0%, #003d82 100%);
            color: white;
            padding: 30px;
            margin-top: 50px;
        }
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .footer-logo img {
            height: 40px;
        }
        .footer-links p {
            margin: 0;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header>
        <a href="{{ route('home') }}">
            <img src="{{ asset('logo.png') }}" alt="Library Logo" class="logo">        </a>
        <div class="header-search">
            <form action="{{ route('books.index') }}" method="get">
                <input type="text" name="search" placeholder="Cari Judul/Pengarang/ISBN...">
            </form>
        </div>
        <div class="header-links">
            <div class="nav-menu">
                <!-- Dropdown Aktivitas -->
                <div class="nav-dropdown">
                    <span class="nav-dropdown-toggle">📢 Aktivitas ▾</span>
                    <div class="nav-dropdown-content">
                        <a href="{{ route('news.index') }}">📰 Berita</a>
                        <a href="{{ route('news.agenda') }}">📅 Agenda</a>
                    </div>
                </div>
                <a href="{{ route('books.index') }}">📚 Katalog Buku</a>
                <a href="{{ route('reservations.schedule') }}">📅 Lihat Jadwal</a>
               
                @auth
                    <a href="{{ route('reservations.my') }}">📋 Reservasi Saya</a>
                @endauth
                
            </div>
            @auth
                <a href="{{ route('dashboard') }}" class="log-button">Dashboard</a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.books.index') }}" class="reg-button">Admin</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="log-button">Masuk</a>
                <a href="{{ route('register') }}" class="reg-button">Daftar</a>
            @endauth
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="footer-logo">
                <img src="{{ asset('logo.png') }}" alt="Logo Perpustakaan">
            </div>
            <div class="footer-links">
                <p>&copy; {{ date('Y') }} Perpustakaan Keliling. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
    
    <!-- PWA Install Prompt & Service Worker -->
    <script>
        // Register Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(registration => {
                        console.log('Service Worker registered:', registration);
                    })
                    .catch(error => {
                        console.log('Service Worker registration failed:', error);
                    });
            });
        }

        // PWA Install Prompt
        let deferredPrompt;
        const installButton = document.createElement('button');
        installButton.innerHTML = '📱 Install App';
        installButton.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: linear-gradient(135deg, #0693E3 0%, #0056b3 100%);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            display: none;
            z-index: 9999;
            font-size: 14px;
        `;

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            
            // Tampilkan tombol install
            document.body.appendChild(installButton);
            installButton.style.display = 'block';
        });

        installButton.addEventListener('click', async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                console.log(`User response: ${outcome}`);
                deferredPrompt = null;
                installButton.style.display = 'none';
            }
        });

        // Hide button after installation
        window.addEventListener('appinstalled', () => {
            installButton.style.display = 'none';
            deferredPrompt = null;
            console.log('PWA installed successfully');
        });
    </script>
</body>
</html>

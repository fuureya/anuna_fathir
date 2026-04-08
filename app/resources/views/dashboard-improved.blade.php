<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <style>
        /* Modern Dashboard Styles */
        .dashboard-container {
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            min-height: 100vh;
            padding: 2.5rem 0;
        }

        .stat-card {
            background: white;
            border-radius: 14px;
            padding: 1.5rem;
            box-shadow: 0 3px 5px rgba(0,0,0,0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(255,255,255,0.8);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--card-color-1), var(--card-color-2));
        }

        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.12);
        }

        .stat-card.blue {
            --card-color-1: #667eea;
            --card-color-2: #764ba2;
        }

        .stat-card.green {
            --card-color-1: #48bb78;
            --card-color-2: #38a169;
        }

        .stat-card.yellow {
            --card-color-1: #ed8936;
            --card-color-2: #f6ad55;
        }

        .stat-card.pink {
            --card-color-1: #f5576c;
            --card-color-2: #ff6b81;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            transition: transform 0.3s;
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.08) rotate(3deg);
        }

        .stat-icon.blue {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stat-icon.green {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        }

        .stat-icon.yellow {
            background: linear-gradient(135deg, #ed8936 0%, #f6ad55 100%);
        }

        .stat-icon.pink {
            background: linear-gradient(135deg, #f5576c 0%, #ff6b81 100%);
        }

        .stat-number {
            font-size: 2.25rem;
            font-weight: 800;
            background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .menu-card {
            background: white;
            border-radius: 14px;
            padding: 1.5rem;
            box-shadow: 0 3px 5px rgba(0,0,0,0.06);
            border: 1px solid rgba(255,255,255,0.8);
        }

        .menu-grid {
            display: grid;
            gap: 0.875rem;
        }

        @media (max-width: 767px) {
            .menu-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (min-width: 768px) {
            .menu-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .menu-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .menu-item {
            background: white;
            border-radius: 10px;
            padding: 0.875rem 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid transparent;
            box-shadow: 0 2px 3px rgba(0,0,0,0.04);
            display: flex;
            align-items: center;
            min-height: 76px;
        }

        .menu-item:hover {
            transform: translateX(6px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }

        .menu-item.blue:hover {
            border-color: #667eea;
            background: linear-gradient(135deg, #f0f4ff 0%, #e6ebff 100%);
        }

        .menu-item.green:hover {
            border-color: #48bb78;
            background: linear-gradient(135deg, #f0fff4 0%, #d9f7e8 100%);
        }

        .menu-item.yellow:hover {
            border-color: #ed8936;
            background: linear-gradient(135deg, #fffaf0 0%, #feebc8 100%);
        }

        .menu-item.purple:hover {
            border-color: #9f7aea;
            background: linear-gradient(135deg, #faf5ff 0%, #e9d8fd 100%);
        }

        .menu-item.indigo:hover {
            border-color: #667eea;
            background: linear-gradient(135deg, #f0f4ff 0%, #dfe6ff 100%);
        }

        .menu-item.pink:hover {
            border-color: #f5576c;
            background: linear-gradient(135deg, #fff5f7 0%, #ffe4e9 100%);
        }

        .menu-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            transition: transform 0.3s;
            flex-shrink: 0;
        }

        .menu-item:hover .menu-icon {
            transform: scale(1.12);
        }

        .menu-content {
            margin-left: 0.875rem;
            flex: 1;
            min-width: 0;
        }

        .menu-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 0.125rem;
            line-height: 1.3;
        }

        .menu-subtitle {
            font-size: 0.75rem;
            color: #718096;
            line-height: 1.2;
        }

        .welcome-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 18px;
            padding: 2.25rem;
            color: white;
            margin-bottom: 2rem;
            box-shadow: 0 6px 14px rgba(102, 126, 234, 0.25);
            position: relative;
            overflow: hidden;
        }

        .welcome-section::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            height: 300px;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,165.3C384,171,480,149,576,128C672,107,768,85,864,90.7C960,96,1056,128,1152,138.7C1248,149,1344,139,1392,133.3L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat;
            opacity: 0.5;
        }

        .welcome-title {
            font-size: 1.875rem;
            font-weight: 800;
            margin-bottom: 0.625rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: relative;
            z-index: 1;
        }

        .welcome-text {
            font-size: 1.0625rem;
            opacity: 0.95;
            position: relative;
            z-index: 1;
        }

        .section-title {
            font-size: 1.375rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }

        .section-title::before {
            content: "";
            width: 4px;
            height: 28px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 4px;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(25px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeInUp 0.5s ease-out forwards;
        }

        .alert-custom {
            border-radius: 10px;
            padding: 1.125rem 1.375rem;
            box-shadow: 0 3px 5px rgba(0,0,0,0.06);
            margin-bottom: 1.75rem;
        }

        .new-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: linear-gradient(135deg, #f5576c 0%, #ff6b81 100%);
            color: white;
            font-size: 0.65rem;
            padding: 3px 7px;
            border-radius: 10px;
            font-weight: 700;
            box-shadow: 0 2px 6px rgba(245, 87, 108, 0.35);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Compact Stats Grid */
        .stats-grid {
            display: grid;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        @media (min-width: 640px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
    </style>

    <div class="dashboard-container">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Alert -->
            @if (session('success'))
                <div class="alert-custom bg-green-100 border-l-4 border-green-500 text-green-700 animate-fade-in" role="alert">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="font-semibold">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Error Alert -->
            @if (session('error'))
                <div class="alert-custom bg-red-100 border-l-4 border-red-500 text-red-700 animate-fade-in" role="alert">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="font-semibold">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if(auth()->user()->role === 'admin')
                <!-- Admin Dashboard -->
                <div class="welcome-section animate-fade-in">
                    <div class="welcome-title">👋 Selamat Datang, Administrator!</div>
                    <p class="welcome-text">Kelola sistem perpustakaan keliling melalui menu di bawah ini</p>
                </div>

                <!-- Statistics Cards -->
                <div class="stats-grid animate-fade-in" style="animation-delay: 0.1s;">
                    <!-- Total Books -->
                    <div class="stat-card blue">
                        <div class="flex items-start justify-between mb-3">
                            <div class="stat-icon blue">
                                <span style="color: white;">📚</span>
                            </div>
                        </div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Total Buku</p>
                        <p class="stat-number">{{ \App\Models\Book::count() }}</p>
                    </div>

                    <!-- Total Users -->
                    <div class="stat-card green">
                        <div class="flex items-start justify-between mb-3">
                            <div class="stat-icon green">
                                <span style="color: white;">👥</span>
                            </div>
                        </div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Total Pengguna</p>
                        <p class="stat-number">{{ \App\Models\User::count() }}</p>
                    </div>

                    <!-- Pending Reservations -->
                    <div class="stat-card yellow">
                        <div class="flex items-start justify-between mb-3">
                            <div class="stat-icon yellow">
                                <span style="color: white;">⏳</span>
                            </div>
                        </div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Reservasi Pending</p>
                        <p class="stat-number">{{ \App\Models\Reservation::where('status', 'pending')->count() }}</p>
                    </div>

                    <!-- Today's Reservations -->
                    <div class="stat-card pink">
                        <div class="flex items-start justify-between mb-3">
                            <div class="stat-icon pink">
                                <span style="color: white;">🚌</span>
                            </div>
                        </div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Jadwal Hari Ini</p>
                        <p class="stat-number">{{ \App\Models\Reservation::where('reservation_date', today())->where('status', 'confirmed')->count() }}</p>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="menu-card animate-fade-in" style="animation-delay: 0.2s;">
                    <h4 class="section-title">Menu Admin</h4>
                    <div class="menu-grid">
                        <!-- Manage Books -->
                        <a href="{{ route('admin.books.index') }}" class="menu-item blue">
                            <div class="menu-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <span style="color: white; font-size: 1.25rem;">📚</span>
                            </div>
                            <div class="menu-content">
                                <h5 class="menu-title">Kelola Buku</h5>
                                <p class="menu-subtitle">Tambah, edit, hapus buku</p>
                            </div>
                        </a>

                        <!-- Manage Users -->
                        <a href="{{ route('admin.users.index') }}" class="menu-item green">
                            <div class="menu-icon" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);">
                                <span style="color: white; font-size: 1.25rem;">👥</span>
                            </div>
                            <div class="menu-content">
                                <h5 class="menu-title">Kelola Pengguna</h5>
                                <p class="menu-subtitle">Manage user accounts</p>
                            </div>
                        </a>

                        <!-- Manage Reservations -->
                        <a href="{{ route('admin.reservations.index') }}" class="menu-item yellow">
                            <div class="menu-icon" style="background: linear-gradient(135deg, #ed8936 0%, #f6ad55 100%);">
                                <span style="color: white; font-size: 1.25rem;">📋</span>
                            </div>
                            <div class="menu-content">
                                <h5 class="menu-title">Kelola Reservasi</h5>
                                <p class="menu-subtitle">Review & approve reservations</p>
                            </div>
                        </a>

                        <!-- Manage Reviews -->
                        <a href="{{ route('admin.reviews.index') }}" class="menu-item purple">
                            <div class="menu-icon" style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%);">
                                <span style="color: white; font-size: 1.25rem;">⭐</span>
                            </div>
                            <div class="menu-content">
                                <h5 class="menu-title">Kelola Ulasan</h5>
                                <p class="menu-subtitle">Moderate book reviews</p>
                            </div>
                        </a>

                        <!-- Manage Schedule -->
                        <a href="{{ route('admin.schedule.index') }}" class="menu-item indigo">
                            <div class="menu-icon" style="background: linear-gradient(135deg, #5a67d8 0%, #4c51bf 100%);">
                                <span style="color: white; font-size: 1.25rem;">📅</span>
                            </div>
                            <div class="menu-content">
                                <h5 class="menu-title">Jadwal Pusling</h5>
                                <p class="menu-subtitle">Generate library schedules</p>
                            </div>
                        </a>

                        <!-- Bus Tracking Control -->
                        <a href="{{ route('admin.bus.control') }}" class="menu-item pink" style="position: relative;">
                            <div class="menu-icon" style="background: linear-gradient(135deg, #f5576c 0%, #ff6b81 100%);">
                                <span style="color: white; font-size: 1.25rem;">🚌</span>
                            </div>
                            <div class="menu-content">
                                <h5 class="menu-title">Kontrol Bus</h5>
                                <p class="menu-subtitle">Update status & lokasi bus</p>
                            </div>
                            <span class="new-badge">NEW</span>
                        </a>

                        <!-- Public Bus Tracking View -->
                        <a href="{{ route('bus.tracking') }}" class="menu-item pink" target="_blank">
                            <div class="menu-icon" style="background: linear-gradient(135deg, #f5576c 0%, #ff6b81 100%);">
                                <span style="color: white; font-size: 1.25rem;">📍</span>
                            </div>
                            <div class="menu-content">
                                <h5 class="menu-title">Lihat Tracking</h5>
                                <p class="menu-subtitle">Public tracking view</p>
                            </div>
                        </a>
                    </div>
                </div>

            @else
                <!-- Regular User Dashboard -->
                <div class="welcome-section animate-fade-in">
                    <div class="welcome-title">👋 Selamat Datang, {{ auth()->user()->fullname }}!</div>
                    <p class="welcome-text">Anda berhasil login ke sistem perpustakaan keliling</p>
                </div>

                <div class="menu-card animate-fade-in" style="animation-delay: 0.15s;">
                    <h4 class="section-title">Menu Pengguna</h4>
                    <div class="menu-grid">
                        <a href="{{ route('books.index') }}" class="menu-item blue">
                            <div class="menu-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <span style="color: white; font-size: 1.25rem;">📚</span>
                            </div>
                            <div class="menu-content">
                                <h5 class="menu-title">Koleksi Buku</h5>
                                <p class="menu-subtitle">Jelajahi katalog buku</p>
                            </div>
                        </a>

                        <a href="{{ route('reservations.create') }}" class="menu-item green">
                            <div class="menu-icon" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);">
                                <span style="color: white; font-size: 1.25rem;">📝</span>
                            </div>
                            <div class="menu-content">
                                <h5 class="menu-title">Buat Reservasi</h5>
                                <p class="menu-subtitle">Buat reservasi baru</p>
                            </div>
                        </a>
                        
                        <a href="{{ route('reservations.my') }}" class="menu-item purple">
                            <div class="menu-icon" style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%);">
                                <span style="color: white; font-size: 1.25rem;">📋</span>
                            </div>
                            <div class="menu-content">
                                <h5 class="menu-title">Reservasi Saya</h5>
                                <p class="menu-subtitle">Lihat status reservasi</p>
                            </div>
                        </a>

                        <a href="{{ route('reviews.create') }}" class="menu-item yellow">
                            <div class="menu-icon" style="background: linear-gradient(135deg, #ed8936 0%, #f6ad55 100%);">
                                <span style="color: white; font-size: 1.25rem;">⭐</span>
                            </div>
                            <div class="menu-content">
                                <h5 class="menu-title">Berikan Ulasan</h5>
                                <p class="menu-subtitle">Beri ulasan layanan</p>
                            </div>
                        </a>

                        <a href="{{ route('bus.tracking') }}" class="menu-item pink">
                            <div class="menu-icon" style="background: linear-gradient(135deg, #f5576c 0%, #ff6b81 100%);">
                                <span style="color: white; font-size: 1.25rem;">🚌</span>
                            </div>
                            <div class="menu-content">
                                <h5 class="menu-title">Tracking Bus</h5>
                                <p class="menu-subtitle">Pantau lokasi bus real-time</p>
                            </div>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

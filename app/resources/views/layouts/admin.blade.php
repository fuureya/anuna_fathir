<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#0693E3">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    
    <style>
        /* General Styling */
        body {
            font-family: 'Mulish', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        /* Container */
        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        .sidebar {
            background-color: #0693E3;
            color: #fff;
            width: 250px;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 10px 0;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .sidebar ul li a:hover {
            background-color: #495057;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 30px;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
            min-height: 100vh;
        }

        .main-content h1 {
            color: #2d3748;
            font-size: 32px;
            margin-bottom: 24px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .main-content p {
            margin-bottom: 20px;
            color: #4a5568;
            line-height: 1.6;
        }

        /* Card Container */
        .p-6 {
            background: white;
            padding: 32px;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Text Styles */
        .text-2xl {
            font-size: 28px;
            font-weight: 700;
            color: #2d3748;
        }

        .text-gray-500 {
            color: #718096;
        }

        /* Alert/Success Message */
        .bg-green-100 {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            padding: 16px 20px;
            border-radius: 12px;
            border-left: 4px solid #48bb78;
            box-shadow: 0 2px 4px rgba(72, 187, 120, 0.1);
        }

        .text-green-700 {
            color: #065f46;
            font-weight: 600;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            overflow: hidden;
        }

        table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
        }

        table thead th {
            padding: 16px 12px;
            text-align: left;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table tbody td {
            padding: 14px 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
            color: #4a5568;
        }

        table tbody tr {
            transition: all 0.3s;
        }

        table tbody tr:hover {
            background-color: #f7fafc;
            transform: scale(1.01);
        }

        table tbody tr:last-child td {
            border-bottom: none;
        }

        table tbody td a {
            color: #667eea;
            text-decoration: none;
            margin-right: 10px;
            font-weight: 600;
            transition: color 0.3s;
        }

        table tbody td a:hover {
            color: #5568d3;
            text-decoration: underline;
        }

        /* Button Styling */
        .btn {
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-family: 'Mulish', sans-serif;
            font-size: small;
            transition: background-color 0.3s;
            width: auto;
            height: auto;
            display: inline-block;
            margin: 2px 0;
        }

        .edit-btn {
            background-color: #5cb85c;
            color: white;
            border-color: #4cae4c;
        }

        .edit-btn:hover {
            background-color: darkgreen;
        }

        .delete-btn {
            background-color: #d9534f;
            color: white;
            border-color: #d43f3a;
        }

        .delete-btn:hover {
            background-color: darkred;
        }

        /* Modern Button Styles */
        button, .btn, input[type="submit"], a.btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Mulish', sans-serif;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-decoration: none;
        }

        button:hover, .btn:hover, input[type="submit"]:hover, a.btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        button:active, .btn:active, input[type="submit"]:active, a.btn:active {
            transform: translateY(0);
        }

        /* Blue Button */
        .bg-blue-600, button.bg-blue-600 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .bg-blue-600:hover, button.bg-blue-600:hover {
            background: linear-gradient(135deg, #5568d3 0%, #63398c 100%);
        }

        /* Gray/Preview Button */
        .bg-gray-700, button.bg-gray-700 {
            background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
            color: white;
        }

        .bg-gray-700:hover, button.bg-gray-700:hover {
            background: linear-gradient(135deg, #3a4555 0%, #1a202c 100%);
        }

        /* Green/Commit Button */
        .bg-green-600, button.bg-green-600 {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
        }

        .bg-green-600:hover, button.bg-green-600:hover {
            background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
        }

        /* Input Date Styling */
        input[type="date"], input.border {
            padding: 10px 14px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
            background: white;
            font-family: 'Mulish', sans-serif;
        }

        input[type="date"]:focus, input.border:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        /* Link Button */
        a.text-blue-600 {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 8px;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        a.text-blue-600:hover {
            background: rgba(102, 126, 234, 0.1);
            color: #5568d3;
        }

        a.text-blue-600.underline {
            text-decoration: none;
        }

        /* Form Container */
        form.mb-4, form {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        form label {
            font-weight: 600;
            color: #2d3748;
            font-size: 14px;
        }

        /* Flex Gap Fix */
        .flex.items-center.gap-2, .mb-4.flex.items-center.gap-2 {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        /* Button Icons (pseudo elements) */
        button.bg-blue-600::before {
            content: "👁️";
            font-size: 16px;
        }

        button.bg-gray-700::before {
            content: "📋";
            font-size: 16px;
        }

        button.bg-green-600::before {
            content: "✅";
            font-size: 16px;
        }

        /* Responsive Button */
        @media (max-width: 768px) {
            button, .btn, input[type="submit"], a.btn {
                padding: 8px 16px;
                font-size: 13px;
            }
            
            form.mb-4, form {
                gap: 8px;
            }
        }

        /* Divider */
        .divider {
            border: none;
            height: 4px;
            background-color: rgba(255, 255, 255, 0.5);
            margin: 10px 0;
        }

        /* Logo */
        .logo {
            display: block;
            margin: 0 auto 20px;
            width: 100%;
            height: auto;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
            }

            .main-content {
                padding: 15px;
            }

            table tbody td, table thead th {
                font-size: 12px;
                padding: 10px 8px;
            }
            
            .main-content h1 {
                font-size: 24px;
            }
            
            .p-6 {
                padding: 20px;
            }
        }

        /* Overflow Container */
        .overflow-x-auto {
            overflow-x: auto;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .overflow-x-auto.bg-white {
            background: white;
        }

        .overflow-x-auto.shadow {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .overflow-x-auto.rounded {
            border-radius: 12px;
        }

        /* Pagination */
        .mt-4 {
            margin-top: 20px;
        }

        /* Empty State */
        .text-center {
            text-align: center;
        }

        /* Utility Classes */
        .mb-4 {
            margin-bottom: 20px;
        }

        .rounded {
            border-radius: 12px;
        }

        .shadow {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .min-w-full {
            min-width: 100%;
        }

        .text-sm {
            font-size: 14px;
        }

        .text-left {
            text-align: left;
        }

        .px-3 {
            padding-left: 12px;
            padding-right: 12px;
        }

        .py-2 {
            padding-top: 8px;
            padding-bottom: 8px;
        }

        .py-4 {
            padding-top: 16px;
            padding-bottom: 16px;
        }

        .border-t {
            border-top: 1px solid #e2e8f0;
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="container">
    <!-- Sidebar -->
    <nav class="sidebar">
        <img src="{{ asset('logo.png') }}" alt="Logo" class="logo">
        <hr class="divider">
        <ul>
            <li><a href="{{ route('dashboard') }}">🏠 Dashboard</a></li>
            <li><a href="{{ route('admin.books.index') }}">📚 Kelola Buku</a></li>
            <li><a href="{{ route('admin.news.index') }}">📰 Kelola Berita</a></li>
            <li><a href="{{ route('admin.users.index') }}">👥 Kelola Pengguna</a></li>
            <li><a href="{{ route('admin.reservations.index') }}">🗂 Kelola Reservasi</a></li>
            <li><a href="{{ route('qr.scan') }}">🔍 Scan QR Code</a></li>
            <li><a href="{{ route('admin.reviews.index') }}">⭐ Kelola Ulasan</a></li>
            <li><a href="{{ route('admin.schedule.index') }}">📅 Jadwal Pusling</a></li>
            <li><a href="{{ route('home') }}">🌐 Lihat Website</a></li>
        </ul>
        <hr class="divider">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="
                background: none;
                border: none;
                color: white;
                cursor: pointer;
                padding: 10px 20px;
                width: 100%;
                text-align: left;
            ">🚪 Logout</button>
        </form>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        @if(session('success'))
            <div style="
                background-color: #4CAF50;
                color: white;
                padding: 15px;
                margin-bottom: 20px;
                border-radius: 5px;
            ">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div style="
                background-color: #f44336;
                color: white;
                padding: 15px;
                margin-bottom: 20px;
                border-radius: 5px;
            ">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</div>

@stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="css/navbar_loginn.css">
    <style>
        /* Gaya CSS untuk dropdown */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none; /* Sembunyikan dropdown secara default */
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .show {
            display: block; /* Tampilkan dropdown */
        }
    </style>
    <script>
        function toggleDropdown() {
            document.getElementById("user-settings-dropdown").classList.toggle("show");
        }

        window.onclick = function(event) {
            if (!event.target.matches('.dropbtn')) {
                const dropdowns = document.getElementsByClassName("dropdown-content");
                for (let i = 0; i < dropdowns.length; i++) {
                    const openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</head>
<body>
<header>
    <a href="user_dashboard.php">
        <img src="logo.png" alt="Library Logo" class="logo">
    </a>  
    <div class="header-links">
        <li class="search-form">
            <form action="search.php" method="get">
                <input type="text" name="book_title" placeholder="Cari Judul/Pengarang/ISBN...">
            </form>
        </li>
        <a href="view_profile.php">
            <img src="profile.png" alt="View Profile" class="nav-icon">
        </a>
        <div class="dropdown">
            <button class="dropbtn" onclick="toggleDropdown()">
                <img src="gear_icon.png" alt="User Settings">
            </button>
            <div id="user-settings-dropdown" class="dropdown-content">
                <a href="user_settings.php">Settings</a>
                <a href="delete_account.php">Delete Account</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>
</header>
<nav>
    <ul>
        <li><a href="book_collection.php">Koleksi Buku</a></li>
        <li><a href="visitor_reservation.php">Reservasi Kunjungan</a></li>
        <li><a href="e_resources.php">E-Resources</a></li>
        <li><a href="literacy_agenda.php">Agenda Literasi</a></li>
        <li><a href="pusling_schedule.php">Jadwal Pusling</a></li>
        <li><a href="library_location.php">Lokasi Perpustakaan</a></li>
    </ul>
</nav>
</body>
</html>
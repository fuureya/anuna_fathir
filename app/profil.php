<?php
session_start();

if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];
$email = $_SESSION['email']; // Pastikan email disimpan di sesi saat login
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <link rel="stylesheet" href="css_user.css">
</head>
<body>
    <header>
        <img src="logo.png" alt="Library Logo" class="logo">
        <div class="header-links">
            <li class="search-form">
                <form action="search.php" method="get">
                    <input type="text" name="book_title" placeholder="Search Book">
                </form>
            </li>
            <a href="view_profile.php"><img src="profile_icon.png" alt="View Profile" class="nav-icon"></a>
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
            <li><a href="book_collection.php">Book Collection</a></li>
            <li><a href="visitor_reservation.php">Visitor Reservation</a></li>
            <li><a href="e_resources.php">E-Resources</a></li>
            <li><a href="literacy_agenda.php">Literacy Agenda</a></li>
            <li><a href="mobile_schedule.php">Mobile Schedule</a></li>
            <li><a href="library_location.php">Library Location</a></li>
        </ul>
    </nav>
    <section>
        <div class="profile-container">
            <h2>User Profile</h2>
            <ul class="profile-details">
                <li><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></li>
                <li><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></li>
                <!-- Tambahkan detail profil lainnya di sini -->
            </ul>
        </div>
    </section>
    <footer>
        <p>&copy; 2024 Library. All rights reserved.</p>
    </footer>
    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('user-settings-dropdown');
            dropdown.classList.toggle('show');
        }

        window.onclick = function(event) {
            if (!event.target.matches('.dropbtn, .dropbtn img')) {
                const dropdowns = document.getElementsByClassName('dropdown-content');
                for (let i = 0; i < dropdowns.length; i++) {
                    const openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</body>
</html>

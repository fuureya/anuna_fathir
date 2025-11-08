<?php
// Data kegiatan literasi (biasanya data ini akan diambil dari database)
$events = array(
    1 => array(
        "title" => "Book Reading Session",
        "date" => "2024-06-30",
        "description" => "Join us for a book reading session of 'Harry Potter and the Philosopher's Stone'.",
        "image" => "event1.jpg",
        "details" => "This is a detailed description of the Book Reading Session."
    ),
    2 => array(
        "title" => "Author Meet and Greet",
        "date" => "2024-07-05",
        "description" => "Meet your favorite authors and get your books signed.",
        "image" => "event2.jpg",
        "details" => "This is a detailed description of the Author Meet and Greet."
    ),
    3 => array(
        "title" => "Children's Storytelling",
        "date" => "2024-07-10",
        "description" => "A fun storytelling session for kids.",
        "image" => "event3.jpg",
        "details" => "This is a detailed description of the Children's Storytelling event."
    ),
    // tambahkan data kegiatan lainnya sesuai kebutuhan
);

// Ambil ID kegiatan dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Periksa apakah kegiatan dengan ID tersebut ada
if (!isset($events[$id])) {
    echo "<p>Event not found.</p>";
    exit;
}

$event = $events[$id];
include 'navbar_login.php';
?>

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($event['title']); ?></title>
    <link rel="stylesheet" href="css/user.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
        }
        .event-detail {
            width: 60%;
            margin: 0 auto;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
        }
        .event-detail img {
            width: 100%;
            height: auto;
            border-bottom: 1px solid #ccc;
        }
        .event-detail h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .event-detail p {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="event-detail">
        <img src="images/<?php echo htmlspecialchars($event['image']); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>">
        <h1><?php echo htmlspecialchars($event['title']); ?></h1>
        <p><strong>Date:</strong> <?php echo htmlspecialchars($event['date']); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($event['description']); ?></p>
        <p><?php echo htmlspecialchars($event['details']); ?></p>
    </div>
</body>
</html>

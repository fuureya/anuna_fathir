<?php
session_start(); // Pastikan session dimulai

// Data kegiatan literasi (biasanya data ini akan diambil dari database)
$events = array(
    array(
        "id" => 1,
        "title" => "Book Reading Session",
        "date" => "2024-06-30",
        "description" => "Join us for a book reading session of 'Harry Potter and the Philosopher's Stone'.",
        "image" => "event1.jpg"
    ),
    array(
        "id" => 2,
        "title" => "Author Meet and Greet",
        "date" => "2024-07-05",
        "description" => "Meet your favorite authors and get your books signed.",
        "image" => "event2.jpg"
    ),
    array(
        "id" => 3,
        "title" => "Children's Storytelling",
        "date" => "2024-07-10",
        "description" => "A fun storytelling session for kids.",
        "image" => "event3.jpg"
    ),
    // Tambahkan kegiatan lainnya sesuai kebutuhan
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Literacy Agenda</title>
    <link rel="stylesheet" href="css/agenda_literasi.css"> <!-- Tautkan file CSS -->
</head>
<body>
    <?php
    include 'navbar_login.php';
    ?>

    <h1>Literacy Agenda</h1>
    <div class="event-list">
        <?php foreach ($events as $event): ?>
            <div class="event">
                <a href="literacy_detail.php?id=<?php echo htmlspecialchars($event['id']); ?>">
                    <img src="images/<?php echo htmlspecialchars($event['image']); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>">
                    <div class="event-content">
                        <h2><?php echo htmlspecialchars($event['title']); ?></h2>
                        <p class="event-date"><?php echo htmlspecialchars($event['date']); ?></p>
                        <p><?php echo htmlspecialchars($event['description']); ?></p>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>

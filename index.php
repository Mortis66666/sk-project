<!DOCTYPE html>
<html lang="en">

<?php
session_start();
include("database.php");
include("debug.php");
// debug_log($_SESSION['user_id']);
if (!isset($_SESSION['user_id'])) {
    debug_log("User not logged in");
    header("Location: login.php");
    exit();
}
?>

<head>
    <title>Home Menu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style media="screen">
        .create-class-button {
            margin-right: 20px;
        }

        .logout-button {
            margin-left: 20px;
        }
    </style>
</head>

<body>
    <div class="header" id="header">
        <a href="create.php"><button class="create-class-button">Create New Class</button></a>
        <button class="logout-button">Logout</button>
    </div>
    <div class="content" id="content">
        <?php
        $query = sprintf(
            "SELECT id_kelas, nama FROM kelas
            WHERE id_kelas IN (
                SELECT id_kelas FROM kelas_pengguna
                WHERE id_pengguna = %u
            )",
            $_SESSION['user_id']
        );

        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo sprintf("<a href='class.php?id=%u'><button>%s</button></a>", $row['id_kelas'], $row['nama']);
            }
        } else {
            echo "<p>No classes yet, <a href='create.php'>click here</a> to create a class</p>";
        }
        ?>
    </div>
    <button class="dark-mode-toggle" id="darkModeToggle">
        <i class="fa-solid fa-moon fa-2xl" id="icon-toggle"></i>
    </button>

    <script src="script.js"></script>
</body>

</html>
<?php
session_start();
include("database.php");
include("debug.php");
if (!isset($_SESSION['user_id'])) {
    debug_log("User not logged in");
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Create Class</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style media="screen">
        .create-class-form {
            border: 2px #080710 solid;
            border-radius: 10px;
            width: 35%;
            /* Adjust this value to suit your needs */
            padding: 20px;
            box-shadow: 0 0 40px rgba(8, 7, 16, 0.6);
            backdrop-filter: blur(10px);
            margin: 20vh auto 0 auto;
        }

        .create-class-form input[type="text"] {
            width: 80%;
            margin: 10px;
            padding: 10px;
            border-radius: 5px;
            border: 1px #080710 solid;
        }
    </style>
</head>

<body>
    <div class="header" id="header">
        <button class="create-class-button" onclick="history.back()">
            <i class="fa-solid fa-left-long"></i>
            Back
        </button>
        <button class="logout-button">Logout</button>
    </div>

    <div class="create-class-form content">
        <h1>Create Class</h1>
        <form action="create.php" method="post">
            <label for="class-name">Class Name:</label>
            <input type="text" id="class-name" name="class-name" required>
            <br>
            <button type="submit">Create</button>
        </form>
    </div>
</body>

</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_name = $_POST['class-name'];
    $user_id = $_SESSION['user_id'];

    $query = "INSERT INTO kelas (nama) VALUES ('$class_name')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        debug_log("Class created successfully");
        $last_id = mysqli_insert_id($conn);
    } else {
        debug_log("Error creating class: " . mysqli_error($conn));
        die();
    }

    $query = "INSERT INTO kelas_pengguna (id_kelas, id_pengguna, peranan) VALUES ($last_id, $user_id, 'GURU')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        debug_log("User added to class successfully");
    } else {
        debug_log("Error adding user to class: " . mysqli_error($conn));
        die();
    }

    header("Location: index.php");
}

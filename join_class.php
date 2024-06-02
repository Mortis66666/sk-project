<?php
session_start();
include("database.php");
include("debug.php");
include("check_user.php");

$invite = $_POST['invite'];

// Find class
$query = sprintf(
    "SELECT id_kelas FROM kelas
    WHERE invite = '%s'",
    $invite
);

$result = $conn->query($query);
if ($result->num_rows == 0) {
    header("Location: home.php");
    exit();
}

$class_id = $result->fetch_assoc()['id_kelas'];

// Check if user already in class
$query = sprintf(
    "SELECT * FROM kelas_pengguna
    WHERE id_pengguna = %u AND id_kelas = %u",
    $_SESSION['user_id'],
    $class_id
);

$result = $conn->query($query);
if ($result->num_rows > 0) {
    header("Location: class.php?id=" . $class_id);
    exit();
}


$query = sprintf(
    "INSERT INTO kelas_pengguna (id_kelas, id_pengguna)
    VALUES (%u, %u)",
    $class_id,
    $_SESSION['user_id']
);

if (!$conn->query($query)) {
    header("Location: home.php");
    exit();
}

header("Location: class.php?id=" . $class_id);

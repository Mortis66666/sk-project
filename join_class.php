<?php
session_start();
include("database.php");
include("debug.php");
if (!isset($_SESSION['user_id'])) {
    debug_log("User not logged in");
    header("Location: login.php");
    exit();
}

$invite = $_POST['invite'];

// Find class
$query = sprintf(
    "SELECT id_kelas FROM kelas
    WHERE invite = '%s'",
    $invite
);

$result = $conn->query($query);
if ($result->num_rows == 0) {
    header("Location: index.php");
    exit();
}

$class_id = $result->fetch_assoc()['id_kelas'];

// Insert user into class, if error means user already in class
$query = sprintf(
    "INSERT INTO kelas_pengguna (id_kelas, id_pengguna)
    VALUES (%u, %u)",
    $class_id,
    $_SESSION['user_id']
);

if (!$conn->query($query)) {
    header("Location: index.php");
    exit();
}

header("Location: class.php?id=" . $class_id);

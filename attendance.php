<?php
session_start();
include("database.php");
include("debug.php");

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
} else {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: index.php");
    exit();
}

if (!isset($_POST['class_id']) || !isset($_POST['student_id']) || !isset($_POST['action'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing parameters']);
}

header('Content-Type: application/json; charset=utf-8');

$class_id = $_POST['class_id'];
$student_id = $_POST['student_id'];
$action = $_POST['action'];

if ($student_id != $user_id) {
    // Check if user is a teacher
    $sql = "SELECT * FROM kelas_pengguna WHERE id_pengguna=? AND id_kelas=? AND (peranan='GURU' OR peranan='ADMIN')";
    $result = $conn->execute_query($sql, [$user_id, $class_id]);

    if ($result->num_rows == 0) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit();
    }
}

if ($action == "add") {
    // Check if attendace is taken today
    $sql = "SELECT * FROM kehadiran WHERE id_pendaftar=? AND id_kelas=? AND id_pengguna=? AND masa_daftar=CURDATE()";
    $result = $conn->execute_query($sql, [$user_id, $class_id, $student_id]);

    if ($result->num_rows == 0) {
        $sql = "INSERT INTO kehadiran (id_pendaftar, id_kelas, id_pengguna, masa_daftar) VALUES (?, ?, ?, CURDATE())";
        $conn->execute_query($sql, [$user_id, $class_id, $student_id]);

        echo json_encode(['success' => true]);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Attendance already taken']);
    }
} else if ($action == "remove") {
    $class_id = $_POST['class_id'];
    $student_id = $_POST['student_id'];
    $user_id = $_SESSION['user_id'];

    $sql = "DELETE FROM kehadiran WHERE id_kelas=? AND id_pengguna=? AND masa_daftar=CURDATE()";
    $conn->execute_query($sql, [$class_id, $student_id]);

    echo json_encode(['success' => true]);
}

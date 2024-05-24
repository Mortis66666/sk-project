<?php
session_start();
include("database.php");
include("debug.php");

if (!isset($_SESSION['user_id'])) {
    die("You are not logged in");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['class_id']) || !isset($_POST['code'])) {
        die("Missing parameters");
    }

    // Send post request to attendance.php
    $url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/attendance.php";
    $data = [
        'class_id' => $_POST['class_id'],
        'user_id' => $_SESSION['user_id'],
        'student_id' => $_SESSION['user_id'],
        'action' => 'add'
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);
    curl_close($ch);

    if ($result) {
        $data = json_decode($result, true);

        if (isset($data['success'])) {
            $_SESSION['code-result'] = "Attendance successfully taken";
        } else {
            $_SESSION['code-result'] = $data['error'];
        }

        header("Location: class.php?id=" . $_POST['class_id']);
    } else {
        echo "Error";
    }
}

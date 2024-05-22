<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "database";

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function generate_code()
{
    // Create random 6-digit code as string
    $code = "";
    for ($i = 0; $i < 6; $i++) {
        $code .= rand(0, 9);
    }

    return $code;
}

function get_code($class_id)
{
    global $conn;

    $query = "SELECT code, last_update FROM kelas WHERE id_kelas = $class_id"; // last_update is a date type
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);

        // Reset code if last update is more than 1 day ago
        $last_update = strtotime($row['last_update']);
        $current_time = time();
        $diff = $current_time - $last_update;

        if ($diff > 86400) {
            $code = generate_code();
            $query = "UPDATE kelas_pengguna SET code = $code, last_update = NOW() WHERE id_kelas = $class_id";
            $result = mysqli_query($conn, $query);

            if ($result) {
                return $code;
            } else {
                return "Error: " . mysqli_error($conn);
            }
        }

        return $row['code'];
    } else {
        return "Error: " . mysqli_error($conn);
    }
}

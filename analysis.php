<?php
session_start();
include("database.php");
include("debug.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    die();
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (!isset($_GET['cid']) || !isset($_GET['uid'])) {
        header("Location: home.php");
        die();
    }

    $class_id = $_GET['cid'];
    $user_id = $_GET['uid'];

    // Get user name and class name
    $query = "SELECT nama_pengguna FROM pengguna WHERE id_pengguna = $user_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $user_name = $row['nama_pengguna'];
    } else {
        die("Can't find user");
    }

    $query = "SELECT nama FROM kelas WHERE id_kelas = $class_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $class_name = $row['nama'];
    } else {
        die("Can't find class");
    }

    // Get the date when user join this class
    $query = "SELECT tarikh_masuk FROM kelas_pengguna WHERE id_kelas = $class_id AND id_pengguna = $user_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $join_date = $row['tarikh_masuk'];
    } else {
        die("Can't find user in this class");
    }

    $query = "SELECT masa_daftar FROM kehadiran WHERE id_kelas = $class_id AND id_pengguna = $user_id ORDER BY masa_daftar ASC";

    $result = mysqli_query($conn, $query);

    if ($result) {
        $attendance = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $attendance = [];
    }

    // Check how many times the user is present since $join_date
    // $days = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
    $days = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday");
    $day_amount = count($days);
    $attended_data = array_fill(0, $day_amount, 0);
    $close_call = 0;

    foreach ($attendance as $record) {
        $day = date('w', strtotime($record['masa_daftar'])) - 1;

        if ($day < 0 || $day >= $day_amount) {
            continue;
        }

        $attended_data[$day]++;

        // Check if the record is a close call (taken at the last hour of the day)
        $hour = date('H', strtotime($record['masa_daftar']));
        if ($hour == 23) {
            $close_call++;
        }
    }

    $present = array_sum($attended_data);

    // Use statistics to determine the days user ditched
    $mean = array_sum($attended_data) / count($attended_data);
    $variance = array_sum(array_map(function ($x) use ($mean) {
        return pow($x - $mean, 2);
    }, $attended_data)) / count($attended_data);
    $standard_deviation = sqrt($variance);

    $lower_bound = $mean - 2 * $standard_deviation;

    $most_ditched_days = [];

    for ($i = 0; $i < $day_amount; $i++) {
        if ($attended_data[$i] < $lower_bound) {
            $most_ditched_days[] = $days[$i];
        }
    }

    $most_ditched_day = implode(", ", $most_ditched_days);

    $total_days = ceil((time() - strtotime($join_date)) / (60 * 60 * 24));
    $average_per_week = $present / ($total_days / $day_amount);

    $percentage = ($present / $total_days) * 100;
    $percentage = round($percentage, 2);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analysis of <?= $user_name ?></title>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet" type='text/css'>

    <style>
        :root {
            --percentage: <?= $percentage ?>%;
        }

        body {
            text-align: center;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.13);
            border-radius: 10px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 40px rgba(8, 7, 16, 0.6);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
        }

        .header button {
            background-color: #ffffff;
            color: #080710;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .circle {
            width: 100px;
            height: 100px;
            background:
                radial-gradient(closest-side, white 79%, transparent 80% 100%),
                conic-gradient(yellow var(--percentage), yellowgreen 0);
            width: 150px;
            height: 150px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        ul {
            padding: 0;
        }

        li {
            font-size: 18px;
            margin: 2vh 0;
        }
    </style>
</head>

<body>
    <?php include("header.php") ?>

    <div class="container">
        <h1>Attendance analysis of <?= $user_name ?> in class <?= $class_name ?></h1>

        <div class="circle">
            <?= $percentage ?>%
        </div>
        <ul>
            <?php
            if ($close_call > 0) {
                echo "<li><b>$close_call</b> close call</li>";
            }
            ?>
            <?php
            if (!empty($most_ditched_day)) {
            ?>
                <li><b>Most frequent absent day:</b> <?= $most_ditched_day ?></li>
            <?php
            }
            ?>
            <li><b>Average present days per week:</b> <?= $average_per_week ?></li>
        </ul>
    </div>

    <?php include("dark_mode.php") ?>
</body>

</html>
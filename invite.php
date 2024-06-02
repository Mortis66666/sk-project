<?php
session_start();
include("database.php");
include("debug.php");
include("check_user.php");

$invite = $_GET['id'];

$query = "SELECT nama, id_kelas FROM kelas WHERE invite = ?";

$result = $conn->execute_query($query, [$invite]);

if ($result->num_rows == 0) {
    header("Location: home.php");
    exit();
}

$row = $result->fetch_assoc();
$class_name = $row['nama'];
$class_id = $row['id_kelas'];

// Check if user in class
$query = "SELECT * FROM kelas_pengguna WHERE id_pengguna = ? AND id_kelas = ?";
$result = $conn->execute_query($query, [$_SESSION['user_id'], $class_id]);

if ($result->num_rows > 0) {
    header("Location: class.php?id=$class_id");
    exit();
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Join Class</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style media="screen">
        .invite-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin-top: 0;
        }

        .invite-message {
            font-size: 24px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <?php include("header.php"); ?>

    <div class="invite-container content">
        <p class="invite-message">You are invited to <?php echo $class_name; ?> class</p>
        <form action="join_class.php" method="post">
            <input type="hidden" name="invite" value="<?php echo $invite; ?>">
            <button type="submit">Join Class</button>
        </form>
    </div>
</body>

</html>
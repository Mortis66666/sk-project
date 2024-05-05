<?php
session_start();
include("database.php");
include("debug.php");
if (!isset($_SESSION['user_id'])) {
    debug_log("User not logged in");
    header("Location: login.php");
    exit();
}

$invite = $_GET['e'];
$query = sprintf(
    "SELECT nama FROM kelas
    WHERE nama = '%s'",
    $invite
);

$result = $conn->query($query);
if ($result->num_rows == 0) {
    header("Location: index.php");
    exit();
}

$class_name = $result->fetch_assoc()['nama'];
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
        }

        .invite-message {
            font-size: 24px;
            margin-bottom: 20px;
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
    <div class="invite-container content">
        <p class="invite-message">You are invited to <?php echo $class_name; ?> class</p>
        <form action="join_class.php" method="post">
            <input type="hidden" name="invite" value="<?php echo $invite; ?>">
            <button type="submit">Join Class</button>
        </form>
    </div>
</body>

</html>
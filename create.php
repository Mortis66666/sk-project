<?php
session_start();
include("database.php");
include("debug.php");
include("check_user.php");;
?>

<!DOCTYPE html>
<html>

<head>
    <title>Create Class</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet" type='text/css'>
    <style media="screen">
        .create-class-form {
            border: 2px #080710 solid;
            border-radius: 10px;
            width: 35%;
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
    <?php include("header.php"); ?>

    <div class="create-class-form content">
        <h1>Create Class</h1>
        <form action="create.php" method="post">
            <label for="class-name">Class Name:</label>
            <input type="text" id="class-name" name="class-name">
            <br>
            <button type="submit">Create</button>
        </form>
    </div>

    <?php
    if (isset($_SESSION['error'])) {
        debug_log("Error: " . $_SESSION['error']);
        $error = $_SESSION['error'];
        unset($_SESSION['error']);
        execute("alert('" . $error . "')");
    }
    ?>

    <?php include("dark_mode.php") ?>
</body>

</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_name = $_POST['class-name'];
    $user_id = $_SESSION['user_id'];

    function create_class($conn, $class_name, $user_id)
    {
        // Check if class name is empty
        if (empty($class_name)) {
            $_SESSION['error'] = "Nice try, but you cant create a class without a name!";
            return;
        }

        // Check if class name contains special characters
        if (!preg_match("/^[a-zA-Z0-9 ]*$/", $class_name)) {
            $_SESSION['error'] = "Class name can only contain letters, numbers, and spaces!";
            return;
        }

        // Check if class name is longer than 50 characters
        if (strlen($class_name) > 50) {
            $_SESSION['error'] = "Class name is too long!";
            return;
        }

        $code = generate_code();

        $query = "INSERT INTO kelas (nama, invite, code, last_update) VALUES ('$class_name', UUID(), $code, NOW())";
        $result = mysqli_query($conn, $query);

        if ($result) {
            debug_log("Class created successfully");
            $last_id = mysqli_insert_id($conn);
        } else {
            $_SESSION['error'] = "Error creating class: " . mysqli_error($conn);
            return;
        }

        $query = "INSERT INTO kelas_pengguna (id_kelas, id_pengguna, peranan) VALUES ($last_id, $user_id, 'GURU')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            debug_log("User added to class successfully");
            header("Location: home.php");
        } else {
            $_SESSION['error'] = "Error adding user to class: " . mysqli_error($conn);
            return;
        }
    }

    create_class($conn, $class_name, $user_id);
}

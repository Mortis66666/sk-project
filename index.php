<!DOCTYPE html>
<html lang="en">

<?php
session_start();
include("debug.php");
// debug_log($_SESSION['user_id']);
if (!isset($_SESSION['user_id'])) {
    debug_log("User not logged in");
    header("Location: login.php");
    exit();
}
?>

<head>
    <title>Home Menu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <style media="screen">
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        .dark-mode-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: transparent;
            border: none;
            outline: none;
            cursor: pointer;
        }

        .dark-mode-icon {
            color: #ffffff;
            font-size: 24px;
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

        .create-class-button {
            margin-right: 20px;
        }

        .logout-button {
            margin-left: 20px;
        }

        .content {
            margin-top: 20vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            max-height: 80vh;
            overflow-y: scroll;
        }

        .content button {
            background-color: #ffffff;
            color: #080710;
            border: 2px #080710 solid;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 5px;
            margin: 10px 0;
            cursor: pointer;
            width: 20vw;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .content button:hover {
            background-color: yellow;
        }

        /* Dark Mode Styles */
        body.dark-mode {
            background-color: #080710;
            color: #ffffff;
        }

        .header.dark-mode {
            background-color: rgba(0, 0, 0, 0.13);
            border: 2px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 0 40px rgba(255, 255, 255, 0.6);
        }

        .content.dark-mode button {
            background-color: #26252C !important;
            color: #ffffff;
        }
    </style>
</head>

<body>
    <div class="header" id="header">
        <button class="create-class-button">Create New Class</button>
        <button class="logout-button">Logout</button>
    </div>
    <div class="content" id="content">
        <!-- List of buttons goes here -->
        <!-- Example button: -->
        <button>4ST4</button>
        <button>3 Aranda</button>
        <button>kulupu musi</button>
        <!-- Add more buttons as needed -->
    </div>
    <button class="dark-mode-toggle" id="darkModeToggle">
        <i class="fa-solid fa-moon fa-2xl" id="icon-toggle"></i>
    </button>

    <script src="script.js"></script>
</body>

</html>
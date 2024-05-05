<?php
session_start();
include("database.php");
include("debug.php");
if (!isset($_SESSION['user_id'])) {
    debug_log("User not logged in");
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

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
            margin-top: 5vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            max-height: 80vh;
        }

        .content .checkbox-container {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            /* To align the checkbox to the right */
        }

        .content .checkbox-container input[type="checkbox"] {
            margin-left: 10px;
            /* Adjust spacing between checkbox and button text */
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
            width: 40vw;
            transition: background-color 0.3s ease, color 0.3s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        .content.dark-mode button.checked {
            background-color: lightgreen !important;
            color: #080710 !important;
        }

        .content.dark-mode button {
            background-color: #26252C !important;
            color: #ffffff;
        }

        .invitation {
            display: flex;
            justify-content: center;
        }

        .input-container {
            display: flex;
            justify-content: center;
            margin-top: 20vh;
        }

        .input-box {
            padding: 10px;
            font-size: 16px;
            border: 2px solid #080710;
            border-radius: 5px;
            width: 300px;
        }

        #copy-button,
        .submit-button {
            background-color: #ffffff;
            color: #080710;
            border: 2px solid #080710;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 2vw;
        }

        .checked {
            background-color: lightgreen !important;
        }

        #copy-button {
            width: auto;
        }

        .tooltip {
            position: relative;
            display: inline-block;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 140px;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            top: 150%;
            left: 50%;
            margin-left: -75px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .tooltip .tooltiptext::after {
            content: "";
            position: absolute;
            bottom: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: transparent transparent #555 transparent;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }
    </style>
</head>

<body>
    <form class="input-container">
        <input class="input-box" type="text" name="code" placeholder="Enter code">
        <button class="submit-button" type="submit">Submit</button>
    </form>

    <div class="header" id="header">
        <button class="create-class-button">
            <i class="fa-solid fa-left-long"></i>
            Back
        </button>

        <div class="invitation">
            <input type="text" value="<?php
                                        $query = "SELECT invite FROM kelas WHERE id_kelas = " . $_GET['id'];
                                        $result = $conn->query($query);
                                        $invite = $result->fetch_assoc()['invite'];

                                        // debug_log($_SERVER['SERVER_ADDR']);
                                        echo 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/' . 'invite.php?id=' . $invite;
                                        ?>" id="invitation-link" class="input-box" readonly>

            <div class="tooltip">
                <button id="copy-button">
                    <span class="tooltiptext" id="tooltip">Copy to clipboard</span>
                    <i class="fa-solid fa-copy"></i>
                </button>
            </div>

        </div>

        <button class="logout-button">Logout</button>
    </div>
    <div class="content" id="content">
        <?php
        $query = sprintf(
            "SELECT id_pengguna, nama_pengguna as name FROM pengguna
            WHERE id_pengguna IN (
                SELECT id_pengguna FROM kelas_pengguna
                WHERE id_kelas = %u and peranan != 'GURU'
            )",
            $_GET['id']
        );

        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo sprintf(
                    "<label class='checkbox-container'>
                        <button class='name-btn'>%s</button>
                        <input type='checkbox'>
                    </label>",
                    $row['name']
                );
            }
            $students = true;
        } else {
            echo "<p>No students in this class</p>";
            $students = false;
        }
        ?>
    </div>

    <?php
    if ($students) {
        $code = `for (let element of content.children) {
            const [nameBtn, checkbox] = element.children;

            checkbox.addEventListener("click", () => {
                nameBtn.classList.toggle("checked");
            });
        }`;
        echo '<script>' . $code . '</script>';
    }
    ?>

    <button class="dark-mode-toggle" id="darkModeToggle">
        <i class="fa-solid fa-moon fa-2xl" id="icon-toggle"></i>
    </button>

    <script src="script.js"></script>
    <script>
        const copyBtn = document.getElementById("copy-button");

        copyBtn.onclick = () => {
            let copyText = document.getElementById("invitation-link");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);

            let tooltip = document.getElementById("tooltip");
            tooltip.innerHTML = "Copied to clipboard!";
        };

        copyBtn.addEventListener("mouseout", () => {
            let tooltip = document.getElementById("tooltip");
            tooltip.innerHTML = "Copy to clipboard";
        })
    </script>
</body>

</html>
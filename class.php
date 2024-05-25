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

$class_id = $_GET['id'];

// Get class name
$query = "SELECT nama FROM kelas WHERE id_kelas = ?";
$result = $conn->execute_query($query, [$class_id]);

if ($result->num_rows == 0) {
    debug_log("Class not found");
    header("Location: index.php");
    exit();
}

$class_name = $result->fetch_assoc()['nama'];

// Check if student in class
$query = "SELECT * FROM kelas_pengguna WHERE id_kelas = ? AND id_pengguna = ?";
$result = $conn->execute_query($query, [$class_id, $_SESSION['user_id']]);
if ($result->num_rows == 0) {
    debug_log("User not in class");
    header("Location: index.php");
    exit();
}

$students = array();

$query =
    "SELECT
    p.id_pengguna AS id,
    p.nama_pengguna AS name,
    COALESCE(MAX(DATEDIFF(CURDATE(), k.masa_daftar) <= 1), 0) AS attended
    FROM
        pengguna p
    INNER JOIN
        kelas_pengguna kp ON kp.id_pengguna = p.id_pengguna AND kp.peranan != 'GURU' AND kp.id_kelas = ?
    LEFT JOIN
        kehadiran k ON k.id_pengguna = p.id_pengguna AND k.id_kelas = kp.id_kelas
    GROUP BY
        p.id_pengguna, p.nama_pengguna;
    ";

$result = $conn->execute_query($query, [$class_id]);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = array(
            'id' => $row['id'],
            'name' => $row['name'],
            'attended' => $row['attended']
        );
    }
}

execute("const students = " . json_encode($students) . ";");
execute("const class_id = " . $class_id . ";");

if (isset($_SESSION['code-result'])) {
    execute("alert('" . $_SESSION['code-result'] . "')");
    unset($_SESSION['code-result']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Class <?= $class_name ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet" type='text/css'>
    <style media="screen">
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
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
            margin-top: 30px;
        }

        .hide {
            display: none !important;
        }

        .wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 1;
            /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto;
            /* Enable scroll if needed */
            background-color: rgb(0, 0, 0);
            /* Fallback color */
            background-color: rgba(0, 0, 0, 0.4);
            /* Black w/ opacity */
        }

        .invite-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            /* justify-content: center; */
            background-color: #ffffff;
            border: 2px #080710 solid;
            border-radius: 10px;
            padding: 20px;
            width: 70vh;
            height: 30vh;
            padding: 5vh;
            text-align: center;
            box-shadow: 0 0 40px rgba(8, 7, 16, 0.6);
            backdrop-filter: blur(10px);
        }

        .invite-box.dark-mode {
            background-color: #26252C !important;
        }

        .close {
            background-color: #ffffff;
            color: #080710;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .invite-box.dark-mode .close {
            background-color: #26252C !important;
            color: #ffffff;
        }

        .input-container {
            display: flex;
            justify-content: center;
        }

        .input-box {
            padding: 10px;
            font-size: 16px;
            border: 2px solid #080710;
            border-radius: 5px;
            width: 300px;
        }

        .code {
            text-align: center;
            font-weight: 600;
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
            bottom: 150%;
            left: 50%;
            margin-left: -75px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .tooltip .tooltiptext::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #555 transparent transparent transparent;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }

        .users {
            margin-top: 5vh;
        }

        .utils {
            display: flex;
            flex-direction: row;
        }

        .utils .input-box {
            height: auto !important;
            margin-right: 10px;
        }

        .invite-popup-btn {
            width: auto !important;
        }
    </style>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
    <?php include("header.php"); ?>

    <div class="content" id="content">
        <div class="utils">
            <input class="input-box" id="search-user" type="text" placeholder="Search">
            <button class="invite-popup-btn" id="invite-popup-btn">
                <i class="fa-solid fa-user-plus"></i>
            </button>
        </div>


        <div class="users" id="users">

        </div>
    </div>

    <div class="wrapper hide">
        <div class="invite-box" id="invite-box">
            <button class="close" id="close">
                <i class="fa-solid fa-x"></i>
            </button>
            <h2>Invite students to join this class!</h2>
            <div class="invitation">
                <input type="text" value="<?php
                                            $query = "SELECT invite FROM kelas WHERE id_kelas = " . $_GET['id'];
                                            $result = $conn->query($query);
                                            $invite = $result->fetch_assoc()['invite'];
                                            echo 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/' . 'invite.php?id=' . $invite;
                                            ?>" id="invitation-link" class="input-box" readonly>
                <div class="tooltip">
                    <button id="copy-button">
                        <span class="tooltiptext" id="tooltip">Copy to clipboard</span>
                        <i class="fa-solid fa-copy"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php include("dark_mode.php") ?>

    <script>
        const copyBtn = document.getElementById("copy-button");
        const close = document.getElementById("close");
        const inviteBtn = document.getElementById("invite-popup-btn");
        const users = document.getElementById("users");
        const searchUser = document.getElementById("search-user");
        const code = document.getElementById("code");

        for (let student of students) {
            const label = document.createElement("label");
            label.classList.add("checkbox-container");
            // label.id = student.id;

            const nameBtn = document.createElement("button");
            nameBtn.classList.add("name-btn");
            nameBtn.innerHTML = student.name;

            const checkbox = document.createElement("input");
            checkbox.type = "checkbox";

            if (student.attended) {
                nameBtn.classList.add("checked");
                checkbox.checked = true;
            }

            label.appendChild(nameBtn);
            label.appendChild(checkbox);

            users.appendChild(label);

            nameBtn.addEventListener("click", () => {
                window.location.href = `analysis.php?cid=${class_id}&uid=${student.id}`;
            });

            checkbox.addEventListener("click", () => {
                if (checkbox.checked) {
                    // Send post request to attendance.php
                    $.ajax({
                        url: "attendance.php",
                        type: "POST",
                        dataType: "json",
                        data: {
                            class_id: <?php echo $class_id; ?>,
                            student_id: student.id,
                            action: "add"
                        },
                        success: function(data) {
                            nameBtn.classList.add("checked");
                        },
                        error: function(xhr, status, error) {
                            checkbox.checked = false;
                            let err = eval("(" + xhr.responseText + ")");
                            alert(err.error);
                        }
                    });
                } else {
                    // Send post request to attendance.php
                    $.ajax({
                        url: "attendance.php",
                        type: "POST",
                        dataType: "json",
                        data: {
                            class_id: <?php echo $class_id; ?>,
                            student_id: student.id,
                            action: "remove"
                        },
                        success: function(data) {
                            nameBtn.classList.remove("checked");
                        },
                        error: function(xhr, status, error) {
                            checkbox.checked = true;
                            let err = eval("(" + xhr.responseText + ")");
                            alert(err.error);
                        }
                    });
                }
            });
        }

        searchUser.oninput = () => {
            const query = searchUser.value.toLowerCase();
            for (let student of students) {
                const label = document.getElementById(student.id);
                if (student.name.toLowerCase().includes(query)) {
                    label.classList.remove("hide");
                } else {
                    label.classList.add("hide");
                }
            }
        }

        copyBtn.onclick = () => {
            let copyText = document.getElementById("invitation-link");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);

            let tooltip = document.getElementById("tooltip");
            tooltip.innerHTML = "Copied to clipboard!";
        };

        code.onclick = () => {
            code.select();
            code.setSelectionRange(0, 99999);
            document.execCommand("copy");
        };

        copyBtn.addEventListener("mouseout", () => {
            let tooltip = document.getElementById("tooltip");
            tooltip.innerHTML = "Copy to clipboard";
        })

        inviteBtn.onclick = () => {
            document.querySelector(".wrapper").classList.remove("hide");
        }

        close.onclick = () => {
            document.querySelector(".wrapper").classList.add("hide");
        }
    </script>
</body>

</html>
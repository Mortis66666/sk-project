<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
            font-family: "Poppins", sans-serif;
            flex-direction: column;
        }

        .button {
            background-color: #ffffff;
            color: #080710;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px 0;
            font-size: 8vh;
        }

        .button:hover {
            background-color: #ffff00 !important;
        }

        h1 {
            font-size: 10vh;
        }

        #welcome {
            margin-bottom: 0;
        }

        #magic-word {
            color: #181CE7;
            margin-top: 0;
            cursor: pointer;
            -webkit-animation: glow 1s ease-in-out infinite alternate;
            -moz-animation: glow 1s ease-in-out infinite alternate;
            animation: glow 1s ease-in-out infinite alternate;
        }

        @keyframes glow {
            from {
                text-shadow: 0 0 10px #fff, 0 0 20px #fff, 0 0 30px #E7E318, 0 0 40px #E7E318, 0 0 50px #E7E318, 0 0 60px #E7E318, 0 0 70px #E7E318;
            }

            to {
                text-shadow: 0 0 20px #fff, 0 0 30px #FFC500, 0 0 40px #FFC500, 0 0 50px #FFC500, 0 0 60px #FFC500, 0 0 70px #FFC500, 0 0 80px #FFC500;
            }
        }
    </style>
</head>

<body>
    <h1 id="welcome">Welcome to</h1>
    <h1 id="magic-word">ASK</h1>

    <a href="login.php"><button class="button">Login</button></a>
    <a href="signup.php"><button class="button">Sign Up</button></a>

    <?php
    include("javascript.php");

    $version = phpversion();

    // Check if version >= 8.2.0

    if (version_compare($version, '8.2.0', '<')) {
        execute("alert('You are using php version $version, but version 8.2.0 is required')");
    }


    ?>

    <script>
        // Change magic word text on hover
        document.getElementById("magic-word").addEventListener("mouseover", function() {
            this.innerHTML = "Aplikasi Sistem Kehadiran";
        });

        document.getElementById("magic-word").addEventListener("mouseout", function() {
            this.innerHTML = "ASK";
        });
    </script>
</body>

</html>
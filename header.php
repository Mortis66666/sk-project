<div class="header" id="header">
    <?php
    $current_page = basename($_SERVER['PHP_SELF']);

    if ($current_page == 'index.php') {
        echo '<a href="create.php"><button class="create-class-button">Create New Class</button></a>';
    }

    if ($current_page == 'class.php') {
        echo '<button class="create-class-button" onclick="history.back()"><i class="fa-solid fa-left-long"></i>Back</button>';

        $user_id = $_SESSION['user_id'];
        $class_id = $_GET['id'];

        $query = "SELECT peranan FROM kelas_pengguna WHERE id_pengguna = $user_id AND id_kelas = $class_id";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $row = mysqli_fetch_assoc($result);

            if ($row['peranan'] == 'GURU') {
                $code = get_code($class_id);
                echo '<input class="input-box code" id="code" type="text" name="code" value="' . $code . '" readonly>';
            } else {
                echo '<form class="input-container"><input class="input-box" type="text" name="code" placeholder="Enter code"><button class="submit-button" type="submit">Submit</button></form>';
            }
        }
    }

    if ($current_page == 'create.php' || $current_page == 'invite.php') {
        echo '<button class="create-class-button" onclick="history.back()"><i class="fa-solid fa-left-long"></i>Back</button>';
    }

    echo '<a href="logout.php"><button class="logout-button">Logout</button></a>';
    ?>
</div>
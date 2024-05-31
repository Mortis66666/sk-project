<div class="header" id="header">
    <?php
    $current_page = basename($_SERVER['PHP_SELF']);

    if ($current_page == 'home.php') {
    ?>
        <a href="create.php"><button class="create-class-button">Create New Class</button></a>
    <?php
    }

    if ($current_page == 'class.php') {
    ?>
        <button class="create-class-button" onclick="history.back()"><i class="fa-solid fa-left-long"></i>Back</button>

        <?php

        $user_id = $_SESSION['user_id'];
        $class_id = $_GET['id'];

        $query = "SELECT peranan FROM kelas_pengguna WHERE id_pengguna = $user_id AND id_kelas = $class_id";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $row = mysqli_fetch_assoc($result);

            if ($row['peranan'] == 'GURU' || $row['peranan'] == 'ADMIN') {
                $code = get_code($class_id);

                echo '<input class="input-box code" id="code" type="text" name="code" value="' . $code . '" readonly>';
            } else {
        ?>
                <form class="input-container" method="post" action="enter_code.php">
                    <input type="hidden" name="class_id" value="<?php echo $class_id ?>">
                    <input class="input-box" type="text" name="code" placeholder="Enter code">
                    <button class="submit-button" type="submit">Submit</button>
                </form>
        <?php
            }
        }
    }

    if ($current_page == 'create.php' || $current_page == 'invite.php' || $current_page == 'analysis.php') {
        ?>
        <button class="create-class-button" onclick="history.back()"><i class="fa-solid fa-left-long"></i>Back</button>
    <?php
    }
    ?>

    <a href="logout.php"><button class="logout-button">Logout</button></a>
</div>
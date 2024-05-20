<div class="header" id="header">
    <?php
    $current_page = basename($_SERVER['PHP_SELF']);

    if ($current_page == 'index.php') {
        echo '<a href="create.php"><button class="create-class-button">Create New Class</button></a>';
    }

    if ($current_page == 'class.php') {
        echo '<button class="create-class-button" onclick="history.back()"><i class="fa-solid fa-left-long"></i>Back</button>';
        echo '<form class="input-container"><input class="input-box" type="text" name="code" placeholder="Enter code"><button class="submit-button" type="submit">Submit</button></form>';
    }

    if ($current_page == 'create.php' || $current_page == 'invite.php') {
        echo '<button class="create-class-button" onclick="history.back()"><i class="fa-solid fa-left-long"></i>Back</button>';
    }

    echo '<a href="logout.php"><button class="logout-button">Logout</button></a>';
    ?>
</div>
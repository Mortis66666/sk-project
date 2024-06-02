<?php
if (!isset($_SESSION['user_id'])) {
    debug_log("User not logged in");
    header("Location: index.html");
    exit();
}

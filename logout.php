<?php
require_once __DIR__ . "/config/functions.php";

if (is_logged_in()) {
    log_action($conn, "Logged out of the system");
}

session_destroy();
header("Location: login.php");
exit();
?>

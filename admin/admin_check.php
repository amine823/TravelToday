<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}

//regenerate session id after 30 minutes
if (!isset($_SESSION['session_created'])) {
    $_SESSION['session_created'] = time();
} else if (time() - $_SESSION['session_created'] > 1800) {
    // session startedmore than 30 minutes ago
    session_regenerate_id(true);
    $_SESSION['session_created'] = time();
}
?>
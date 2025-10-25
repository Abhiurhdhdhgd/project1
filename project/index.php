<?php
session_start();

// If user is logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
} else {
    // Otherwise, redirect to login page
    header("Location: login.php");
}
exit();
?>
<?php
session_start();

if (isset($_SESSION["user_id"]) && $_SESSION["role"] == "attendee") {
    header("Location: views/attendee/dashboard.php");
    exit();
} else {
    header("Location: views/attendee/login.php");
    exit();
}
?>
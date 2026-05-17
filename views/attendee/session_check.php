<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "attendee") {
    header("Location: login.php");
    exit();
}
?>
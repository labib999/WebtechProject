<?php
$conn = new mysqli("localhost", "root", "", "event_platform");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
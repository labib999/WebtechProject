<?php
function getDB() {
    $host = "sql12.freesqldatabase.com";
    $db   = "sql12826762";
    $user = "sql12826762";
    $pass = "YYrIAFxd6v";

    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");
    return $conn;
}
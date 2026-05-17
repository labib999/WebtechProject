<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "attendee") {
    header("Location: ../views/attendee/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"];
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);

    if ($name == "" || $email == "" || $phone == "") {
        $_SESSION["error"] = "All fields are required";
        header("Location: ../views/attendee/profile.php");
        exit();
    }

    $sql = "update users set name = ?, email = ?, phone = ? where id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $name, $email, $phone, $user_id);

    if ($stmt->execute()) {
        $_SESSION["name"] = $name;
        $_SESSION["email"] = $email;
        $_SESSION["success"] = "Profile updated successfully";
        header("Location: ../views/attendee/profile.php");
        exit();
    } else {
        $_SESSION["error"] = "Profile update failed";
        header("Location: ../views/attendee/profile.php");
        exit();
    }
}
?>
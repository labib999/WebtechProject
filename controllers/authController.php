<?php
session_start();
include("../config/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"];

    if ($action == "register") {
        $name = trim($_POST["name"]);
        $email = trim($_POST["email"]);
        $phone = trim($_POST["phone"]);
        $password = trim($_POST["password"]);
        $confirm_password = trim($_POST["confirm_password"]);

        if ($name == "" || $email == "" || $phone == "" || $password == "" || $confirm_password == "") {
            $_SESSION["error"] = "All fields are required";
            header("Location: ../views/attendee/register.php");
            exit();
        }

        if (!preg_match("/^[a-zA-Z ]+$/", $name)) {
            $_SESSION["error"] = "Name must contain letters only";
            header("Location: ../views/attendee/register.php");
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["error"] = "Invalid email format";
            header("Location: ../views/attendee/register.php");
            exit();
        }

        if (!is_numeric($phone)) {
            $_SESSION["error"] = "Phone must contain numbers only";
            header("Location: ../views/attendee/register.php");
            exit();
        }

        if (strlen($phone) != 11) {
            $_SESSION["error"] = "Phone number must be 11 digits";
            header("Location: ../views/attendee/register.php");
            exit();
        }

        if (strlen($password) < 6) {
            $_SESSION["error"] = "Password must be at least 6 characters";
            header("Location: ../views/attendee/register.php");
            exit();
        }

        if ($password != $confirm_password) {
            $_SESSION["error"] = "Password does not match";
            header("Location: ../views/attendee/register.php");
            exit();
        }

        $checkSql = "select id from users where email = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            $_SESSION["error"] = "Email already exists";
            header("Location: ../views/attendee/register.php");
            exit();
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $role = "attendee";

        $sql = "insert into users (name, email, password_hash, phone, role) values (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $name, $email, $password_hash, $phone, $role);

        if ($stmt->execute()) {
            $_SESSION["success"] = "Registration successful. Please login.";
            header("Location: ../views/attendee/login.php");
            exit();
        } else {
            $_SESSION["error"] = "Registration failed";
            header("Location: ../views/attendee/register.php");
            exit();
        }
    }

    if ($action == "login") {
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);

        if ($email == "" || $password == "") {
            $_SESSION["error"] = "Email and password are required";
            header("Location: ../views/attendee/login.php");
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["error"] = "Invalid email format";
            header("Location: ../views/attendee/login.php");
            exit();
        }

        $sql = "select * from users where email = ? and role = 'attendee' and is_active = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user["password_hash"])) {
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["name"] = $user["name"];
                $_SESSION["email"] = $user["email"];
                $_SESSION["role"] = $user["role"];

                header("Location: ../views/attendee/dashboard.php");
                exit();
            } else {
                $_SESSION["error"] = "Invalid password";
                header("Location: ../views/attendee/login.php");
                exit();
            }
        } else {
            $_SESSION["error"] = "Invalid email";
            header("Location: ../views/attendee/login.php");
            exit();
        }
    }
}
?>
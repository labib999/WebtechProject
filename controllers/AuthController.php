<?php
session_start();
require_once '../config/db.php';


function handleLogin() {
    
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    
    if (empty($email) || empty($password)) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Please fill in both email and password.'];
        header('Location: ../views/shared/login.php');
        exit;
    }

    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Please enter a valid email address.'];
        header('Location: ../views/shared/login.php');
        exit;
    }

    
    $conn = getDB();
    $stmt = $conn->prepare("SELECT id, name, email, password_hash, role, is_active FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    
    if (!$user) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'No account found with that email address.'];
        header('Location: ../views/shared/login.php');
        exit;
    }

    
    if (!password_verify($password, $user['password_hash'])) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Incorrect password. Please try again.'];
        header('Location: ../views/shared/login.php');
        exit;
    }

    
    if (!$user['is_active']) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Your account has been suspended. Please contact support.'];
        header('Location: ../views/shared/login.php');
        exit;
    }

    
    if ($user['role'] !== 'admin') {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'You do not have permission to access the admin panel.'];
        header('Location: ../views/shared/login.php');
        exit;
    }

    
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role']    = $user['role'];
    $_SESSION['name']    = $user['name'];

    
    header('Location: ../views/admin/dashboard.php');
    exit;
}


function handleLogout() {
    session_destroy();
    header('Location: ../views/shared/login.php');
    exit;
}


if (isset($_POST['action']) && $_POST['action'] === 'login') {
    handleLogin();
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    handleLogout();
}
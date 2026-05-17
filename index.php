<?php
session_start();


$page = isset($_GET['page']) ? $_GET['page'] : 'login';
$action = isset($_GET['action']) ? $_GET['action'] : '';


if ($action === 'logout') {
    session_destroy();
    header('Location: views/shared/login.php');
    exit;
}


switch ($page) {
    case 'admin':
        require_once 'controllers/AdminController.php';
        break;

    case 'auth':
        require_once 'controllers/AuthController.php';
        break;

    default:
        header('Location: views/shared/login.php');
        break;
}
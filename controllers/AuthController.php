<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$action = $_GET['action'] ?? '';

if ($action === 'logout') {
    session_destroy();
    header('Location: /webtechproject/WebtechProject/views/shared/login.php');
    exit;
}

header('Location: /webtechproject/WebtechProject/views/shared/login.php');
exit;
?>
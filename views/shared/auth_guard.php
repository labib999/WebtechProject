<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'venue_manager') {
    header('Location: /webtechproject/WebtechProject/views/shared/login.php');
    exit;
}
?>
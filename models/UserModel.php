<?php
require_once 'config/db.php';


function getAllUsers() {
    $conn = getDB();
    $sql = "SELECT id, name, email, phone, role, is_active, created_at 
            FROM users 
            ORDER BY created_at DESC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}


function countUsersByRole() {
    $conn = getDB();
    $sql = "SELECT role, COUNT(*) as total 
            FROM users 
            GROUP BY role";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}


function getUserById($id) {
    $conn = getDB();
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}


function searchUsers($keyword) {
    $conn = getDB();
    $keyword = "%" . $keyword . "%";
    $stmt = $conn->prepare("SELECT id, name, email, phone, role, is_active, created_at 
                            FROM users 
                            WHERE name LIKE ? OR email LIKE ?
                            ORDER BY created_at DESC");
    $stmt->bind_param("ss", $keyword, $keyword);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}


function suspendUser($id) {
    $conn = getDB();
    $stmt = $conn->prepare("UPDATE users SET is_active = 0 WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

function reactivateUser($id) {
    $conn = getDB();
    $stmt = $conn->prepare("UPDATE users SET is_active = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}


function countNewUsersToday() {
    $conn = getDB();
    $sql = "SELECT COUNT(*) as total 
            FROM users 
            WHERE DATE(created_at) = CURDATE()";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['total'];
}


function countTotalUsers() {
    $conn = getDB();
    $sql = "SELECT COUNT(*) as total FROM users";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['total'];
}
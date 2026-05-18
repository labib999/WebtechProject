<?php
function checkEmailExists($conn, $email){
    $sql = "select id from users where email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    return $stmt->get_result();
}

function registerAttendee($conn, $name, $email, $password_hash, $phone, $role){
    $sql = "insert into users (name, email, password_hash, phone, role) values (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $email, $password_hash, $phone, $role);
    return $stmt->execute();
}

function getUserByEmail($conn, $email){
    $sql = "select * from users where email = ? and role = 'attendee' and is_active = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    return $stmt->get_result();
}

function getUserById($conn, $user_id){
    $sql = "select * from users where id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}

function updateUserProfile($conn, $name, $email, $phone, $user_id){
    $sql = "update users set name = ?, email = ?, phone = ? where id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $name, $email, $phone, $user_id);
    return $stmt->execute();
}
?>
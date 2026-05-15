<?php
require_once '../config/db.php';

$newHash = password_hash('123456', PASSWORD_BCRYPT);
echo "New hash: " . $newHash . "<br>";

$conn = getDB();
$stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE email = 'rohit@emts.com'");
$stmt->bind_param('s', $newHash);
$stmt->execute();
echo "Updated! Rows: " . $stmt->affected_rows . "<br>";

echo "Verify: ";
var_dump(password_verify('123456', $newHash));
?>
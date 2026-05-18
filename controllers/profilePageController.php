<?php
include("../../config/db.php");
include("../../models/UserModel.php");

$user_id = $_SESSION["user_id"];
$result = getUserById($conn, $user_id);
$user = $result->fetch_assoc();
?>
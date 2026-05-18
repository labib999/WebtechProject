<?php
include("../../config/db.php");
include("../../models/EventModel.php");

$search = "";

if (isset($_GET["search"])) {
    $search = trim($_GET["search"]);
}

if ($search != "") {
    $result = searchPublishedEvents($conn, $search);
} else {
    $result = getPublishedEvents($conn);
}
?>
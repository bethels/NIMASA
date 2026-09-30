<?php
session_start();

require 'database.php';


$sql = "SELECT MAX(id) AS max_id FROM cabotage";
$result = $conn->query($sql);
$row = $result->fetch_assoc();


$nextId = ($row['max_id']) ? $row['max_id'] + 1 : 1;



if (!isset($_SESSION['last_application'])) {
    $_SESSION['last_application'] = [];
}


$_SESSION['last_application']['next_suggested_id'] = $nextId;

$conn->close();


header("Location: ../others/cabotage1.php");
exit();
?>
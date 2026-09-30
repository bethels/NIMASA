<?php

header('Content-Type: application/json');
include 'db_connect.php'; 

$sql = "SELECT email_user, unread_count FROM conversations ORDER BY last_msg_date DESC";
$result = $conn->query($sql);

$contacts = [];
while($row = $result->fetch_assoc()) {
    $contacts[] = $row;
}

echo json_encode($contacts);
?>
<?php
session_start();
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "nimasa");

$session_email = $_SESSION['user_email'] ?? '';
$target = $_GET['target'] ?? 'admin_admin';

// Check if I am an admin
$check = $conn->prepare("SELECT file_path FROM nimdat WHERE email = ? LIMIT 1");
$check->bind_param("s", $session_email);
$check->execute();
$role = $check->get_result()->fetch_assoc()['file_path'] ?? '';

$checked=0;

if ($role === 'admin_admin' || $role === 'admin_admin_dir') {
   $checked=1;
    $sideA = 'admin_admin';
    $sideB = $target;
} else {
    // I am a user. I want to see messages between 'myself' and 'admin_admin'
    $sideA = $session_email;
    $sideB = 'admin_admin';
}

$stmt = $conn->prepare("SELECT * FROM chat WHERE (sender = ? AND receiver = ?) OR (sender = ? AND receiver = ?) ORDER BY send_date ASC");



$stmt->bind_param("ssss", $sideA, $sideB, $sideB, $sideA);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while($row = $result->fetch_assoc()) {
    $messages[] = $row;
}
echo json_encode($messages);


if ($checked == 1) {
    $stmt = $conn->prepare("UPDATE chat SET `replied` = 1 
                            WHERE sender = ? 
                            AND receiver = ? 
                            AND replied = 0");

   
    $stmt->bind_param("ss", $sideB, $sideA); 
    $stmt->execute();
}


?>
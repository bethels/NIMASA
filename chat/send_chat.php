<?php
session_start();
$conn = new mysqli("localhost", "root", "", "nimasa");

$session_email = $_SESSION['user_email'] ?? '';



$msg = $_POST['msg'] ?? '';

// Clean the input before saving to database
$msg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');

 
if (empty($session_email) || empty($msg)) {
    echo json_encode(["status" => "error", "message" => "Missing data"]);
    exit;
}

$target = $_POST['target'] ?? ''; // This is the user email (e.g., gama@gmail.com)
  
 $stmt = $conn->prepare("INSERT INTO chat (sender, receiver, msg, replied) VALUES (?, ?, ?, ?)");


// 1. Check if the person sending is an admin
$check = $conn->prepare("SELECT file_path FROM nimdat WHERE email = ? LIMIT 1");
$check->bind_param("s", $session_email);
$check->execute();
$role = $check->get_result()->fetch_assoc()['file_path'] ?? '';

// 2. Routing Logic
if ($role === 'admin_admin' || $role === 'admin_admin_dir') {
    $sender = 'admin_admin'; // <--- Mask the sender as the generic admin
    $receiver = $target;     // Send to the specific user
} else {
    $sender = $session_email; // Regular user sends as themselves
    $receiver = 'admin_admin'; 
}

$wordings=0;
// 3. Insert
$stmt = $conn->prepare("INSERT INTO chat (sender, receiver, msg, replied) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $sender, $receiver, $msg, $wordings);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}
?>
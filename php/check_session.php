<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_email'])) {
    header("Location: ../others/login.html");
    exit();
}

require 'database.php';

$session_email = $_SESSION['user_email'];


$query = "SELECT email, file_path FROM nimdat WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $session_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION = array();
    session_destroy();
    header("Location: ../others/login.html");
    exit();
}


$userData = $result->fetch_assoc();

// Verification check
if ($userData['file_path'] == 'admin_admin1'||$userData['file_path'] == 'admin_admin'||$userData['file_path'] == 'admin_admin2') {
    header("Location: ../others/login.html"); 
    exit();
}

// Success!
$stmt->close();
$conn->close();
?>
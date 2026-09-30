<?php
session_start();


$email = $_SESSION['user_email'] ?? null;


require 'database.php';


if (isset($_SESSION['user_email'])) {
    $email = $_SESSION['user_email'];
    
    $stmt = $conn->prepare("UPDATE nimdat SET remember_token = NULL, remember_expire = NULL WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    
   
    if ($stmt->affected_rows === 0) {
        
        error_log("Logout: No rows updated for email: " . $email);
    }
    $stmt->close();
}

$conn->close();


if (isset($_COOKIE['remember_me'])) {
    setcookie('remember_me', '', time() - 3600, '/');
}


$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}


session_destroy();
header("Location: ../index.html");
exit();
?>
<?php
require '../php/db_connection.php';

if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT * FROM nimdat WHERE email = ? AND token = ? LIMIT 1");
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows > 0) {
        $upd = $conn->prepare("UPDATE nimdat SET confirm = 1, token = NULL WHERE email = ?");
        $upd->bind_param("s", $email);
        $upd->execute();
        echo "<h1>Success!</h1><p>Your NIMASA account is verified. <a href='../others/login.html'>Login now</a>.</p>";
    } else {
        echo "<h1>Error</h1><p>Invalid or expired link.</p>";
    }
}
?>
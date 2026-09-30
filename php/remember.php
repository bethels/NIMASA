<?php
session_start();
header('Content-Type: application/json');

include '../php/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['user_email'] ?? '';
    $loginPassword = $_POST['password'] ?? '';
   

   
        $token = bin2hex(random_bytes(32)); 
        $token_hash = password_hash($token, PASSWORD_DEFAULT);
        $expiry_seconds = 86400 * 30; 
        $expiry_date = date('Y-m-d H:i:s', time() + $expiry_seconds);

      
        $stmt = $pdo->prepare("UPDATE nimdat SET remember_token = ?, remember_expire = ? WHERE email = ?");
        $stmt->execute([$token_hash, $expiry_date, $email]);

        
       $response = ['status' => 'error', 'message' => 'Failed to update'];

    if ($stmt->rowCount() > 0) {
      
        setcookie('remember_me', $token, [
            'expires' => time() + $expiry_seconds,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
        $response = ['status' => 'success', 'message' => 'Remember me set'];
    }

    echo json_encode($response);
    exit;

}
?>
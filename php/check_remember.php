<?php
header('Content-Type: application/json');

require 'database.php';



$response = ["status" => "none"];

if (isset($_COOKIE['remember_me'])) {
    $token = $_COOKIE['remember_me'];
    $now = date('Y-m-d H:i:s');

    
    $stmt = $conn->prepare("SELECT email, remember_token FROM nimdat WHERE remember_expire > ? AND remember_token IS NOT NULL");
    $stmt->bind_param("s", $now);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        if (password_verify($token, $row['remember_token'])) {
            $response = [
                "status" => "found",
                "email" => $row['email']
				
            ];
            break; 
        }
    }
    $stmt->close();
}

echo json_encode($response);
$conn->close();
?>
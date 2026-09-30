<?php
session_start(); 
header('Content-Type: application/json');



if (password_verify($loginPassword, $userRow['password'])) {
    
   
    $_SESSION['user_id'] = $userRow['id'];
    $_SESSION['username'] = $userRow['username'];
    $_SESSION['email'] = $userRow['email'];

    echo json_encode(["status" => "success", "message" => "Logged in!"]);
}


?>
<?php
session_start();

header('Content-Type: application/json');



if (isset($_POST["tag"])) {
   
    $_SESSION['tag'] = $_POST["tag"];
    
    echo json_encode([
        "status1" => "success", 
        "message" => "Session updated. Redirecting..."
    ]);
} else {
    echo json_encode([
        "status1" => "error", 
        "message" => "No tag received"
    ]);
}
?>
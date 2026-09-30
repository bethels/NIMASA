<?php
require '../php/check_session_admin.php'; 

require 'database.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
   
    $stmt = $conn->prepare("DELETE FROM cabotage WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        
        echo "success"; 
    } else {
        echo "Database Error: " . $conn->error;
    }
    
    $stmt->close();
} else {
    echo "No ID provided.";
}

$conn->close();
?>
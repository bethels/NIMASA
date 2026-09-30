<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "nimasa";

// Enable error reporting for mysqli
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $user, $pass, $dbname);
    $conn->set_charset("utf8mb4"); // Good for supporting emojis/special characters
} catch (Exception $e) {
    error_log($e->getMessage());
    exit('Error connecting to database'); 
}
?>
<?php
// db_connection.php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "nimasa";

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Set character set to utf8 to handle special characters correctly
$conn->set_charset("utf8");
?>
<?php
session_start();
$conn = new mysqli("localhost", "root", "", "nimasa");

$id = $_POST['id'];
$me = $_SESSION['user_email'];

// Safety: Only delete if I am the sender
$stmt = $conn->prepare("DELETE FROM chat WHERE id = ? AND sender = ?");
$stmt->bind_param("is", $id, $me);
$stmt->execute();
echo json_encode(["status" => "success"]);
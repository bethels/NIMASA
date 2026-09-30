<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "nimasa";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $plain_password = $_POST['password'] ?? '';
    
    // 1. Check if user already exists
    $check = $conn->prepare("SELECT id FROM NIMDAT WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Email already registered."]);
        exit;
    }

    // 2. Hash the password (creates the "gibberish" for security)
    $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);
    
    // 3. Set the file_path specifically to "admin_admin"
    $file_path = "admin_admin_dir";

    // 4. Insert into database
    $stmt = $conn->prepare("INSERT INTO NIMDAT (email, password, file_path) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $hashed_password, $file_path);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Admin created successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Error: " . $stmt->error]);
    }
    
    $stmt->close();
}
$conn->close();
?>
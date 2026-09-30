<?php
session_start();


header('Content-Type: application/json');

require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['user_email'] ?? '';
    $loginPassword = $_POST['password'] ?? '';
    $rememberMe = isset($_POST['remember_me_checked']); // Pass this from JS

    $stmt = $conn->prepare("SELECT * FROM nimdat WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($userRow = $result->fetch_assoc()) {
        
        // 1. Verify Password
        if (password_verify($loginPassword, $userRow['password'])) {
            
            // 2. Check Verification Status
            if ($userRow['confirm'] == 0) {
                $_SESSION['temp_verify_email'] = $userRow['email'];
                echo json_encode([
                    "status" => "unverified", 
                    "message" => "Account not verified. Redirecting..."
                ]);
                exit;
            }

            // 3. Store Session Data
            session_regenerate_id(true);
            $_SESSION['user_id'] = $userRow['id'];
            $_SESSION['user_email'] = $userRow['email'];
            $_SESSION['username'] = $userRow['username'];
            $_SESSION['file_path'] = $userRow['file_path'];

            // 4. Handle "Remember Me" Logic
            if ($rememberMe) {
                $token = bin2hex(random_bytes(32)); 
                $token_hash = password_hash($token, PASSWORD_DEFAULT);
                $expiry_seconds = 86400 * 30; 
                $expiry_date = date('Y-m-d H:i:s', time() + $expiry_seconds);

                // Update database using MySQLi
                $upd = $conn->prepare("UPDATE nimdat SET remember_token = ?, remember_expire = ? WHERE email = ?");
                $upd->bind_param("sss", $token_hash, $expiry_date, $email);
                
                if ($upd->execute()) {
                    setcookie('remember_me', $token, [
                        'expires' => time() + $expiry_seconds,
                        'path' => '/',
                        'httponly' => true,
                        'samesite' => 'Lax'
                    ]);
                }
                $upd->close();
            }

            // 5. Success Response
            unset($userRow['password']);
            echo json_encode([
                "status" => "success", 
                "message" => "Login successful",
                "data" => $userRow 
            ]);

        } else {
            echo json_encode(["status" => "error", "message" => "Incorrect password."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No account found."]);
    }
    $stmt->close();
}

else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}

$conn->close();
?>
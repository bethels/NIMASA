<?php
session_start();
header('Content-Type: application/json');
require '../php/db_connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

if (!isset($_SESSION['temp_verify_email'])) {
    echo json_encode(['status' => 'error', 'message' => 'No session found']);
    exit;
}

$recipient = $_SESSION['temp_verify_email'];
$token = bin2hex(random_bytes(32));

// Save token to DB
$upd = $conn->prepare("UPDATE nimdat SET token = ? WHERE email = ?");
$upd->bind_param("ss", $token, $recipient);
$upd->execute();

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'xeronstd@gmail.com';
    $mail->Password   = 'njmm bqup zqyf apxr'; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // XAMPP SSL Fix
    $mail->SMTPOptions = array(
        'ssl' => array('verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true)
    );

    $mail->setFrom('xeronstd@gmail.com', 'NIMASA Portal');
    $mail->addAddress($recipient);

    $v_link = "http://localhost/NIMASA/mailer/verify.php?email=$recipient&token=$token";

    $mail->isHTML(true);
    $mail->Subject = 'Verify Your NIMASA Account';
    $mail->Body    = "
        <div style='font-family:sans-serif; max-width:500px; border:1px solid #ddd; padding:20px; border-radius:10px;'>
            <h2 style='color:#0056b3;'>NIMASA Portal</h2>
            <p>Click the button below to verify your account:</p>
            <a href='$v_link' style='background:#28a745; color:white; padding:10px 20px; text-decoration:none; border-radius:5px; display:inline-block;'>Verify Account</a>
        </div>";

    $mail->send();
    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $mail->ErrorInfo]);
}
?>
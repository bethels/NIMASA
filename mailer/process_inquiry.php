<?php
// 1. Prevent accidental whitespace from breaking the JSON output
ob_start(); 
header('Content-Type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Correct paths to your PHPMailer files
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

$response = ['status' => 'error', 'message' => 'Internal server error'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize incoming data
    $name    = htmlspecialchars(strip_tags($_POST['name'] ?? 'Guest'));
    $email   = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $type    = htmlspecialchars(strip_tags($_POST['type'] ?? 'General Inquiry'));
    $message = nl2br(htmlspecialchars(strip_tags($_POST['message'] ?? '')));

    if (empty($email)) {
        echo json_encode(['status' => 'error', 'message' => 'Valid email is required.']);
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // --- Server Settings ---
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'xeronstd@gmail.com'; 
        $mail->Password   = 'njmm bqup zqyf apxr'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // --- Recipients ---
        $mail->setFrom('xeronstd@gmail.com', 'NIMASA Project System');
        
        
        $mail->addAddress($email, $name); 
        $mail->addBCC('xeronstd@gmail.com', 'NIMASA Admin');
        
        
        $mail->addReplyTo($email, $name); 

        // --- Content ---
        $mail->isHTML(true);
        $mail->Subject = "NIMASA Inquiry: $type from $name";
        
        // HTML Version
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; padding: 25px; border: 1px solid #1a5276; border-radius: 8px;'>
                <h2 style='color: #1a5276;'>Confirmation: Inquiry Received</h2>
                <p>Hello <strong>$name</strong>,</p>
                <p>Thank you for contacting the NIMASA Project System. We have received your inquiry regarding <strong>$type</strong>.</p>
                <hr style='border:none; border-top:1px solid #eee; margin: 20px 0;'>
                <p style='color: #555;'><strong>Your Message Summary:</strong></p>
                <div style='background:#f4f7f6; padding:15px; border-radius: 5px;'>$message</div>
                <p style='margin-top: 20px;'>Our team will review your request and get back to you shortly.</p>
                <p>Best Regards,<br><strong>NIMASA Admin Team</strong></p>
            </div>";

        // Plain Text Version (Crucial for Spam Filters)
        $mail->AltBody = "Hello $name,\n\nThank you for contacting NIMASA. We have received your inquiry regarding $type.\n\nYour Message: " . strip_tags($message) . "\n\nOur team will get back to you shortly.";

        $mail->send();
        $response = ['status' => 'success', 'message' => "Confirmation sent to $email. If you don't see it, please check your spam folder."];

    } catch (Exception $e) {
        $response = ['status' => 'error', 'message' => "Mailer Error: {$mail->ErrorInfo}"];
    }
}

// 3. Clear buffer and output JSON
ob_end_clean();
echo json_encode($response);
exit;
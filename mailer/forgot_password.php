<?php
session_start();
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include '../php/db_connect.php';


$message = "";
$statusClass = "hidden";
$alertType = "bg-blue-100 text-blue-700 border-blue-200";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $token = bin2hex(random_bytes(32)); 
    $token_hash = password_hash($token, PASSWORD_DEFAULT);
    $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

   
    $stmt = $pdo->prepare("UPDATE nimdat SET reset_token = ?, reset_expires = ? WHERE email = ?");
    $stmt->execute([$token_hash, $expiry, $email]);

    if ($stmt->rowCount() > 0) {
        $mail = new PHPMailer(true);

        try {
            // SMTP Settings
            $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'xeronstd@gmail.com';
    $mail->Password   = 'njmm bqup zqyf apxr'; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;


            // Recipients
            $mail->setFrom('xeronstd@gmail.com', 'NIMASA Portal');
            $mail->addAddress($email);

            // Content
            $reset_link = "http://localhost/NIMASA/mailer/reset_password.php?token=$token&email=" . urlencode($email);
            
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "
                <h3>Reset Your Password</h3>
                <p>We received a request to reset your password for the NIMASA Portal.</p>
                <p>Click the link below to set a new password. This link expires in 1 hour.</p>
                <a href='$reset_link' style='background: #2563eb; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Reset Password</a>
                <p>If you did not request this, please ignore this email.</p>";

            $mail->send();
            $message = "A reset link has been sent to your email. Please check your inbox.";
            $statusClass = "block";
        } catch (Exception $e) {
            $message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            $alertType = "bg-red-100 text-red-700 border-red-200";
            $statusClass = "block";
        }
    } else {
        // We show the same success message even if email isn't found for security (prevents email harvesting)
        $message = "If that email exists in our system, a link has been sent.";
        $statusClass = "block";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
	<style>
	body{
background-color: rgb(50,50,50);
background-image: url("../images/background.png");
background-attachment: fixed;
background-size: cover;

}
	
	</style>
	 <link rel="icon" href="../images/logo.png" type="image/x-icon">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-96 text-center">
        <h2 class="text-2xl font-bold mb-4">Reset Password</h2>
        
        <div id="status-box" class="<?php echo $statusClass; ?> mb-4 p-3 bg-green-100 text-green-700 rounded border border-green-200">
            <?php echo $message; ?>
            <?php if(isset($reset_link)): ?>
                <br>
				<!--
				<a href="<?php echo $reset_link; ?>" class="text-blue-600 underline font-bold mt-2 inline-block">Simulate Email Link</a>
				-->
            <?php endif; ?>
        </div>

        <form action="" method="POST" class="<?php echo $statusClass == 'block' ? 'hidden' : ''; ?>">
            <p class="text-gray-600 mb-6">Enter your email address to receive a password reset link.</p>
            <input type="email" name="email" required placeholder="name@company.com" 
                   class="w-full p-3 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded font-semibold hover:bg-blue-700 transition">
                Send Reset Link
            </button>
        </form>
    </div>
</body>
</html>
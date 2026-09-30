<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

$mail = new PHPMailer(true);

try {
    // 2. Server Settings for High Deliverability
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    
   
    $mail->Username   = 'xeronstd@gmail.com'; 
    
    
    $mail->Password   = 'njmm bqup zqyf apxr'; 
    
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

  
    $mail->setFrom('xeronstd@gmail.com', 'NIMASA Project System');
    $mail->addAddress('cysterstudio@gmail.com');
    $mail->addReplyTo('xeronstd@gmail.com', 'Information');

   
    $mail->isHTML(true);
    $mail->Subject = 'NIMASA System Notification - Successful Setup';
    $mail->Body    = "
        <div style='font-family: Arial; border: 1px solid #ddd; padding: 20px;'>
            <h2 style='color: #2e7d32;'>Mail Sent Successfully</h2>
            <p>This is a test email from the <b>NIMASA</b> mailer subfolder.</p>
            <p>Sent at: " . date('Y-m-d H:i:s') . "</p>
        </div>";
    $mail->AltBody = 'This is the plain text version for non-HTML mail clients';

    $mail->send();
    echo '<h3>Success! Mail has been sent to the inbox.</h3>';

} catch (Exception $e) {
    echo "<h3>Error: Message could not be sent.</h3>";
    echo "Mailer Error details: {$mail->ErrorInfo}";
}
?>
<?php
session_start();
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include '../php/db_connect.php'; 

$today = new DateTime();
$notificationWindow = (new DateTime())->modify('+3 months'); 
$uploadDir = '../uploads/'; 

echo "<h3>NIMASA Portal Maintenance</h3>";
echo "Current Date: " . $today->format('Y-m-d') . "<br><hr>";

// --- PART 1: BUILD VALID FILE LIST ---
$validFiles = [];
try {
    $cabotageQuery = $pdo->query("SELECT * FROM cabotage");
    while ($r = $cabotageQuery->fetch()) {
        for ($i=1; $i<=34; $i++) { if (!empty($r["dat$i"])) $validFiles[] = basename($r["dat$i"]); }
        for ($i=1; $i<=10; $i++) { if (!empty($r["pay$i"])) $validFiles[] = basename($r["pay$i"]); }
        if (!empty($r['tax_clearance']))   $validFiles[] = basename($r['tax_clearance']);
        if (!empty($r['manning_liscense'])) $validFiles[] = basename($r['manning_liscense']);
        if (!empty($r['maritime_labour']))  $validFiles[] = basename($r['maritime_labour']);
    }
    $msgQuery = $pdo->query("SELECT * FROM msg");
    while ($m = $msgQuery->fetch()) {
        for ($i=1; $i<=20; $i++) { if (!empty($m["attach$i"])) $validFiles[] = basename($m["attach$i"]); }
    }
} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}

$validFiles = array_unique(array_filter($validFiles));
if (empty($validFiles)) { die("Safety Gate: No files in DB. Cleanup aborted."); }

// --- PART 2: FOLDER CLEANUP ---
if (is_dir($uploadDir)) {
    $filesInFolder = array_diff(scandir($uploadDir), array('.', '..'));
    foreach ($filesInFolder as $currentFile) {
        if (!in_array($currentFile, $validFiles)) {
            $filePath = $uploadDir . $currentFile;
            if (is_file($filePath)) {
                 unlink($filePath); 
                echo "Cleanup: Identified for deletion: $currentFile<br>";
            }
        }
    }
}

// --- PART 3: EXPIRY CHECK & EMAILS ---
try {
    $stmt = $pdo->query("SELECT email, last_modified, dat1, dat2, status, type FROM cabotage");
    $emailCount = 0;

    while ($row = $stmt->fetch()) {
		
		if ($row['status'] != "1.00") {
            continue; 
        }
		
		
        if (empty($row['last_modified'])) continue;
		
		

        $lastModified = new DateTime($row['last_modified']);
        $expiryDate = clone $lastModified;
        $expiryDate->modify('+1 year'); 

        if ($expiryDate <= $notificationWindow) {
            
            $appTypes = [1 => "Nigerian Waiver", 2 => "Foreign Waiver", 3 => "Bareboat Waiver", 4 => "Joint-Venture Waiver"];
            $typeName = isset($appTypes[$row['type']]) ? $appTypes[$row['type']] : "General Cabotage";
            
            $details = [
                'vessel'  => !empty($row['dat2']) ? $row['dat2'] : "N/A",
                'company' => !empty($row['dat1']) ? $row['dat1'] : "N/A",
                'type'    => $typeName
            ];

            if ($expiryDate <= $today) {
                $status = "EXPIRED";
                $timeLeft = "already expired on " . $expiryDate->format('Y-m-d');
            } else {
                $status = "EXPIRING SOON";
                $interval = $today->diff($expiryDate);
                $timeLeft = ($interval->m >= 1) ? $interval->m . " month(s)" : $interval->days . " day(s)";
            }

            // Attempt to send
            if (sendExpiryMail($row['email'], $timeLeft, $status, $details)) {
                echo "<span style='color:green;'>SUCCESS:</span> Mail sent to {$row['email']} (Vessel: {$details['vessel']})<br>";
                $emailCount++;
            } else {
                echo "<span style='color:red;'>FAILED:</span> Could not send mail to {$row['email']}<br>";
            }
        }
    }
    
    if ($emailCount == 0) echo "No emails were sent.<br>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


function sendExpiryMail($email, $timeLeft, $status, $details) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'xeronstd@gmail.com';
        $mail->Password   = 'njmm bqup zqyf apxr'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
		$mail->Port       = 465;                         

        $mail->setFrom('xeronstd@gmail.com', 'NIMASA Portal');
        $mail->addAddress($email);
        $mail->isHTML(true);
        
        $mail->Subject = "NIMASA EXPIRATION NOTICE ";
        $mail->Body = "
		
            <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #eee;'>
			<img src='https://nimasa.gov.ng/wp-content/uploads/2019/08/NIMASSA-LOGO.png' style='width:100%;'></img>
			<br/>
                <h1 style='color: #004a99;'>NIMASA Cabotage Expiration Notification</h1>
                <p>Application for vessel: <strong>" . htmlspecialchars($details['vessel']) . "</strong></p>
                <p><strong>Company:</strong> " . htmlspecialchars($details['company']) . "<br>
                <strong>Type:</strong> " . htmlspecialchars($details['type']) . "<br>
                <strong>Status:</strong> <span style='color:red;'>$status ($timeLeft)</span></p>
                <p>Please log in to the portal to update your application.</p>
				<br/>
				<center><p>------------do not reply to this mail------------</p></center>
            </div>";
        
        $mail->send();
        return true; 
    } catch (Exception $e) {
        
        echo "Mailer Error for $email: " . $mail->ErrorInfo . "<br>";
        return false;
    }
}
?>
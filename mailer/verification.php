<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify | NIMASA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); text-align: center; max-width: 400px; border-top: 5px solid #0056b3; }
        .resend-btn { color: #0056b3; background: none; border: none; font-weight: 600; cursor: pointer; margin-top: 20px; font-size: 14px; }
        .resend-btn:disabled { color: #cbd5e1; cursor: not-allowed; }
    </style>
</head>
<body onload="sendMail()">

<div class="card">
    <h1>Verify your identity</h1>
    <p>A link was sent to <b><?php echo $_SESSION['temp_verify_email'] ?? 'your email'; ?></b>. Please check your inbox.</p>
    
    <button id="resendBtn" class="resend-btn" onclick="handleResend()" disabled>
        Resend email <span id="timer">(60s)</span>
    </button>
</div>

<script>
    async function sendMail() {
        try {
            const response = await fetch('send_mail.php', { method: 'POST' });
            const result = await response.json();
            console.log(result.status === 'success' ? "Mail Sent" : "Error: " + result.message);
        } catch (e) { console.error("Network Error"); }
    }

    let timeLeft = 60;
    const timer = document.getElementById('timer');
    const btn = document.getElementById('resendBtn');

    setInterval(() => {
        if (timeLeft > 0) {
            timeLeft--;
            timer.innerText = "(" + timeLeft + "s)";
        } else {
            btn.disabled = false;
            timer.innerText = "";
        }
    }, 1000);

    function handleResend() {
        sendMail();
        alert("Verification link resent!");
        timeLeft = 60;
        btn.disabled = true;
    }
</script>
</body>
</html>
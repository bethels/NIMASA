async function sendMail() {
    console.log("Requesting mailer to send to session user...");
    try {
        const response = await fetch('send_mail.php', {
            method: 'POST' // No body needed because PHP uses the session!
        });
        const result = await response.json();
        console.log(result.message);
    } catch (e) {
        console.error("Connection Error");
    }
}
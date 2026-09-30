function register() {
    const email = document.getElementById('reg_email').value;
    const password = document.getElementById('reg_password').value;
    const msgDiv = document.getElementById('msg');

    if (!email || !password) {
        msgDiv.innerText = "Please fill all fields.";
        return;
    }

    const formData = new FormData();
    formData.append('email', email);
    formData.append('password', password);

    fetch('register.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        msgDiv.style.color = result.status === "success" ? "green" : "red";
        msgDiv.innerText = result.message;
        if(result.status === "success") {
            // Optional: Redirect to login after 2 seconds
           // setTimeout(() => window.location.href = "login.html", 2000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        msgDiv.innerText = "Connection error.";
    });
}
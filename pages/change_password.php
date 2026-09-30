<?php 
require '../php/check_session_neutral.php'; 
require '../php/db_connect.php';

$user_email = $_SESSION['user_email'];


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
	
	
<link rel="icon" href="../images/logo.png" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .password-card { max-width: 400px; margin: 80px auto; border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .btn-primary { background-color: #4e73df; border: none; }
		
		

.dashtop{
display: flex;
position:fixed;
width:100vw;
height: 65px;
top:0vh;
left:0vw;
background-color: rgb(50,57,240);

background-image: linear-gradient(to bottom,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185) ),
					linear-gradient(to right,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185) );


background-blend-mode: multiply;

z-index: 1000;

box-shadow: 0px 0px 20px rgba(0,0,0,0.8);	
	
}

.logo{
text-decoration:none;
background-color:white;
position:relative;
width: 14vw;
height: 65px;

}

.logofont{
font-size: 20px;
font-weight: 600;
font-family: segoe ui, sans-serif, Berlin Sans FB,arial;
color: black;
position:relative;
left: 30px;

	
}

.logo:hover{
	
cursor:pointer;	
	
}

.dashmenu{
display:flex;
font-size: 20px;
font-weight: 400;
font-family: segoe ui, sans-serif, Berlin Sans FB,arial;
color: white;
position:relative;
text-decoration:none;
top:0px;
margin: 7px;
background:none;
border:none;
	
}



 .logo:hover { opacity: 0.8; }
		 .logout:hover { opacity: 0.7; }
		
        .dashmenu:hover { opacity: 0.7; }


.username {
    margin-left: auto; 
    text-decoration: none;
    height: 56px;
    display: flex;
    align-items: center;
    padding: 0 15px;
  
    color: white;
}


.username:hover{
	
cursor:pointer;
	
}

.logout {
    font-size: 22px;
    font-family: "Segoe UI", sans-serif;
    color: white;
    text-decoration: none;
    margin: 6px 20px 6px 10px; 
	
}

    </style>
	
	
</head>
<body>


<div class="dashtop">

<a href="../index.html" class="logo">
<div class="logo">

<img src="../images/logo.png" style="position:relative; height:100%; left: 20px; ">
<span class="logofont"> NIMASA</span>

</div>

</a>

<a href="dashboard.php" class="dashmenu"><span class="dashmenu"> Dashboard </span></a>

<a href="applicationMid.php" class="dashmenu"><span class="dashmenu"> Applications </span></a>

<a href="accounts.php" class="dashmenu"><span class="dashmenu"> My Account </span></a>

<!--<a href="" class="dashmenu"><span class="dashmenu"> My Waivers </span></a>-->

<a href="helpMid.php" class="dashmenu"><span class="dashmenu"> Help </span></a>



<span class="username" id="username"><span class="logout"><?php echo $_SESSION['user_email']; ?></span></span>

<a href="../php/logout.php" class="logout"><span class="logout"> Logout </span></a>


</div>


<br/><br/>
<div class="container">
    <div class="card password-card">
        <div class="card-body p-4">
            <h3 class="text-center mb-4">Update Password</h3>
            <form id="passwordForm">
			<input type="text" name="username" value="<?php echo $_SESSION['user_email']; ?>" autocomplete="username" style="display:none;">
                <div class="mb-3">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="old_password" class="form-control" required>
                </div>
                <hr>
                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" id="new_password" name="new_password" class="form-control" required onkeyup="checkStrength()">
                    <div id="strength-text" class="form-text mt-1"></div>
                    <div class="progress mt-2" style="height: 5px;">
                        <div id="strength-bar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2">Update Password</button>
            </form>
        </div>
    </div>
</div>

<script>
function checkStrength() {
    const password = document.getElementById('new_password').value;
    const bar = document.getElementById('strength-bar');
    const text = document.getElementById('strength-text');
    let strength = 0;
    if (password.length >= 8) strength += 25;
    if (password.match(/[A-Z]/)) strength += 25;
    if (password.match(/[0-9]/)) strength += 25;
    if (password.match(/[^A-Za-z0-9]/)) strength += 25;
    bar.style.width = strength + "%";
    if (strength <= 50) { bar.className = "progress-bar bg-danger"; text.innerHTML = "Weak"; }
    else if (strength == 75) { bar.className = "progress-bar bg-warning"; text.innerHTML = "Medium"; }
    else { bar.className = "progress-bar bg-success"; text.innerHTML = "Strong"; }
}

document.getElementById('passwordForm').addEventListener('submit', function(e) {
    e.preventDefault(); 
    const formData = new FormData(this);
    formData.append('submit_change', true); 

    fetch('../php/update_password.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text()) // Get text first to catch PHP errors
    .then(text => {
        try {
            const data = JSON.parse(text);
            if (data.status === 'success') {
                alert("✅ " + data.message);
                window.location.href = 'dashboard.php'; 
            } else {
                alert("❌ " + data.message); 
            }
        } catch (err) {
            console.error("PHP Response was not JSON:", text);
            alert("An unexpected server error occurred. Check console.");
        }
    });
});
</script>
</body>
</html>
</html>
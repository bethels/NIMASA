
<?php 
require '../php/check_session.php'; 


?>




<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width" initial-scale="1.0">



<link rel="icon" href="../images/logo.png" type="image/x-icon">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NIMASA | Inquiry Form</title>
    <style>
        :root {
            --primary-blue: #1a5276;
            --secondary-green: #2e7d32;
            --bg-light: #f4f7f6;
        }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: var(--bg-light); display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .container { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 500px; border-top: 5px solid var(--primary-blue);
			position:relative; top: 80px;}
        h2 { color: var(--primary-blue); margin-top: 0; text-align: center; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; }
        input, textarea, select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; font-size: 16px; transition: border-color 0.3s; }
        input:focus, textarea:focus { outline: none; border-color: var(--primary-blue); }
        button { width: 100%; padding: 14px; background: var(--primary-blue); color: white; border: none; border-radius: 6px; font-size: 16px; font-weight: bold; cursor: pointer; transition: opacity 0.3s; }
        button:hover { opacity: 0.9; }
        #statusMessage { margin-top: 20px; padding: 15px; border-radius: 6px; display: none; text-align: center; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
		
		
		
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
top:-25px;
	
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

 .logo:hover { opacity: 0.8; }
		 .logout:hover { opacity: 0.7; }
		
        .dashmenu:hover { opacity: 0.7; }


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











<div class="container">
    <h2>NIMASA Inquiry</h2>
    <form id="inquiryForm">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" required placeholder="John Doe">
        </div>
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" required placeholder="john@example.com">
        </div>
        <div class="form-group">
            <label>Inquiry Type</label>
            <select name="type">
                <option value="General">General Inquiry</option>
                <option value="Technical">Technical Support</option>
                <option value="Project">Project Collaboration</option>
            </select>
        </div>
        <div class="form-group">
            <label>Message</label>
            <textarea name="message" rows="5" required placeholder="How can we help you?"></textarea>
        </div>
        <button type="submit" id="submitBtn">Send Inquiry</button>
    </form>
    <div id="statusMessage"></div>
</div>

<script>
    document.getElementById('inquiryForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const btn = document.getElementById('submitBtn');
        const status = document.getElementById('statusMessage');
        const formData = new FormData(this);

        btn.disabled = true;
        btn.innerText = 'Sending...';
        status.style.display = 'none';

        fetch('../mailer/process_inquiry.php', {
    method: 'POST',
    body: formData
})
.then(res => {
    // Check if the server actually sent a 200 OK response
    if (!res.ok) throw new Error('Network response was not ok');
    return res.json(); 
})
.then(data => {
    status.style.display = 'block';
    if (data.status === 'success') {
        status.className = 'success';
        status.innerText = data.message;
        alert("Message Successfully sent! If you don't see it, please check your spam folder");
        document.getElementById('inquiryForm').reset();
		
		
		btn.disabled = false;
        btn.innerText = 'Send';
       
		
		
    } else {
        status.className = 'error';
        status.innerText = data.message;
        console.error("PHP Error:", data.message);
    }
})
.catch(err => {
    status.style.display = 'block';
    status.className = 'error';
    status.innerText = 'Connection Error: ' + err.message;
    console.error("Fetch Error:", err);
});

	});

</script>

</body>
</html>
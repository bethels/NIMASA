

function start() {
    const mail = document.getElementById('mail').value;
    const passd = document.getElementById('pswrd').value;

    const formData = new FormData();
    formData.append('user_email', mail);
    formData.append('password', passd);
	if(document.getElementById('myCheck').checked){
    formData.append('remember_me_checked', 'true');
}


    fetch('../php/login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.status === "success") {
            console.log("User Details:", result.data);

           
	
		   
		   
		   
		   
            if (result.data.file_path === "admin_admin") { 
                window.location.href = "adminpanel.php";
            } 
			else if (result.data.file_path === "admin_admin_dir") { 
                window.location.href = "adminpanel.php";
            }
			
			else if (result.data.file_path === "admin_admin1") { 
                window.location.href = "adminpanel1.php";
            } else if (result.data.file_path === "admin_admin2") { 
                window.location.href = "adminpanel2.php";
            }else {
                window.location.href = "../pages/dashboard.php"; 
            }
            
        } 
		else if (result.status === "unverified") {
  
    window.location.href = "../mailer/verification.php";
}
		
		else {
            alert(result.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("An error occurred during login.");
    });


}




document.getElementById('mail').onkeypress = (e) => { if(e.key==='Enter') start(); };
document.getElementById('pswrd').onkeypress = (e) => { if(e.key==='Enter') start(); };












document.addEventListener('DOMContentLoaded', () => {
    checkRememberMe();
});

function checkRememberMe() {
    fetch('../php/check_remember.php')
    .then(response => response.json())
    .then(data => {
        if (data.status === "found") {
           
            document.getElementById('mail').value = data.email;
            
           
            document.getElementById('myCheck').checked = true;

           
        }
    })
    .catch(error => console.error('Error checking remember status:', error));
}
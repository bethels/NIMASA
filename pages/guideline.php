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
    <title>NIMASA | Guidelines & Procedures</title>
    <style>
        :root {
            --primary-blue: #1b32b9;
            --secondary-blue: #435aff;
            --text-dark: #333;
            --bg-light: #f4f7f6;
            --white: #ffffff;
        }

        body { font-family: 'Segoe UI', sans-serif; background: var(--bg-light); margin: 0; padding-top: 80px; color: var(--text-dark); }
        
        /* Navbar Styling (Matching your Dashboard) */
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

        /* Content Layout */
        .wrapper { display: flex; max-width: 1200px; margin: 20px auto; gap: 20px; padding: 0 20px; }
        
        /* Sidebar */
        .sidebar { width: 250px; background: var(--white); padding: 20px; border-radius: 8px; height: fit-content; position: sticky; top: 100px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .sidebar h3 { font-size: 16px; text-transform: uppercase; color: #888; margin-bottom: 15px; }
        .side-link { display: block; padding: 10px; color: var(--primary-blue); text-decoration: none; border-radius: 4px; margin-bottom: 5px; }
        .side-link:hover, .side-link.active { background: var(--primary-blue); color: white; }

        /* Main Content */
        .main-content { flex: 1; background: var(--white); padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .search-box { width: 100%; padding: 12px; margin-bottom: 30px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; }
        
        .guideline-section { margin-bottom: 40px; border-bottom: 1px solid #eee; padding-bottom: 20px; }
        .guideline-section h2 { color: var(--primary-blue); border-left: 5px solid var(--primary-blue); padding-left: 15px; }
        .step-card { background: #f9f9f9; padding: 15px; border-radius: 6px; margin: 10px 0; border-left: 3px solid #ccc; }
        
        @media (max-width: 768px) { .wrapper { flex-direction: column; } .sidebar { width: 100%; position: static; } }
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














<div class="wrapper">
    <aside class="sidebar">
        <h3>Categories</h3>
        <a href="#waivers" class="side-link active">Cabotage Waivers</a>
        <a href="#licensing" class="side-link">Vessel Licensing</a>
        <a href="#documents" class="side-link">Document Specs</a>
    </aside>

    <main class="main-content">
        <input type="text" id="guidelineSearch" class="search-box" placeholder="Search guidelines (e.g., 'Waiver', 'Registry')...">

        <div id="waivers" class="guideline-section">
            <h2>Cabotage Waiver Application</h2>
            <p>Guidelines for processing waivers for foreign-owned vessels operating in Nigerian waters.</p>
            <div class="step-card">
                <strong>Step 1: Check Eligibility</strong>
                <p>Ensure the vessel type is not currently restricted under the local content schedule.</p>
            </div>
            <div class="step-card">
                <strong>Step 2: Document Upload</strong>
                <p>Upload a clear Bill of Sale and Ship Registry Certificate (Max 5MB, PDF/JPG).</p>
            </div>
        </div>

        <div id="licensing" class="guideline-section">
            <h2>Coastal Vessel Licensing</h2>
            <p>Procedures for obtaining annual licenses for commercial operations.</p>
            <div class="step-card">
                <strong>Requirement: Inspection</strong>
                <p>Vessels must undergo a mandatory physical survey by NIMASA inspectors before license issuance.</p>
            </div>
        </div>
    </main>
</div>

<script>
    // Simple Search Filter
    document.getElementById('guidelineSearch').addEventListener('input', function(e) {
        let filter = e.target.value.toLowerCase();
        let cards = document.querySelectorAll('.step-card');
        
        cards.forEach(card => {
            let text = card.innerText.toLowerCase();
            card.style.display = text.includes(filter) ? 'block' : 'none';
        });
    });

    // Smooth Scroll for Sidebar
    document.querySelectorAll('.side-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
            
            // Toggle active class
            document.querySelectorAll('.side-link').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });
</script>

</body>
</html>
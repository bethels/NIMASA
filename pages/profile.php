<?php
require '../php/check_session.php'; 


$host = "localhost";
$user = "root";
$pass = "";
$dbname = "nimasa";

$conn = new mysqli($host, $user, $pass, $dbname);
// Assume you have the user's ID or Email in a session
$user_email = $_SESSION['user_email']; 

$stmt = $conn->prepare("SELECT * FROM nimdat WHERE email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();

echo "<script>const userData = " . json_encode($userData) . ";</script>";
?>








<!DOCTYPE html>
<head>

<title>Company Profile</title>
<link rel="stylesheet" href="../css/profile.css">
<meta name="viewport" content="width=device-width" initial-scale="1.0">

<link rel="icon" href="../images/logo.png" type="image/x-icon">
</head>

<html>
<script src="../JS/profile.js"></script>
<body >

<div class="topper">

<center class="jaba"> 
<div class="circleimg1">
<a href="../index.html"><img src="../images/logo.png" class="middle"></a>
</div>
<span class="BoldFont">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nigerian Maritime Administration and Safety Agency</span>

</center>

</div>



<div class="portal2">

<p class="BoldFont1">&nbsp&nbsp Company Profile</p>





<div class="account" id="accounts">

<div class="main-container">
    <div class="tabs">
       <button id="cpf" class="tab active" onclick="compProf()">Company Profile</button>
    <button id="cdd" class="tab" onclick="compAdd()">Company Address</button>
    <button id="cdr" class="tab" onclick="compDir()">Company Directors</button>
    </div>





    <div  id="profileSection"  class="form-wrapper">
        <span class="form-title">Company Information</span>
        <hr>
        
        <p class="required-notice">Fields with <span>*</span> are required</p>

        <form id="profileForm">
            <div class="input-grid">
                <div class="input-group">
                    <label>COMPANY NAME <span>*</span></label>
                    <input type="text"  id="company_name" placeholder="Company name...">
                </div>
                <div class="input-group">
                    <label>BUSINESS TYPE <span>*</span></label>
                    <input type="text" id="business_type" placeholder="Business type...">
                </div>
                <div class="input-group">
                    <label>NATIONALITY <span>*</span></label>
                    <select id="country">
                        <option>Nigeria</option>
						<option>Indian</option>
                    </select>
                </div>

                <div class="input-group">
                    <label>CONTACT FIRSTNAME <span>*</span></label>
                    <input type="text" id="first_name" placeholder="First name...">
                </div>
                <div class="input-group">
                    <label>CONTACT LASTNAME <span>*</span></label>
                    <input type="text" id="last_name" placeholder="Last Name...">
                </div>
                <div class="input-group">
                    <label>CONTACT PHONE <span>*</span></label>
                    <input type="text" id="phone" placeholder="Phone number..">
                </div>

                <div class="input-group">
                    <label>YEAR INCORPORATED <span>*</span></label>
                    <input type="number" id="year" placeholder="Year...">
                </div>
                <div class="input-group">
                    <label>RC NUMBER <span>*</span></label>
                    <input type="text" id="rc" placeholder="Rc no...">
                </div>
                <div class="input-group">
                    <label>TIN <span>* (Tax Identification Number)</span></label>
                    <input type="text" id="tin" placeholder="TIN">
                </div>
            </div>

            <div class="button-container">
                <button type="submit" class="submit-btn">Update Profile</button>
            </div>
        </form>
    </div>







 <div  id="addressSection"  class="form-wrapper" style="display:none;">
        <span class="form-title">Company Address</span>
        <hr>
        
        <p class="required-notice">Fields with <span>*</span> are required</p>

        <form id="profileForm">
            <div class="input-grid">
                <div class="input-group">
                    <label>ADRESS 1 <span>*</span></label>
                    <input type="text"  id="address1" placeholder="Adress 1...">
                </div>
                <div class="input-group">
                    <label>ADDRESS 2 </label>
                    <input type="text" id="address2" placeholder="Address 2...">
                </div>
                
				

                <div class="input-group">
                    <label>CITY <span>*</span></label>
                    <input type="text" id="city" placeholder="City...">
                </div>
                <div class="input-group">
                    <label>POSTAL CODE </label>
                    <input type="text" id="postal" placeholder="Postal Code...">
                </div>
                <div class="input-group">
                    <label>COUNTRY <span>*</span></label>
                    
					<select id="address_country">
                        <option>Nigeria</option>
						<option>Indian</option>
                    </select>
                </div>

                <div class="input-group">
                    <label>STATE <span>*</span></label>
                   <select id="state">
                        <option>Lagos</option>
                    </select>
                </div>
                
            </div>

            <div class="button-container">
                <button type="submit" class="submit-btn">Update Address</button>
            </div>
        </form>
    </div>








 <div  id="directorSection"  class="form-wrapper" style="display:none;">
        <span class="form-title">Company Director(s)</span> 
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<button onclick="AddDir()" class="adddir">Add New Director</button>
		
		<button onclick="deleteCurrentDirector()" class="remdir">Delete Current Director</button>
        <hr>
        
        <p class="required-notice">Fields with <span>*</span> are required</p>

        <form id="profileForm">
           
		   
<div class="form-row-wrapper" style="display: flex; width: 100%; align-items: flex-start;">

    <div class="sidebar-info" style="width: 30%; padding: 10px; background-color: none;">
        <p class="required-notice" style="width:100%;">List of directors</p>
		<p id="list_of_dir"></p>
		
		
    </div>

		  <div class="input-grid" style="width:70%; position:relative; left: 0%; display: flex;">
        <div class="input-group" style="flex: 1 1 200px;">
		   <span class="required-notice" style="width:100%;">Company Director</span>
		   
		   
		   
		   
                <div class="input-group">
                    <label>FIRST NAME <span>*</span></label>
                    <input type="text"  id="dir_name1" placeholder="First name...">
                </div>
                <div class="input-group">
                    <label>LAST NAME <span>*</span> </label>
                    <input type="text" id="dir_name2" placeholder="Last name...">
                </div>
                
				

               
                <div class="input-group">
                    <label>PHONE NO <span>*</span> </label>
                    <input type="text" id="dir_phone" placeholder="Phone number...">
                </div>
                <div class="input-group">
                    <label>NATIONALITY <span>*</span></label>
                    <select id="dir_nation">
                        <option>NIGERIA</option>
                    </select>
                </div>

                 <div class="input-group">
                    <label>ADRESS 1 <span>*</span></label>
                    <input type="text"  id="dir_address1" placeholder="Adress 1...">
                </div>
                <div class="input-group">
                    <label>ADDRESS 2 </label>
                    <input type="text" id="dir_address2" placeholder="Address 2...">
                </div>
                
				

                <div class="input-group">
                    <label>CITY <span>*</span></label>
                    <input type="text" id="dir_city" placeholder="City...">
                </div>
                <div class="input-group">
                    <label>POSTAL CODE </label>
                    <input type="text" id="dir_postal" placeholder="Postal Code...">
                </div>
                <div class="input-group">
                    <label>COUNTRY <span>*</span></label>
                   
					<select id="dir_country">
                        <option>Nigeria</option>
						<option>Indian</option>
                    </select>
                </div>

                <div class="input-group">
                    <label>STATE <span>*</span></label>
                   <select id="dir_state" required>
                        <option>Lagos</option>
                    </select>
                </div>
                
            </div>



</div>

</div>
		
		   
		   
		   

            <div class="button-container">
                <button type="submit" class="submit-btn">Update Director</button>
            </div>
        </form>
    </div>






</div>

</div>







<br/>
&nbsp;



</div>




<div class="bottom">
<center class="BoldFont2">
© NIMASA|Nigerian Maritime Administration and Safety Agency
</center>
</div>



















<div id="addDirModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:1000; overflow-y:auto;">
    <div style="background:white; margin:5% auto; padding:20px; width:50%; border-radius:8px; position:relative;">
        <h3>Add New Director</h3>
        <span onclick="closeAddDir()" style="position:absolute; right:20px; top:10px; cursor:pointer; font-size:24px;">&times;</span>
        
        <div id="addDirForm">
            <div class="input-group">
                <label>FIRST NAME <span>*</span></label>
                <input type="text" id="new_dir_name1" placeholder="First name...">
            </div>
            <div class="input-group">
                <label>LAST NAME <span>*</span></label>
                <input type="text" id="new_dir_name2" placeholder="Last name...">
            </div>
            <div class="input-group">
                <label>PHONE NO <span>*</span></label>
                <input type="text" id="new_dir_phone" placeholder="Phone number...">
            </div>
            <div class="input-group">
                <label>NATIONALITY <span>*</span></label>
                <select id="new_dir_nation"></select>
            </div>
            <div class="input-group">
                <label>ADDRESS 1 <span>*</span></label>
                <input type="text" id="new_dir_address1" placeholder="Address 1...">
            </div>
            <div class="input-group">
                <label>ADDRESS 2</label>
                <input type="text" id="new_dir_address2" placeholder="Address 2...">
            </div>
            <div class="input-group">
                <label>CITY <span>*</span></label>
                <input type="text" id="new_dir_city" placeholder="City...">
            </div>
            <div class="input-group">
                <label>POSTAL CODE</label>
                <input type="text" id="new_dir_postal" placeholder="Postal Code...">
            </div>
            <div class="input-group">
                <label>COUNTRY <span>*</span></label>
                <select id="new_dir_country"></select>
            </div>
            <div class="input-group">
                <label>STATE <span>*</span></label>
                <select id="new_dir_state"></select>
            </div>
            
            <div class="button-container" style="margin-top:20px;">
                <button type="button" onclick="saveNewDirector()" class="submit-btn">Save Director</button>
            </div>
        </div>
    </div>
</div>






</body>
</html>
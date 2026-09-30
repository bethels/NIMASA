<?php
require '../php/check_session_admin.php'; 

?>

<!DOCTYPE html>
<head>

<title>Memo</title>
<link rel="stylesheet" href="../css/memo.css">
<meta name="viewport" content="width=device-width" initial-scale="1.0">

</head>

<html>
<body>
<script src="../JS/memo.js"></script>
<div class="topper">

<center class="jaba"> 
<div class="circleimg1">
<a href="../others/adminpanel.php"><img src="../images/logo.png" class="middle"></a>
</div>
<span class="BoldFont">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nigerian Maritime Administration and Safety Agency</span>

</center>

</div>



<div class="portal2">

<p class="BoldFont1">&nbsp&nbsp General Message</p>





<div class="account" id="accounts">




 <div id="msgArea" style="display: block; margin-top: 10px; ">
    <center> 
    <textarea id="adminNote" placeholder="Type your message here..." rows="4" class="edit-box"></textarea>
     

		
<div id="upload-container" style="border: 1px solid #ccc; padding: 15px; width: 95%;">
    <div id="file-list"></div>
    
    <input type="file" id="file-input" style="display: none;">
   
<center>   
<br/>
    <button type="button" class="back" id="attach-btn" onclick="document.getElementById('file-input').click()">
        Attach File
    </button>
    </center>
  
   <div class="msg-actions">
           <button type="button" onclick="sendMessage()" class="btn-send">Send</button> </center>
        </div>

		
</div>



	 
       
		
		<br/>
		<br/>
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



</body>
</html>
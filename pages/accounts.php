
<?php 
require '../php/check_session.php'; 
require '../php/db_connect.php';

$user_email = $_SESSION['user_email'];


?>



<!Doctype html>
<head>


<title>My Account</title>

<link rel="stylesheet" href="../css/appmid.css">

<meta name="viewport" content="width=device-width" initial-scale="1.0">


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


<link rel="icon" href="../images/logo.png" type="image/x-icon">
</head>

<html>



<body >




<script src="../JS/dashboard.js"></script>

<div class="dashtop">

<a href="dashboard.php" class="logo">
<div class="logo">

<img src="../images/logo.png" style="position:relative; height:100%; left: 20px; ">
<span class="logofont"> NIMASA</span>

</div>

</a>

<a href="dashboard.php" class="dashmenu"><span class="dashmenu"> Dashboard </span></a>

<a href="applicationMid.php" class="dashmenu"><span class="dashmenu"> Applications </span></a>

<a href="accounts.php" class="dashmenu"><span class="dashmenu"> My Accounts </span></a>


<a href="" class="dashmenu"><span class="dashmenu"> Help </span></a>



<span class="username" id="username"><span class="logout"><?php echo $_SESSION['user_email']; ?></span></span>

<a href="../php/logout.php" class="logout"><span class="logout"> Logout </span></a>


</div>




<div class="semi">


<div class="middle" >

<div class="note">
<br/><br/>
<center class="whitefont">Select An Operation<center>
<br/>

<!--cards-->
<a href="documents.php">
<div class="card">
<div class="hollow-circle">
<span id="applications">
<i class="fas fa-file-invoice" style="font-size:150px;  position: relative; top: 20px;"></i>
</span>

<div class="cardlow"> 
<center class="whitefont1">My Documents</center>
</div>
</div>
</div>
</a>

<!--cards-->
<a href="profile.php">
<div class="card">
<div class="hollow-circle">
<span id="processing"><i class="fas fa-building" style="font-size:150px;  position: relative; top: 20px; color: rgb(100,250,50);"></i></span>

<div class="cardlow"> 
<center class="whitefont1">Company Profile</center>
</div>
</div>
</div>
</a>

<!--cards-->
<a href="change_password.php">
<div class="card">
<div class="hollow-circle">
<span id="licensed"><i class="fas fa-key" style="font-size:150px;  position: relative; top: 20px;"></i></span>

<div class="cardlow"> 
<center class="whitefont1">Change Password</center>
</div>
</div>
</div>
</a>

<!--cards-->
<a href="messages.php" >
<div class="card">
<div class="hollow-circle">
<span id="licensed" ><i class="fas fa-envelope" style="font-size:150px;  position: relative; top: 20px; color: rgb(250,180,50);"></i></span>

<div class="cardlow"> 
<center class="whitefont1">Messages</center>
</div>
</div>
</div>
</a>















</div>













</div>








<div class="bottom">
<center class="BoldFont2">
© NIMASA|Nigerian Maritime Administration and Safety Agency
</center>
</div>


    <script src="../JS/dashboard.js"></script>


</body>

</html>
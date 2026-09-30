
<?php 
require '../php/check_session.php'; 
require '../php/db_connect.php';

$user_email = $_SESSION['user_email'];


?>



<!Doctype html>
<head>


<title>Applications Dashboard</title>

<link rel="stylesheet" href="../css/appmid.css">

<meta name="viewport" content="width=device-width" initial-scale="1.0">





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
<center class="whitefont">Select Your Application Type<center>
<br/>

<!--cards-->
<a href="appType.php">
<div class="card">
<div class="hollow-circle">
<span id="applications"><img src="../images/new.png" style="width:80%;"></span>

<div class="cardlow"> 
<center class="whitefont1">New</center>
</div>
</div>
</div>
</a>

<!--cards-->
<a href="allApplications.php">
<div class="card">
<div class="hollow-circle">
<span id="processing"><img src="../images/app.png" style="width:70%; position:relative; left: 5%; top: 3%;"></span>

<div class="cardlow"> 
<center class="whitefont1">All Application(s)</center>
</div>
</div>
</div>
</a>

<!--cards-->
<a href="pendingapp.php">
<div class="card">
<div class="hollow-green">
<span id="licensed"><img src="../images/prog.png" style="width:70%;"></span>

<div class="cardlow"> 
<center class="whitefont1">In Progress</center>
</div>
</div>
</div>
</a>

<!--cards-->
<a href="rejectapp.php" >
<div class="card">
<div class="hollow-red">
<span id="licensed" ><img src="../images/reject.png" style="width:70%; position:relative; top: 7%;"></span>

<div class="cardlow"> 
<center class="whitefont1">Rejections</center>
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
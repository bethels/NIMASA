
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
<center class="whitefont">Make A Selection<center>
<br/>

<!--cards-->
<a href="../others/selectionCabotage.php">
<div class="card">
<div class="hollow-circle">
<span id="applications"><img src="../images/newapp.png" style="width:70%; position:relative; top:2%; left:-1%;"></span>

<div class="cardlow"> 
<center class="whitefont1">New Application</center>
</div>
</div>
</div>
</a>

<!--cards-->
<a href="../pages/completeapp.php">
<div class="card">
<div class="hollow-circle">
<span id="processing"><img src="../images/renewapp.png" style="width:65%; position:relative; left: 0%; top: 5%;"></span>

<div class="cardlow"> 
<center class="whitefont1">Renewal Application</center>
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
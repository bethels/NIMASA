<?php
require '../php/check_session.php'; 

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "nimasa";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$session_email = $_SESSION['user_email'] ?? '';


$sql = "SELECT id, email, dat1 FROM cabotage WHERE email = ? AND status = 1";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $session_email);
    $stmt->execute();
    $result = $stmt->get_result(); 
} else {
    $result = false;
}




?>






<!DOCTYPE html>
<head>

<title>My Applications</title>
<link rel="stylesheet" href="../css/accountlist.css">
<meta name="viewport" content="width=device-width" initial-scale="1.0">

</head>

<html>
<body>
<script src="../JS/myApplication.js"></script>
<div class="topper">

<center class="jaba"> 
<div class="circleimg1">
<a href="../index.html"><img src="../images/logo.png" class="middle"></a>
</div>
<span class="BoldFont">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nigerian Maritime Administration and Safety Agency</span>

</center>

</div>



<div class="portal2">

<p class="BoldFont1">&nbsp&nbsp My Applications</p>

<div class="search">

<input type="edit" id="searchbar" placeholder="Search" class="searchedit"></input>

</div>



<div class="Topper">
<button class="sort">Sort-by</button>&nbsp;&nbsp;
<button class="sort">Filter</button>
</div>





<div class="account" id="accounts">





<select size="4" class="list" id="userList" onchange="listSelect()">
    <?php
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $recordId = $row['id']; 
            $email = $row['email'];
            
            $displayName = !empty($row['dat1']) ? $row['dat1'] : 'Unnamed Company'; 
            
            echo "<option value='$recordId'>$displayName ($email)</option>";
        }
    } else {
        echo "<option disabled>You have no rejected application.</option>";
    }
    ?>
</select>






</div>




<div class="accountDET" id="accountDETS">

<p id="accountz" class="fonter"></p><br/>





<br/><br/>



<center><button id="renewal" class="renewal" onclick="renewal()">Renew Application</button></center>

<center><button class="back" onclick="listClose()">CLOSE</button></center>
<br/><br/>
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
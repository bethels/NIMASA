<?php
session_start(); 


if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.html");
    exit();
}


$name = $_SESSION['username'];
$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html>
<body>
    <h1>Welcome to your Dashboard, <?php echo $name; ?></h1>
    <p>Your registered email is: <?php echo $email; ?></p>
    <a href="logout.php">Logout</a>
</body>
</html>
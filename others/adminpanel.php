<?php 
require '../php/check_session_admin.php'; 
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline' https://user-images.githubusercontent.com;");

?>


<?php 
$conn = new mysqli("localhost", "root", "", "nimasa");

$session_email = $_SESSION['user_email'] ?? ''; 
if (!$session_email) { die("Direct access denied. Please log in."); }

$stmt = $conn->prepare("SELECT file_path FROM nimdat WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $session_email);
$stmt->execute();
$roleRow = $stmt->get_result()->fetch_assoc();
$myRole = $roleRow['file_path'] ?? 'user';

$isAdmin = ($myRole === 'admin_admin' || $myRole === 'admin_admin_dir');

// Updated SQL to count unread messages (where replied = 0)
$sql = "SELECT email_user, SUM(is_unread) AS unread_count 
        FROM (
            /* Get all unique users from both sides to build the list */
            SELECT sender AS email_user, 
                   (CASE WHEN replied = 0 THEN 1 ELSE 0 END) AS is_unread 
            FROM chat 
            WHERE sender NOT IN ('admin_admin', 'admin_admin_dir')

            UNION ALL

            SELECT receiver AS email_user, 
                   0 AS is_unread /* We don't count messages received by users as unread for the admin view */
            FROM chat 
            WHERE receiver NOT IN ('admin_admin', 'admin_admin_dir')
        ) AS combined_users 
        GROUP BY email_user 
        ORDER BY email_user ASC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<head>

<title>NIMASA portal system</title>

<link rel="stylesheet" href="../css/stylemin.css">

<meta name="viewport" content="width=device-width" initial-scale="1.0">






<link rel="icon" href="../images/logo.png" type="image/x-icon">




</head>


<html>

<body onload="loader()">








<script src="../chat/chat_logic.js"></script>

<button id="chat-fab" onclick="toggleChat(true)">
    <span style="font-size: 28px; color:white;">&#128489;</span>
</button>

<div id="chat-popup">


<div id="chat-container">
    <div id="header">
        <div> <button id="back-btn" onclick="backAdmin()">◄</button> <strong>NIMASA Messaging</strong><br><small><?php echo $session_email; ?></small>
		<small id="useral"></small>
		</div>
		  <button id="close-chat-btn" onclick="toggleChat(false)">✖</button>
    </div>

    <div id="chat-box"></div>

    <div id="inputer" class="input-bar">
        <input type="text" id="msg-input" placeholder="Type message..." autocomplete="off">
        <button id="send-btn">➤</button>
    </div>
	
	
	
	
	
	
	
	
<div id="contact-list">
    <div style="padding: 20px; text-align: center; color: #999;">Loading conversations...</div>
</div>
	
	
	
	
	
	
	
	
</div>






</div>







<script>
let chadmin=0;

const sessionUser = "<?php echo $session_email; ?>";
const isAdminRole = <?php echo $isAdmin ? 'true' : 'false'; ?>;








//  Admin, the target is the user I am viewing (e.g. gama@gmail.com)
// User, the target is always 'admin_admin'
let targetUser = (typeof trueEmail !== 'undefined') ? trueEmail : "gama@gmail.com"; 
if(!isAdminRole) { targetUser = 'admin_admin'; }



function handleUserClick(email) {
    
    chadmin=0;
targetUser=email;
loadChat();
document.getElementById('back-btn').style.display = "block";
	document.getElementById('inputer').style.display = "flex";
	document.getElementById('chat-box').style.display = "flex";
	document.getElementById('contact-list').style.display = "none";
	
	
	document.getElementById('useral').innerHTML="&nbsp;&nbsp["+email+"]";
	
}



function backAdmin(){
	
	isAdmin();
	
}

function escape(str) {
    if (!str) return "";
    return String(str)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function loadChat() {
    fetch(`../chat/get_chat.php?target=${targetUser}`)
    .then(r => r.json())
    .then(data => {
        const box = document.getElementById('chat-box');
        let newContent = ''; // Build the string first for better performance
        
        data.forEach(m => {
            const isMe = isAdminRole ? (m.sender === 'admin_admin') : (m.sender === sessionUser);
            
            // 1. Determine Display Name
            let displayName;
            if (m.sender === 'admin_admin') displayName = "Admin";
            else if (isMe) displayName = "You";
            else displayName = m.sender;

            const side = isMe ? 'right' : 'left';

            // 2. Escape EVERYTHING before it touches the HTML
            const safeMsg = escape(m.msg);
            const safeSender = escape(displayName);
            const safeDate = escape(m.send_date);
            const safeId = parseInt(m.id); // Convert to number for total safety

            newContent += `
                <div class="msg ${side}">
                    <span class="sender-name">${safeSender}</span>
                    <div class="msg-text">${safeMsg}</div> 
                    <div class="footerz">
                        <span>${safeDate}</span>
                        ${isMe ? `<button class="del-btn" onclick="deleteMsg(${safeId})">🗑</button>` : ''}
                    </div>
                </div>`;
        });

        box.innerHTML = newContent;
        box.scrollTop = box.scrollHeight;
    });
}









function sendMsg() {
    const input = document.getElementById('msg-input');
    const text = input.value.trim();
    if (!text) return;

    const fd = new FormData();
    fd.append('msg', text);
    fd.append('target', targetUser); 

    fetch('../chat/send_chat.php', { method: 'POST', body: fd })
    .then(() => {
        input.value = '';
        loadChat();
    });
}

function deleteMsg(id) {
    if(!confirm("Delete?")) return;
    const fd = new FormData();
    fd.append('id', id);
    fetch('../chat/delete_chat.php', { method: 'POST', body: fd }).then(() => loadChat());
}


function isAdmin(){
	if(isAdminRole){
	chadmin=-1;

document.getElementById('useral').innerHTML="";
	
document.getElementById('back-btn').style.display = "none";
	document.getElementById('inputer').style.display = "none";
	document.getElementById('chat-box').style.display = "none";
	document.getElementById('contact-list').style.display = "block";
	}
	else{
		
	document.getElementById('contact-list').style.display = "none";	
	}
}






function toggleChat(show) {
    const fab = document.getElementById('chat-fab');
    const popup = document.getElementById('chat-popup');

    if (show) {
        popup.style.display = 'flex';
        fab.style.display = 'none'; // Button disappears
       
		loadList();
    } else {
		loadList();
        popup.style.display = 'none';
        fab.style.display = 'flex'; // Button reappears
    }
	
	isAdmin();
}






function loader(){
	isAdmin(); //alert("testing");
	
}


document.getElementById('send-btn').onclick = sendMsg;
document.getElementById('msg-input').onkeypress = (e) => { if(e.key==='Enter') sendMsg(); };

function startDynamicRefresh() {
   
   
    if (window.chatInterval) clearInterval(window.chatInterval);

   setInterval(() => {
    // Only run if we are in "Admin Mode" and the list is visible
    if (chadmin === -1) {
        const listElement = document.getElementById('contact-list');
        if (listElement && getComputedStyle(listElement).display !== 'none') {
            loadList();
        }
    }
}, 1000);


setInterval(() => {
    // Only run if we are in "Chat Mode" and the box is visible
    if (chadmin === 0) {
        const chatBox = document.getElementById('chat-box');
        if (chatBox && getComputedStyle(chatBox).display !== 'none') {
            loadChat();
        }
    }
}, 3000);
}


startDynamicRefresh();

</script>
























<div class="portal1">

<center class="jaba"> 
<img src="../images/logo.png" class="middle">
<br/>

<h1 class="BoldFont"><br/>Nigerian Maritime Administration and Safety Agency</h1>

</center>


<div class="rounder">
<a href="../others/login.html"> <button type="input" class="button1">PORTAL LOGIN</button></a>

<div class="shift">
<a href="../pages/change_password.php"> <button type="input" class="button2">CHANGE PASSWORD</button></a>
</div><br/>

<div class="shift">

<a href="../php/logout.php"> <button type="input" class="button22">LOG OUT</button></a>




</div>
</div>


</div>

<div class="portal2">


<a href="../others/account info.html">
<div class="circleimg2">
<img src="../images/user.png" style="height:90%; width: 90%; position:relative; top: 5%; left:5%; ">
</div>
</a>
<div class="username">
<h2 class="fonter1">Administrator</h2>
</div>



<center class="BoldFont1"><br/>Admin Control Panel</center>


<!-- card 1-->
<div class="adjust0">
<a href="users.php" class="noue">
<div class="card" >
<div class="cardhead">
<center class="fonter12">Veiw Accounts</center>
</div>
<div class="circleimg">

<img src="../images/account.png" style="height:90%; width: 90%; position:relative; top: 5%; left:8%; ">

</div>
<pre>
<center class="fonter"><br/><br/>View All Registered Accounts
Veiw Accounts informations
Delete Unwanted Accounts
</center>
</pre>
</div>
</a>
</div>

<!-- card 2-->
<div class="adjust">
<a href="submisions.php"  class="noue">
<div class="card" >
<div class="cardhead">
<center class="fonter12">View Submissions</center>
</div>
<div class="circleimg">

<img src="../images/guide.png" style="height:90%; width: 90%; position:relative; top: 5%; left:5%; ">


</div>
<pre>
<center class="fonter"><br/><br/>See Document permit submissions
Verify document checklists
Give document feedback.</center>
</pre>
</div>
</a>
</div>


<!-- card 3-->
<div class="adjust1">
<a href="../pages/memo.php" class="noue">
<div class="card" >
<div class="cardhead">
<center class="fonter12">Memo</center>
</div>
<div class="circleimg">

<img src="../images/app.png" style="height:80%; width: 80%; position:relative; top: 8%; left:15%; ">


</div>
<pre>
<center class="fonter"><br/><br/>Share General Memo
To all users...
To mail and dashboard
 </center>
</pre>
</div>
</a>
</a>
</div>





<!--footer -->

<div class="footer">

<center style="position: relative; left: -3vw;">
<img src="../images/visa.png" style="height:auto; width: 6.5%; position:relative; top: 0; ">

<img src="../images/master.png" style="height:auto; width: 8%; position:relative; top: 0; left: 3vw; ">

<img src="../images/paystack.png" style="height:auto; width: 13%; position:relative; top: 0%; left: 6vw; ">
 
<img src="../images/remita.png" style="height:auto; width: 13%; position:relative; top: -12px; left: 8vw; ">

</center>

<center style="position: relative; left: -3vw;">
<a href="../" class="fonter1">Internet Banking</a>

<a href="../" class="fonter1" style="position: relative; left: 6%;">Mobile Banking</a>

<a href="../" class="fonter1" style="position: relative; left: 12%;">Bank Branches</a>
</center>


</div>





</div>

<div class="bottom">
<center class="BoldFont2">
© NIMASA|Nigerian Maritime Administration and Safety Agency
</center>
</div>

</body>
</html>

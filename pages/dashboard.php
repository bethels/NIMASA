
<?php 
require '../php/check_session.php'; 
require '../php/db_connect.php';

header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline' https://user-images.githubusercontent.com;");


$user_email = $_SESSION['user_email'];

//Fetch all status values for this specific email
// Fetch BOTH columns
$stmt = $pdo->prepare("SELECT status, last_modified FROM cabotage WHERE email = ?");
$stmt->execute([$user_email]);

// FETCH_ASSOC keeps the date and status linked together in an object
$status_list = $stmt->fetchAll(PDO::FETCH_ASSOC);


$email_count = count($status_list); 




$msg_stmt = $pdo->prepare("SELECT * FROM msg WHERE mail = ? ORDER BY message_date DESC");
$msg_stmt->execute([$user_email]);
$all_messages = $msg_stmt->fetchAll();


					





$admin_stmt = $pdo->prepare("SELECT * FROM msg WHERE mail = 'admin_admin' ORDER BY message_date DESC LIMIT 1");
$admin_stmt->execute();
$admin_msg_row = $admin_stmt->fetch();






// Store the string if found, otherwise an empty string
$admin_message_text = $admin_msg_row ? $admin_msg_row['msg'] : "";
$attachments = [];

if ($admin_msg_row) {
    for ($i = 1; $i <= 20; $i++) {
    
    $filePath = $admin_msg_row["attach$i"] ?? "";
    $fileName = $admin_msg_row["attach{$i}_name"] ?? "";

    if (!empty($filePath)) {
        $attachments[] = [
            'path' => $filePath,
            'name' => !empty($fileName) ? $fileName : "Attachment $i"
        ];
    }
}
}

?>




<?php 

$conn = new mysqli("localhost", "root", "", "nimasa");

// 1. Identify current session
$session_email = $_SESSION['user_email'] ?? ''; 
if (!$session_email) { die("Direct access denied. Please log in."); }

// 2. Fetch Role from nimdat
$stmt = $conn->prepare("SELECT file_path FROM nimdat WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $session_email);
$stmt->execute();
$roleRow = $stmt->get_result()->fetch_assoc();
$myRole = $roleRow['file_path'] ?? 'user';

// 3. Determine if I am part of the Admin Team
$isAdmin = ($myRole === 'admin_admin' || $myRole === 'admin_admin_dir');




$sql = "SELECT DISTINCT email_user FROM (
            SELECT sender AS email_user FROM chat WHERE sender NOT IN ('admin_admin', 'admin_admin_dir')
            UNION
            SELECT receiver AS email_user FROM chat WHERE receiver NOT IN ('admin_admin', 'admin_admin_dir')
        ) AS combined_users ORDER BY email_user ASC";

$result = $conn->query($sql);



?>



<!Doctype html>
<head>


<title>Company Dashboard</title>

<link rel="stylesheet" href="../css/dashboard.css">

<meta name="viewport" content="width=device-width" initial-scale="1.0">



<link rel="icon" href="../images/logo.png" type="image/x-icon">
</head>

<html>



<div id="msgModalz" style="display: none; 
    position: fixed; 
    top: 0; 
    left: 0; 
    width: 100vw; 
    height: 100vh; 
    background: rgba(0,0,0,0.8); 
    z-index: 99999; /* Ensure this is the highest number on the page */
    justify-content: center; 
    align-items: center;">
    <div style="background:white; padding:20px; border-radius:8px; width:90%; max-width:500px; color:#333; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
        <h2 id="modalTitlez" style="color:rgb(27, 50, 185); margin-top:0;">Message</h2>
        <hr>
        
        <div id="modalBodyz" style="margin:20px 0; line-height:1.6; max-height:300px; overflow-y:auto; white-space: pre-wrap;"></div>
        
        <div id="modalAttachmentsz"></div>
        
        <div style="text-align:right; margin-top:20px;">
            <button onclick="closeModalz()" style="background:rgb(27, 50, 185); color:white; border:none; padding:10px 20px; cursor:pointer; border-radius:4px;">Close</button>
        </div>
    </div>
</div>

<body onload="getdata()">











<script src="../chat/chat_logic.js"></script>

<button id="chat-fab" onclick="toggleChat(true)">
    <span style="font-size: 28px; color:white;">&#128489;</span>
</button>

<div id="chat-popup">


<div id="chat-container">
    <div id="header">
        <div> <strong>NIMASA Messaging</strong><br><small><?php echo $session_email; ?></small>
		<small id="useral"></small>
		</div>
		  <button id="close-chat-btn" onclick="toggleChat(false)">✖</button>
    </div>

    <div id="chat-box"></div>

    <div id="inputer" class="input-bar">
        <input type="text" id="msg-input" placeholder="Type message..." autocomplete="off">
        <button id="send-btn">➤</button>
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
	document.getElementById('back-btn').style.display = "none";
	
	
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
	document.getElementById('back-btn').style.display = "none";
	
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
        loadChat(); // Refresh messages when opened
    } else {
        popup.style.display = 'none';
        fab.style.display = 'flex'; // Button reappears
    }
}






function loader(){
	isAdmin(); //alert("testing");
	
}


document.getElementById('send-btn').onclick = sendMsg;
document.getElementById('msg-input').onkeypress = (e) => { if(e.key==='Enter') sendMsg(); };

if(chadmin==0){
// Auto-refresh when chat is open
setInterval(() => {
    if(document.getElementById('chat-popup').style.display === 'flex') {
        loadChat();
    }
}, 3000);

//loadChat();

}

</script>
























<script src="../JS/dashboard.js"></script>

<div class="dashtop">

<a href="../index.html" class="logo">
<div class="logo">

<img src="../images/logo.png" style="position:relative; height:100%; left: 20px; ">
<span class="logofont"> NIMASA</span>

</div>

</a>

<a href="" class="dashmenu"><span class="dashmenu"> Dashboard </span></a>

<a href="applicationMid.php" class="dashmenu"><span class="dashmenu"> Applications </span></a>

<a href="accounts.php" class="dashmenu"><span class="dashmenu"> My Account </span></a>

<!--<a href="" class="dashmenu"><span class="dashmenu"> My Waivers </span></a>-->

<a href="helpMid.php" class="dashmenu"><span class="dashmenu"> Help </span></a>



<span class="username" id="username"><span class="logout"><?php echo $_SESSION['user_email']; ?></span></span>

<a href="../php/logout.php" class="logout"><span class="logout"> Logout </span></a>


</div>




<div class="semi">


<div class="middle" >

<div class="note">
<br/><br/>
<center class="whitefont">Nigerian Maritime Administration and Safety Agency (NIMASA)<center>
<br/>

<!--cards-->
<a href="allApplications.php" style="color:white; text-decoration: none;">
<div class="card" >
<div class="hollow-circle">
<span id="applications">93</span>

<div class="cardlow"> 
<center class="whitefont1">All Applications</center>
</div>
</div>
</div>
</a>

<!--cards-->
<a href="processapp.php" style="color:white; text-decoration: none;">
<div class="card">
<div class="hollow-circle">
<span id="processing">03</span>

<div class="cardlow"> 
<center class="whitefont1">Processing</center>
</div>
</div>
</div>
</a>


<!--cards-->
<a href="verifyapp.php" style="color:white; text-decoration: none;">
<div class="card">
<div class="hollow-circle">
<span id="verifying">03</span>

<div class="cardlow"> 
<center class="whitefont1">Verifying</center>
</div>
</div>
</div>
</a>



<!--cards-->
<a href="completeapp.php" style="color:white; text-decoration: none;">
<div class="card">
<div class="hollow-green">
<span id="complete">83</span>

<div class="cardlow"> 
<center class="whitefont1">Completed</center>
</div>
</div>
</div>
</a>


<!--cards-->
<a href="rejectapp.php" style="color:white; text-decoration: none;">
<div class="card">
<div class="hollow-red">
<span id="rejects">93</span>

<div class="cardlow"> 
<center class="whitefont1">Rejections</center>
</div>
</div>
</div>
</a>




<!--cards-->
<a href="expiredapp.php" style="color:white; text-decoration: none;">
<div class="card">
<div class="hollow-yellow">
<span id="expired">93</span>

<div class="cardlow"> 
<center class="whitefont1">Expiring</center>
</div>
</div>
</div>
</a>













</div>




  <br/>  
  <center>
<a href="appType.php"><button class="buttons">Apply for Waiver</button></a>
<a href="profile.php"><button class="buttons">Company Profile</button></a>
<a href="documents.php"><button class="buttons">My Documents</button></a>
<a href="completeapp.php"><button class="buttons">Completed</button></a>
<a href="rejectapp.php"><button class="buttons">Rejections</button></a>
<a href="expiredapp.php"><button class="buttons">Expiring</button></a>
</center>

<br/>


<div class="errormsg" id="errormsg">
<button class="close" onclick="closeError()">close</button>
<span id="error">You seem to have some errors in your application(s)</span>




</div>

<br/>
<!--notification-->

<div class="infomsg" id="infomsg">
<button class="close"  onclick="closeInfo()">close</button>
<span id="error">You have some applications waiting for approval</span>




</div>

<br/>



<div class="container">

<h1 class="msghead">Messages</h1>


<div class="tables-wrapper">
    
   <table class="data-table">
    <thead>
        <tr>
            <th>Message Preview</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php if (empty($all_messages)): ?>
        <tr><td colspan="3">No messages found</td></tr>
    <?php else: ?>
        <?php foreach ($all_messages as $row): ?>
            <?php 
                // 1. COLLECT ATTACHMENTS FOR THIS SPECIFIC ROW INSIDE THE LOOP
                $this_row_attachments = [];
                for ($i = 1; $i <= 20; $i++) {
                    $path = $row["attach$i"] ?? "";
                    $name = $row["attach{$i}_name"] ?? "";
                    if (!empty($path)) {
                        $this_row_attachments[] = ['path' => $path, 'name' => $name];
                    }
                }
                // 2. CONVERT TO JSON SPECIFICALLY FOR THIS ROW
                $row_json = htmlspecialchars(json_encode($this_row_attachments), ENT_QUOTES, 'UTF-8');
            ?>
            <tr>
                <td>
                    <?php 
                        $preview = strlen($row['msg']) > 30 ? substr($row['msg'], 0, 30) . "..." : $row['msg'];
                        echo htmlspecialchars($preview); 
                    ?>
                </td>
                <td>
                    <?php echo date("jS M, Y", strtotime($row['message_date'])); ?>
                </td>
                <td>
                    <button class="read-btn" 
                            onclick="openMessage('NIMASA Message', `<?php echo htmlspecialchars($row['msg']); ?>`, '<?php echo $row_json; ?>')">
                        Read More
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</tbody>
</table>
    <table class="data-table1">
        <thead>
            <tr><th>Help?</th></tr>
        </thead>
        <tbody>
            <tr><td onclick="inquire()">Inquiry</td></tr>
			<tr><td onclick="faq()">FAQ</td></tr>
			<tr><td onclick="guides()">Guide Lines</td></tr>
			<tr><td>Video Guide</td></tr>
			</tr>
        </tbody>
    </table>

</div>






</div>












</div>








<div class="bottom">
<center class="BoldFont2">
© NIMASA|Nigerian Maritime Administration and Safety Agency
</center>
</div>


<script>
        // This converts the PHP results into JavaScript variables
        var totalApplications = <?php echo json_encode((int)$email_count); ?>;
      var allStatuses = <?php echo json_encode($status_list); ?>;
		
        var adminMessage = <?php echo json_encode($admin_message_text); ?>;
		
      
    </script>

    <script src="../JS/dashboard.js"></script>






<div id="msgModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:9999; justify-content:center; align-items:center;">
    <div style="background:white; padding:20px; border-radius:8px; width:90%; max-width:500px; color:#333; position:absolute; top:10%; ">
        <h2 id="modalTitle" style="color:rgb(27, 50, 185);">Message</h2>
        <hr>
        <p id="modalBody" style="margin:20px 0; line-height:1.6;"></p>
		
		
		

<?php if (!empty($attachments)): ?>
    <div class="attachment-list" style="margin-top: 15px; border-top: 1px solid #ddd; padding-top: 10px;">
        <p><strong>Attachments:</strong></p>
        <?php foreach ($attachments as $file): ?>
            <div style="margin-bottom: 5px;">
                <a href="../<?php echo htmlspecialchars($file['path']); ?>" target="_blank" style="color: blue; text-decoration: underline;">
                    📎 <?php echo htmlspecialchars($file['name']); ?>
                </a>
            </div>
        <?php endforeach; ?>
		
		
    </div>
<?php endif; ?>
		
		
		
        <button onclick="closeModal()" style="background:rgb(27, 50, 185); color:white; border:none; padding:10px 20px; cursor:pointer; border-radius:4px;">Close</button>
    </div>
</div>










</body>

</html>
<?php 
session_start(); 
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NIMASA Support Chat</title>
    <style>
        body { font-family: 'Segoe UI', Helvetica, sans-serif; background-color: #e5ddd5; margin: 0; }
        #chat-container { width: 100%; max-width: 500px; height: 95vh; margin: 10px auto; background: #fff; display: flex; flex-direction: column; box-shadow: 0 5px 15px rgba(0,0,0,0.2); border-radius: 10px; overflow: hidden; }
        
        #header { background: rgb(50,50,240); color: white; padding: 15px; display: flex; justify-content: space-between; align-items: center; }
        #chat-box { flex: 1; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; gap: 10px; background-color: rgb(200,200,200); }

        
        .msg { max-width: 75%; padding: 10px; border-radius: 8px; font-size: 14px; position: relative; box-shadow: 0 1px 1px rgba(0,0,0,0.1); }
        .left { align-self: flex-start; background: #fff; border-top-left-radius: 0; }
        .right { align-self: flex-end; background: rgb(220,250,255); border-top-right-radius: 0; }

        .sender-name { font-size: 10px; font-weight: bold; color: rgb(20,20,180); margin-bottom: 3px; display: block; }
        .footer { display: flex; justify-content: space-between; align-items: center; font-size: 10px; margin-top: 5px; opacity: 0.6; }
        .del-btn { color: red; background: block; border: none; cursor: pointer; font-size: 20px; font-weight:900; }

        .input-bar { padding: 10px; background: #f0f0f0; display: flex; gap: 10px; align-items: center; }
        #msg-input { flex: 1; padding: 12px; border-radius: 20px; border: none; outline: none; }
        #send-btn { background: rgb(50,50,240); color: white; border: none; width: 45px; height: 45px; border-radius: 50%; cursor: pointer; }
		
		#back-btn { background: rgb(240,240,240); color: rgb(50,50,250); border: none; width: 45px; height: 45px; border-radius: 50%; cursor: pointer; }
    
	
	
	
	
	 .contact-item {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        border-bottom: 1px solid #f2f2f2;
        cursor: pointer;
        transition: background 0.2s;
    }
    .contact-item:hover {
        background-color: #f5f5f5;
    }
    .avatar {
        width: 45px;
        height: 45px;
        background-color: #007bff;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 15px;
    }
    .contact-info {
        flex: 1;
        overflow: hidden;
    }
    .contact-name {
        font-weight: 600;
        font-size: 14px;
        color: #333;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }
    .contact-status {
        font-size: 12px;
        color: #888;
    }
	
	
	
	
	
	  /* Floating Button Styles */
    #chat-fab {
        position: fixed;
        bottom: 25px;
        right: 25px;
        width: 65px;
        height: 65px;
        background-color: rgb(50,50,250);
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0,0,0,0.4);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    /* Popup Chat Box Styles (Bottom Left) */
    #chat-popup {
        position: fixed;
        bottom: 25px;
        right: 25px;
        width: 380px;
        height: 90vh;
        background: white;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.3);
        display: none; /* Hidden initially */
        flex-direction: column;
        z-index: 10000;
        overflow: hidden;
        border: 1px solid #ddd;
    }

    #chat-widget-header {
        background: #075e54;
        color: white;
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
    }

    #close-chat-btn {
        background: none;
        border: none;
        color: white;
        font-size: 18px;
        cursor: pointer;
        opacity: 0.8;
    }



	
	</style>
</head>
<body onload="loader()">



<script src="../chat/chat_logic.js"></script>

<button id="chat-fab" onclick="toggleChat(true)">
    <span style="font-size: 28px; color:white;">💬</span>
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
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="contact-item" onclick="handleUserClick('<?php echo $row['email_user']; ?>')">
                    <div class="avatar"><?php echo strtoupper(substr($row['email_user'], 0, 1)); ?></div>
                    <div class="contact-info">
                        <div class="contact-name"><?php echo $row['email_user']; ?></div>
                        <div class="contact-status">Click to view messages</div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="padding: 20px; text-align: center; color: #999;">No conversations found.</div>
        <?php endif; ?>
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



function loadChat() {
    fetch(`../chat/get_chat.php?target=${targetUser}`)
    .then(r => r.json())
    .then(data => {
        const box = document.getElementById('chat-box');
        box.innerHTML = '';
        
        data.forEach(m => {
            // 1. Identify if the message belongs to the current user's side
            let isMe;
            if (isAdminRole) {
                isMe = (m.sender === 'admin_admin');
            } else {
                isMe = (m.sender === sessionUser);
            }

            // 2. CLEAN DISPLAY NAME LOGIC
            // If the sender is admin_admin, show "Admin". 
            // Otherwise, show "You" if it's the user, or the user's email if it's someone else.
            let displaySender;
            if (m.sender === 'admin_admin') {
                displaySender = "Admin";
            } else if (isMe) {
                displaySender = "You";
            } else {
                displaySender = m.sender;
            }

            const side = isMe ? 'right' : 'left';
            
            box.innerHTML += `
                <div class="msg ${side}">
                    <span class="sender-name">${displaySender}</span>
                    <div class="msg-text">${m.msg}</div>
                    <div class="footer">
                        <span>${m.send_date}</span>
                        ${isMe ? `<button class="del-btn" onclick="deleteMsg(${m.id})">🗑</button>` : ''}
                    </div>
                </div>
            `;
        });
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



</body>
</html>
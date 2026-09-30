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
    </style>
</head>
<body>

<div id="chat-container">
    <div id="header">
        <div><strong>NIMASA Messaging</strong><br><small><?php echo $session_email; ?></small></div>
    </div>

    <div id="chat-box"></div>

    <div class="input-bar">
        <input type="text" id="msg-input" placeholder="Type message..." autocomplete="off">
        <button id="send-btn">➤</button>
    </div>
</div>

<script>
const sessionUser = "<?php echo $session_email; ?>";
const isAdminRole = <?php echo $isAdmin ? 'true' : 'false'; ?>;






// Admin, the target is the user I am viewing (e.g. gama@gmail.com)
// User, the target is always 'admin_admin'
let targetUser = (typeof trueEmail !== 'undefined') ? trueEmail : "youtube@gmail.com"; 
if(!isAdminRole) { targetUser = 'admin_admin'; }

function loadChat() {
    fetch(`get_chat.php?target=${targetUser}`)
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

    fetch('send_chat.php', { method: 'POST', body: fd })
    .then(() => {
        input.value = '';
        loadChat();
    });
}

function deleteMsg(id) {
    if(!confirm("Delete?")) return;
    const fd = new FormData();
    fd.append('id', id);
    fetch('delete_chat.php', { method: 'POST', body: fd }).then(() => loadChat());
}

document.getElementById('send-btn').onclick = sendMsg;
document.getElementById('msg-input').onkeypress = (e) => { if(e.key==='Enter') sendMsg(); };

setInterval(loadChat, 3000);
loadChat();
</script>



</body>
</html>
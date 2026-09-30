<?php
require '../php/check_session.php'; 
require '../php/db_connect.php';

$user_email = $_SESSION['user_email'];

// 1. Fetch all messages for the sidebar and main view
$msg_stmt = $pdo->prepare("SELECT * FROM msg WHERE mail = ? ORDER BY message_date DESC");
$msg_stmt->execute([$user_email]);
$all_messages = $msg_stmt->fetchAll(PDO::FETCH_ASSOC);

// 2. Format messages for JavaScript use
$json_messages = json_encode($all_messages);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Message Center | NIMASA</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="icon" href="../images/logo.png" type="image/x-icon">
    <style>
        :root { --primary: rgb(27, 50, 185); --bg: #f4f7f6; }
        body { background: var(--bg); font-family: 'Segoe UI', sans-serif; margin: 0; }
        
        .msg-layout { display: flex; height: 100vh; padding-top: 60px; box-sizing: border-box; }
        
        /* Sidebar List */
        .msg-sidebar { width: 350px; background: white; border-right: 1px solid #ddd; display: flex; flex-direction: column; }
        .sidebar-header { padding: 20px; border-bottom: 1px solid #eee; background: white; z-index: 10; }
        .msg-list { overflow-y: auto; flex-grow: 1; }
        
        .msg-item { padding: 15px 20px; border-bottom: 1px solid #f0f0f0; cursor: pointer; transition: 0.2s; position: relative; }
        .msg-item:hover { background: #f9f9f9; }
        .msg-item.active { background: #eef2ff; border-left: 4px solid var(--primary); }
        .msg-item .date { font-size: 0.75rem; color: #888; float: right; }
        .msg-item .subject { font-weight: 600; color: #333; display: block; margin-bottom: 4px; }
        .msg-item .preview { font-size: 0.85rem; color: #666; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        /* Reading Pane */
        .msg-viewport { flex-grow: 1; background: white; display: flex; flex-direction: column; position: relative; }
        #empty-state { display: flex; align-items: center; justify-content: center; height: 100%; color: #aaa; flex-direction: column; }
        #msg-content-area { display: none; padding: 40px; overflow-y: auto; }
        
        .msg-header { border-bottom: 1px solid #eee; margin-bottom: 30px; padding-bottom: 20px; }
        .msg-title { font-size: 1.5rem; color: var(--primary); margin: 0 0 10px 0; }
        .msg-meta { font-size: 0.9rem; color: #666; }
        
        .msg-body { line-height: 1.8; color: #444; font-size: 1.05rem; white-space: pre-wrap; margin-bottom: 40px; }
        
        .attachments-box { background: #f9f9fb; border: 1px solid #eee; border-radius: 8px; padding: 20px; }
        .file-pill { display: inline-flex; align-items: center; background: white; border: 1px solid #ddd; 
                      padding: 8px 15px; border-radius: 20px; margin: 5px; text-decoration: none; color: #333; font-size: 0.9rem; transition: 0.3s; }
        .file-pill:hover { border-color: var(--primary); color: var(--primary); transform: translateY(-2px); }
    </style>
</head>
<body>

<div class="dashtop">

<a href="../index.html" class="logo">
<div class="logo">

<img src="../images/logo.png" style="position:relative; height:100%; left: 20px; ">
<span class="logofont"> NIMASA</span>

</div>

</a>

<a href="dashboard.php" class="dashmenu"><span class="dashmenu"> Dashboard </span></a>

<a href="applicationMid.php" class="dashmenu"><span class="dashmenu"> Applications </span></a>

<a href="accounts.php" class="dashmenu"><span class="dashmenu"> My Account </span></a>

<!--<a href="" class="dashmenu"><span class="dashmenu"> My Waivers </span></a>-->

<a href="helpMid.php" class="dashmenu"><span class="dashmenu"> Help </span></a>



<span class="username" id="username"><span class="logout"><?php echo $_SESSION['user_email']; ?></span></span>

<a href="../php/logout.php" class="logout"><span class="logout"> Logout </span></a>


</div>

<div class="msg-layout">
    <div class="msg-sidebar">
        <div class="sidebar-header">
            <h2 style="margin:0; font-size: 1.2rem;">Messages</h2>
        </div>
        <div class="msg-list" id="sidebar-list">
            </div>
    </div>

    <div class="msg-viewport">
        <div id="empty-state">
            <span style="font-size: 4rem;">✉️</span>
            <p>Select a message to read</p>
        </div>

        <div id="msg-content-area">
            <div class="msg-header">
                <h1 class="msg-title" id="view-title">NIMASA Official Notification</h1>
                <div class="msg-meta">
                    Sent on: <span id="view-date">--</span>
                </div>
            </div>
            
            <div class="msg-body" id="view-body">
                </div>

            <div id="view-attachments" class="attachments-box">
                <p style="margin-top:0; font-weight:600;">Attachments</p>
                <div id="files-container"></div>
            </div>
        </div>
    </div>
</div>

<script>
    const messages = <?php echo $json_messages; ?>;

    function init() {
        const listContainer = document.getElementById('sidebar-list');
        
        if (messages.length === 0) {
            listContainer.innerHTML = '<div style="padding:20px; color:#888;">No messages yet.</div>';
            return;
        }

        messages.forEach((msg, index) => {
            const div = document.createElement('div');
            div.className = 'msg-item';
            div.id = `msg-${index}`;
            
            // Format Date
            const date = new Date(msg.message_date).toLocaleDateString('en-GB', {
                day: 'numeric', month: 'short'
            });

            div.innerHTML = `
                <span class="date">${date}</span>
                <span class="subject">NIMASA Notification</span>
                <p class="preview">${msg.msg.substring(0, 60)}...</p>
            `;
            
            div.onclick = () => selectMessage(index);
            listContainer.appendChild(div);
        });
    }

    function selectMessage(index) {
        const msg = messages[index];
        
        // UI Updates
        document.getElementById('empty-state').style.display = 'none';
        document.getElementById('msg-content-area').style.display = 'block';
        
        // Clear active classes
        document.querySelectorAll('.msg-item').forEach(el => el.classList.remove('active'));
        document.getElementById(`msg-${index}`).classList.add('active');

        // Fill Data
        document.getElementById('view-date').innerText = new Date(msg.message_date).toLocaleDateString('en-GB', {
            day: 'numeric', month: 'long', year: 'numeric'
        });
        document.getElementById('view-body').innerText = msg.msg;

        // Handle Attachments
        const filesContainer = document.getElementById('files-container');
        const attachBox = document.getElementById('view-attachments');
        filesContainer.innerHTML = "";
        
        let hasFiles = false;
        for (let i = 1; i <= 20; i++) {
            const path = msg[`attach${i}`];
            const name = msg[`attach${i}_name`];
            
            if (path && path.trim() !== "") {
                hasFiles = true;
                const link = document.createElement('a');
                link.href = "../" + path;
                link.target = "_blank";
                link.className = "file-pill";
                link.innerHTML = `📎 ${name || 'Attachment ' + i}`;
                filesContainer.appendChild(link);
            }
        }
        
        attachBox.style.display = hasFiles ? 'block' : 'none';
    }

    window.onload = init;
</script>
</body>
</html>
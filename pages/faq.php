

<?php 
require '../php/check_session.php'; 
require '../php/db_connect.php';

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















<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width" initial-scale="1.0">



<link rel="icon" href="../images/logo.png" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NIMASA FAQ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
	
	



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
	
	
	
	
	
	

    /* Popup Chat Box Styles (Bottom Left) */
    #chat-popup {
        position: fixed;
        bottom: 25px;
        left: 25px;
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





        :root {
            --nimasa-blue: #003366;
            --text-main: #000000;
            --text-muted: #666666;
            --accent-blue: #3B82F6;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            margin: 0;
            padding: 100px 40px;
            background-color: #ffffff;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
        }

        h1 {
            font-size: 4rem;
            font-weight: 800;
            margin: 0 0 60px 0;
            letter-spacing: -2px;
        }

        .grid {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 80px;
        }

        /* Left Column: Chat Prompt */
        .sidebar-chat h3 {
            font-size: 1rem;
            color: #999;
            font-weight: 500;
            margin-bottom: 10px;
        }
        .sidebar-chat p {
            font-size: 1.6rem;
            font-weight: 600;
            margin: 0 0 30px 0;
            line-height: 1.2;
        }
        .chat-btn {
            width: 70px;
            height: 70px;
            background-color: var(--accent-blue);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .chat-btn:hover { transform: scale(1.1); }
        .chat-btn i { color: white; font-size: 2rem; }

        /* Right Column: FAQ Accordion */
        .search-box {
            position: relative;
            margin-bottom: 40px;
        }
        .search-box i {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            color: #ccc;
            font-size: 1.2rem;
        }
        #faqSearch {
            width: 100%;
            border: none;
            border-bottom: 1px dashed #cccccc;
            padding: 15px 0 15px 35px;
            font-size: 1.2rem;
            outline: none;
            color: var(--text-main);
        }

        .faq-item {
            border-bottom: 1px solid #eeeeee;
        }
        .faq-question {
            width: 100%;
            padding: 30px 0;
            background: none;
            border: none;
            display: flex;
            align-items: center;
            cursor: pointer;
            text-align: left;
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--text-main);
        }
        .faq-question i {
            margin-right: 25px;
            font-size: 1rem;
            color: #bbbbbb;
            transition: transform 0.3s ease;
        }
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: all 0.4s ease;
            color: var(--text-muted);
            font-size: 1.1rem;
            line-height: 1.7;
        }
        .faq-item.active .faq-answer {
            max-height: 400px;
            padding-bottom: 35px;
        }
        .faq-item.active .fa-chevron-down {
            transform: rotate(180deg);
        }

        @media (max-width: 900px) {
            .grid { grid-template-columns: 1fr; gap: 60px; }
            h1 { font-size: 3rem; }
        }
		
		
.dashtop{
display: flex;
position:fixed;
width:100vw;
height: 65px;
top:0vh;
left:0vw;
background-color: rgb(50,57,240);

background-image: linear-gradient(to bottom,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185) ),
					linear-gradient(to right,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185) );


background-blend-mode: multiply;

z-index: 1000;

box-shadow: 0px 0px 20px rgba(0,0,0,0.8);	
	
}

.logo{
text-decoration:none;
background-color:white;
position:relative;
width: 14vw;
height: 65px;

}

.logofont{
font-size: 20px;
font-weight: 600;
font-family: segoe ui, sans-serif, Berlin Sans FB,arial;
color: black;
position:relative;
left: 30px;
top:-25px;
	
}

.logo:hover{
	
cursor:pointer;	
	
}

.dashmenu{
display:flex;
font-size: 20px;
font-weight: 400;
font-family: segoe ui, sans-serif, Berlin Sans FB,arial;
color: white;
position:relative;
text-decoration:none;
top:0px;
margin: 7px;
background:none;
border:none;
	
}






.username {
    margin-left: auto; 
    text-decoration: none;
    height: 56px;
    display: flex;
    align-items: center;
    padding: 0 15px;
  
    color: white;
}


.username:hover{
	
cursor:pointer;
	
}

.logout {
    font-size: 22px;
    font-family: "Segoe UI", sans-serif;
    color: white;
    text-decoration: none;
    margin: 6px 20px 6px 10px; 
	
}

 .logo:hover { opacity: 0.8; }
		 .logout:hover { opacity: 0.7; }
		
        .dashmenu:hover { opacity: 0.7; }


    </style>
</head>
<body>









<script src="../chat/chat_logic.js"></script>


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














<div class="container">
    <h1>Frequently Asked<br>Questions</h1>

    <div class="grid">
        <div class="sidebar-chat">
            <h3>Can't find what you are looking for?</h3>
            <p>We would like to chat with you.</p>
			
		

            <div id="chat-fab" class="chat-btn" onclick="toggleChat(true)">
                <i class="fas fa-comment-dots"></i>
            </div>
        </div>

        <div class="faq-list">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="faqSearch" placeholder="What are you looking for?">
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    <i class="fas fa-chevron-down"></i>
                    What is a Cabotage Waiver?
                </button>
                <div class="faq-answer">
                    A Cabotage Waiver is a special authorization granted by the Federal Ministry of Transportation. It allows foreign-owned or foreign-crewed vessels to operate in Nigeria's domestic coastal trade when no suitable Nigerian vessel is available.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    <i class="fas fa-chevron-down"></i>
                    What documents must I upload for validation?
                </button>
                <div class="faq-answer">
                    For a successful application, you must upload a valid Certificate of Ship Registry, Bill of Sale, Evidence of Manning Requirements, and a current Vessel Inspection Report. All files should be high-resolution PDFs.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    <i class="fas fa-chevron-down"></i>
                    How long does the NIMASA validation take?
                </button>
                <div class="faq-answer">
                    Document validation usually takes between 48 to 72 hours. Once verified, the application is forwarded for Ministerial approval, which can take an additional 7-10 working days.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    <i class="fas fa-chevron-down"></i>
                    Why was my document rejected?
                </button>
                <div class="faq-answer">
                    Common reasons for rejection include blurry scans, expired certificates, or incorrect file formats. Ensure that all official NIMASA stamps and signatures are clearly visible before uploading.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // FAQ Accordion Toggle
    document.querySelectorAll('.faq-question').forEach(button => {
        button.addEventListener('click', () => {
            const currentItem = button.parentElement;
            
            // Close other items for a cleaner look
            document.querySelectorAll('.faq-item').forEach(item => {
                if (item !== currentItem) item.classList.remove('active');
            });

            currentItem.classList.toggle('active');
        });
    });

    // Search Filter Logic
    document.getElementById('faqSearch').addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('.faq-item').forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(term) ? 'block' : 'none';
        });
    });

    // Integration helper
    function openChat() {
        // Replace with your actual chat open function
        if(typeof toggleChat === "function") {
            toggleChat(true);
        } else {
            alert("Chat system is initializing...");
        }
    }
</script>

</body>
</html>
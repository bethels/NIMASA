
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

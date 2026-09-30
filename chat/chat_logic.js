// 1. DATA FROM PHP
// You should define these via PHP before calling this script
// const sessionUser = "user@example.com";
// const isAdminRole = true/false;
// let targetUser = "admin_admin"; 

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



function escapeHTML(str) {
    const p = document.createElement('p');
    p.textContent = str;
    return p.innerHTML;
}


function loadList() {
    console.log("Attempting to fetch contact list..."); // Debug 1
    
    fetch('../chat/get_contacts_json.php')
    .then(r => {
        console.log("Response received from PHP"); // Debug 2
        return r.json();
    })
    .then(data => {
        console.log("Data parsed:", data); // Debug 3
        
        const listContainer = document.getElementById('contact-list');
        if (!listContainer) {
            console.error("Could not find element with ID 'contact-list'");
            return;
        }

        if (data.length === 0) {
            listContainer.innerHTML = '<div style="text-align:center; padding:20px;">No conversations yet.</div>';
            return;
        }

       let html = '';
data.forEach(row => {
    // 1. Sanitize the data immediately
    const safeEmail = escapeHTML(row.chat_user);
    const firstLetter = safeEmail.charAt(0).toUpperCase();
    const safeCount = parseInt(row.unread_count) || 0;

    const badge = (safeCount > 0) 
        ? `<div class="unread-badge">${safeCount}</div>` 
        : '';

    html += `
        <div class="contact-item" 
             onclick="handleUserClick('${safeEmail.replace(/'/g, "\\'")}')" 
             style="display:flex; justify-content:space-between; align-items:center; padding:10px; border-bottom:1px solid #eee; cursor:pointer;">
            <div style="display:flex; align-items:center;">
                <div class="avatar" style="width:40px; height:40px; background:#007bff; color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; margin-right:10px;">
                    ${firstLetter}
                </div>
                <div class="contact-info">
                    <div class="contact-name" style="font-weight:bold;">${safeEmail}</div>
                    <div class="contact-status" style="font-size:0.85em; color:#666;">Click to view messages</div>
                </div>
            </div>
            ${badge}
        </div>`;
});

        listContainer.innerHTML = html;
        console.log("List updated successfully"); // Debug 4
    })
    .catch(err => {
        console.error("JS Fetch Error:", err);
    });
}





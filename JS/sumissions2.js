
function toggleMsg(show) {
    const msgBtn = document.getElementById('msgBtn');
    const msgArea = document.getElementById('msgArea');

    if (show) {
        msgBtn.style.display = "none";
        msgArea.style.display = "block";
    } else {
        msgBtn.style.display = "block";
        msgArea.style.display = "none";
        // Optional: Clear the text when closing
        document.getElementById('adminNote').value = ""; 
    }
}

function sendMessage() {
    const adminNote = document.getElementById("adminNote").value;
    const userList = document.getElementById("userList");
	
	
const sendBtn = document.querySelector(".btn-send");
sendBtn.disabled = true;
sendBtn.innerText = "Sending...";
    
    // get the email from the selected option text 
   
    const selectedEmail = document.getElementById('accountz').querySelector('span').innerText; 

    if (!adminNote.trim()) {
        alert("No Message Inputed.");
        return;
    }

    const formData = new FormData();
    formData.append('email', selectedEmail);
    formData.append('message', adminNote);

    fetch('../php/save_message.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(res => {
	sendBtn.disabled = false;
sendBtn.innerText = "Send";
        if (res.status === "success") {
            alert("Message sent");
            document.getElementById("adminNote").value = ""; // Clear box
            toggleMsg(false); // Close area
        } else {
            alert("Error: " + res.message);
        }
    })
    .catch(err => console.error("Error:", err));
}





function listClose(){

//alert("adsf");
		
	
var element = document.getElementById('accounts');

//element.style.backgroundColor = 'blue';
element.style.width='96vw';



var element = document.getElementById('accountDETS');
element.style.display="none";


var list = document.getElementById("userList").selectedIndex = -1;


}





function nigerian(data, displayArea, spacer, details) {
    const tags = {
        1: "Name of Company/Agent",
        2: "Name of Vessel (MT/MV)",
        3: "Type of Vessel",
        4: "GRT",
        5: "Vessel Registration Status",
        6: "Cabotage Application for Waiver Form",
        7: "Cabotage Affidavit Form (CAF)",
        8: "Bill of Sale",
        9: "Evidence of Ownership / Ship Registry Cert",
        10: "Builders Certificate",
        11: "Application to Participate Form",
        12: "Crew List Declaration Form",
        13: "Declaration of Ownership Form",
        14: "Detailed Crew List (Letterhead)",
        15: "Declaration of Eligibility",
        16: "Minimum Safe Manning Certificate (SMC)",
        17: "Completed Maritime Labour Declaration",
        18: "Evidence of Sea Protection Levy (SPL)",
        19: "Certificate of Incorporation",
        20: "Memorandum and Articles of Association",
        21: "CAC 2 & 7 (Min =N=25M Share Capital)",
        22: "Tax Clearance Certificates (Last 3 Years)",
        23: "Evidence of NIMASA Shipping Reg",
        24: "Evidence of 2% Surcharge (Renewal)",
        25: "Last Waiver Cert / Processing Fee Receipt",
        26: "Evidence of Nigerian Cadet Training",
        27: "Survey Report / Customs TI Document"
    };




const currentAppStatus = data.status; 
const radioToSelect = document.getElementById(`status-${currentAppStatus}`);

if (radioToSelect) {
    radioToSelect.checked = true;
} else {
    // Clear all if status doesn't match 0, 1, or 3
    document.querySelectorAll('input[name="app_status"]').forEach(r => r.checked = false);
}






    
    let infoSection = `<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 20px;">`;
    
   
    const headerCards = [
        { label: "Email", val: data.email },
        { label: "Application Type", val: "Nigerian Waiver" }
    ];

    headerCards.forEach(card => {
        infoSection += `
            <div style="background-image: linear-gradient(to bottom,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185)), linear-gradient(to right,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185)); background-blend-mode: multiply; padding: 10px; border-radius: 5px; border-left: 4px solid #007bff;">
                <small style="color: white; font-weight: bold;">${card.label}</small><br>
                <span style="color:rgb(220,220,220);">${card.val}</span>
            </div>`;
    });

    for (let i = 1; i <= 5; i++) {
        let val = data['dat' + i] || "N/A";
        infoSection += `
            <div style="background-image: linear-gradient(to bottom,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185)), linear-gradient(to right,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185)); background-blend-mode: multiply; padding: 10px; border-radius: 5px; border-left: 4px solid #007bff;">
                <small style="color: white; font-weight: bold;">${tags[i]}</small><br>
                <span style="color:rgb(220,220,220);">${val}</span>
            </div>`;
    }
    infoSection += `</div><hr><h3>Uploaded Documents</h3>`;
    displayArea.innerHTML = infoSection;

   
    for (let i = 10; i <= 12; i++) {
        let filePath = data['dat' + i];
        let fileName = data['dat' + i + '_name'] || `Doc_${i}.pdf`;
        let label = tags[i];
        let submissionId = data.id;

        // READ STATUS FROM DATABASE (0, 1, or 2)
        let currentStatus = parseInt(data['dat' + i + '_status']) || 0;

        const row = document.createElement('div');
        row.style = "display: flex; align-items: center; justify-content: space-between; padding: 10px; border-bottom: 1px solid #eee;";
        
        if (filePath && filePath !== "") {
            // Logic for pre-setting the UI based on status
            let statusText = "";
            let statusColor = "";
            let showActionBtns = "inline-block";
            let showCancelBtn = "none";

            if (currentStatus === 1) { // Accepted
                statusText = "Accepted";
                statusColor = "green";
                showActionBtns = "none";
                showCancelBtn = "inline-block";
            } else if (currentStatus === 2) { // Rejected
                statusText = "Rejected";
                statusColor = "red";
                showActionBtns = "none";
                showCancelBtn = "inline-block";
            }

            row.innerHTML = `
                <div style="flex: 1;">
                    <span class="fonterstr" style="font-weight:bold;">${label}:</span><br>
                    📄 <a href="${filePath}" target="_blank" style="color: blue;">${fileName}</a>
                </div>
                <div id="actions-${i}" style="display: flex; align-items: center; gap: 10px;">
                    <span id="status-text-${i}" style="font-weight:bold; color: ${statusColor};">${statusText}</span>
                    
                    <button class="btn-accept" onclick="updateStatus(${submissionId}, ${i}, 1)" 
                        style="display:${showActionBtns}; background:green; color:white; border:none; padding:5px 10px; cursor:pointer; border-radius:3px;">Accept</button>
                    
                    <button class="btn-reject" onclick="updateStatus(${submissionId}, ${i}, 2)" 
                        style="display:${showActionBtns}; background:red; color:white; border:none; padding:5px 10px; cursor:pointer; border-radius:3px;">Reject</button>
                    
                    <button class="btn-cancel" onclick="resetStatus(${submissionId}, ${i})" 
                        style="display:${showCancelBtn}; background:rgb(50,50,240); color:white; border:none; padding:5px 10px; cursor:pointer; border-radius:3px;">Cancel</button>
                </div>
            `;
        } else {
            row.innerHTML = `<div style="flex: 1; color: gray;"><strong>${label}:</strong> --None--</div>`;
        }
        displayArea.appendChild(row);
    }
    details.scrollTop = 0;
}




function listSelect() {
    var accounts = document.getElementById('accounts');
    accounts.style.width = '40vw';

    var details = document.getElementById('accountDETS');
    details.style.display = "block";

    var list = document.getElementById("userList");
    var selectedEmail = list.value;
	var selectedId = list.value;

    if (!selectedId) return;

   const formData = new FormData();
    formData.append('id', selectedId);
	

    const spacer = "&nbsp;".repeat(10);

    fetch('../php/getsubmission.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.status === "success") {
            const data = result.data;
            const displayArea = document.getElementById('accountz');
            
			
			
           
            nigerian(data, displayArea, spacer, details);
        }
    })
    .catch(error => console.error('Error:', error));
}




function updateStatus(id, fieldIndex, statusValue) {
    const statusText = document.getElementById(`status-text-${fieldIndex}`);
    const actionsDiv = document.getElementById(`actions-${fieldIndex}`);
    
    // UI Updates
    actionsDiv.querySelector('.btn-accept').style.display = 'none';
    actionsDiv.querySelector('.btn-reject').style.display = 'none';
    actionsDiv.querySelector('.btn-cancel').style.display = 'inline-block';
    
    statusText.innerText = (statusValue === 1) ? "Accepted" : "Rejected";
    statusText.style.color = (statusValue === 1) ? "green" : "red";

    sendUpdateToServer(id, fieldIndex, statusValue);
}

function resetStatus(id, fieldIndex) {
    const statusText = document.getElementById(`status-text-${fieldIndex}`);
    const actionsDiv = document.getElementById(`actions-${fieldIndex}`);
    
    // UI Reset
    actionsDiv.querySelector('.btn-accept').style.display = 'inline-block';
    actionsDiv.querySelector('.btn-reject').style.display = 'inline-block';
    actionsDiv.querySelector('.btn-cancel').style.display = 'none';
    
    statusText.innerText = "";
    
    // Send 0 to the database to reset it
    sendUpdateToServer(id, fieldIndex, 0);
}


function sendUpdateToServer(id, fieldIndex, statusValue) {
    const formData = new FormData();
    formData.append('id', id);
    formData.append('column_index', fieldIndex);
    formData.append('status', statusValue);

    fetch('../php/update_document_status.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(res => {
        if(res.status !== "success") {
            console.error("Database Error:", res.message);
            alert("Database update failed!");
        }
    })
    .catch(err => console.error("Fetch Error:", err));
}



function updateApplicationStatus(newStatus) {
   
    // get the id from the userlist.
    const selectedId = document.getElementById("userList").value;

    if (!selectedId) {
        alert("Please select a record first.");
        return;
    }

    const formData = new FormData();
    formData.append('id', selectedId);
    formData.append('status', newStatus);

    fetch('../php/update_main_status.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === "success") {
            console.log("Application status updated to " + newStatus);
        } else {
            alert("Error updating application status.");
        }
    })
    .catch(err => console.error("Error:", err));
}














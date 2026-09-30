function openMessage(title, text, attachmentsJson) {
    // 1. Find the elements
    var modal = document.getElementById('msgModalz');
    var body = document.getElementById('modalBodyz');
    var head = document.getElementById('modalTitlez');
    var attachDiv = document.getElementById('modalAttachmentsz'); // New element needed

    // 2. Put the text inside
    head.innerText = title;
    body.innerText = text;

    // 3. Handle Attachments
    if (attachDiv) {
        attachDiv.innerHTML = ""; // Always clear previous message's files
        
        if (attachmentsJson) {
            try {
                const files = JSON.parse(attachmentsJson);
                
                if (files.length > 0) {
                    attachDiv.innerHTML = "<hr><p><strong>Attachments:</strong></p>";
                    
                    files.forEach(file => {
                        const link = document.createElement('a');
                        link.href = "../" + file.path;
                        link.target = "_blank";
                        link.className = "modal-file-link";
                        link.innerHTML = "📎 " + (file.name || "View File");
                        link.style.display = "block";
                        link.style.marginBottom = "8px";
                        link.style.color = "#007bff";
                        
                        attachDiv.appendChild(link);
                    });
                }
            } catch (e) {
                console.error("Error parsing attachments:", e);
            }
        }
    }

    
    modal.style.display = 'flex';

}
function closeModalz() {
    document.getElementById('msgModalz').style.display = 'none';
	document.body.style.overflow = 'auto';
}


function closeModal() {
    document.getElementById('msgModal').style.display = 'none';
	document.body.style.overflow = 'auto';
}


function getdata() {
   window.scrollTo(0, 0);
    let processingCount = 0;
    let completedCount = 0;
    let rejected = 0;
    let verifying = 0;
    let expiringCount = 0; 

    const today = new Date();
	const notificationWindow = new Date();
notificationWindow.setMonth(today.getMonth() + 3);

  
    allStatuses.forEach(function(item) {
        
        // 1. Handle Statuses
        let s = Number(item.status);

        if (s === 0 || s == 0.4) {
            processingCount++;
        } else if (s === 1) {
            completedCount++;
        } else if (s === 9) {
            verifying++;
        } else if (s === 3) {
            rejected++;
        }

        if (item.last_modified) {
    let lastMod = new Date(item.last_modified);
    let expiryDate = new Date(lastMod);
    
    // Add 1 year to the last modified date to get the expiry date
    expiryDate.setFullYear(expiryDate.getFullYear() + 1);

    // Increment count if:
    // 1. It is already expired (today >= expiryDate)
    // 2. OR it is within the 3-month window (notificationWindow >= expiryDate)
    if (notificationWindow >= expiryDate) {
        expiringCount++;
    }
}
    });

    // 3. Update the HTML Cards
    updateCard('applications', totalApplications);
    updateCard('processing', processingCount);
    updateCard('verifying', verifying);
    updateCard('complete', completedCount);
    updateCard('rejects', rejected);
    
    // Update with the calculated count
    updateCard('expired', expiringCount);
	if(rejected<=0){
	document.getElementById("errormsg").style.display = "none";	
	}
	if(processing<=0){
	document.getElementById("infomsg").style.display = "none";	
	}
	
	
	
	
	
	
	
	if (adminMessage && adminMessage.trim() !== "") {
		
	document.getElementById("msgModal").style.display = "block";	
	document.getElementById("msgModal").style.display = "flex";
	document.getElementById("modalBody").innerText = adminMessage;	
       
    } 
	
	
	loader();
}

// function to add leading zeros (01, 02...)
function updateCard(id, value) {
    let element = document.getElementById(id);
    if (element) {
        element.innerText = value < 10 ? "0" + value : value;
    }
}


function closeError(){
	
	document.getElementById("errormsg").style.display = "none";	
}

function closeInfo(){
	
	document.getElementById("infomsg").style.display = "none";	
}


function inquire(){
	
	 window.location.href = "inquiry.php";
}

function faq(){
	
	 window.location.href = "faq.php";
}

function guides(){
	
	 window.location.href = "guideline.php";
}









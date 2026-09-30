




function listSelect(selectedEmail){

//alert("adsf");
		
	
var element = document.getElementById('accounts');

//element.style.backgroundColor = 'blue';
element.style.width='43vw';



var element = document.getElementById('accountDETS');
element.style.display="block";



	
	
	
	
	
	var list = document.getElementById("userList");
   // var selectedEmail = list.value; // This is the email from the <option value="...">

    if (!selectedEmail) return;

    // Send the email to our lookup script
    const formData = new FormData();
    formData.append('email', selectedEmail);

    fetch('../php/accountGet.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
    if (result.status === "success") {
        const data = result.data;
        const displayArea = document.getElementById('accountz');
        
        // 1. Define the fields we want to show
        // format: [Database_Column_Name, Display_Label]
        const fieldMapping = [
            ["email", "Email"],
            ["created_at", "Creation time"],
            ["file_path", "Account Type"], // Logic handled below
            ["company_account", "Company Account"],
            ["company_address", "Company Address"],
            ["username", "Username"],
            ["company_name", "Company Name"],
            ["business_type", "Business Type"],
            ["nationality", "Nationality"],
            ["contact_firstname", "Contact Firstname"],
            ["contact_lastname", "Contact Lastname"],
            ["contact_phone", "Contact Phone"],
            ["year_incorporated", "Year Incorporated"],
            ["rc_number", "Rc Number"],
            ["tin", "Tin"],
            ["company_address2", "Company Address2"],
            ["city", "City"],
            ["postal_code", "Postal Code"],
            ["country", "Country"],
            ["state", "State"],
            ["director_name1", "Director Name1"],
            ["director_name2", "Director Name2"],
            ["director_no", "Director No"],
            ["director_nationality", "Director Nationality"],
            ["director_address", "Director Address"],
            ["director_address2", "Director Address2"],
            ["director_city", "Director City"],
            ["director_postal", "Director Postal"],
            ["director_country", "Director Country"],
            ["director_state", "Director State"]
        ];

        // 2. Build the Grid UI
        let infoSection = `<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; padding: 10px;">`;

        fieldMapping.forEach(([column, label]) => {
            let val = data[column] || "N/A";

            // Special Logic: Account Type
            if (column === "file_path") {
                val = (data.file_path === "admin_admin") ? "Admin" : "User";
            }

            infoSection += `
                <div style="background-image: linear-gradient(to bottom,rgb(27,50,185), rgb(27,50,185),rgb(27,0,155)), linear-gradient(to right,rgb(27,50,185), rgb(27,0,155),rgb(27,50,185)); 
                            background-blend-mode: multiply; padding: 10px; border-radius: 5px; border-left: 4px solid #007bff; min-height: 60px;">
                    <small style="color: white; font-weight: bold;">${label}</small><br>
                    <span style="color:rgb(220,220,220); font-size: 0.95rem; word-break: break-word;">${val}</span>
                </div>`;
        });

        infoSection += `</div>`;
        
        // 3. Update the display area
        displayArea.innerHTML = infoSection;
    }
})
    .catch(error => console.error('Error:', error));
	


}


function listClose(){

//alert("adsf");
		
	
var element = document.getElementById('accounts');

//element.style.backgroundColor = 'blue';
element.style.width='96vw';



var element = document.getElementById('accountDETS');
element.style.display="none";

var list = document.getElementById("userList");
    list.selectedIndex = -1;


}



function DeleteAccount() {
    var list = document.getElementById("userList");
    var selectedEmail = list.value;

    if (!selectedEmail || selectedEmail === "") {
        alert("Please select an account first.");
        return;
    }

    // The Warning Popup
    const warningMessage = `⚠️ ALERT: ACTION CONFIRMATION!\n\n` +
                           `You are about to delete the account for: ${selectedEmail}\n\n` +
                           `This will permanently remove:\n` +
                           `- All the user registration data\n` +
                           `- All the user's applications and registrations\n` +
                           `- All the user's messages \n` +
                           `- ALL uploaded document files from the server.\n\n` +
                           `THIS PROCESS CANNOT BE UNDONE. Do you want to proceed?`;

    if (confirm(warningMessage)) {
        const formData = new FormData();
        formData.append('email', selectedEmail);

        // The Fetch Request
        fetch('../php/delete_full_account.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.status === "success") {
                alert("Account and all associated files have been wiped successfully.");
                location.reload(); // Refresh the page to update the UI
            } else {
                alert("Error: " + result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("A system error occurred while trying to delete the account.");
        });
    }
}



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
    const sendBtn = document.querySelector(".btn-send");
    
   
    const emailElem = document.getElementById('accountz').querySelector('span');
    if (!emailElem) {
        alert("Receiver email not found.");
        return;
    }
    const selectedEmail = emailElem.innerText;

    if (!adminNote.trim()) {
        alert("Please enter a message.");
        return;
    }

    sendBtn.disabled = true;
    sendBtn.innerText = "Sending...";

    const formData = new FormData();
    formData.append('email', selectedEmail);
    formData.append('message', adminNote);
	
	formData.append('app_tag', -1000);
    
    // Add the files from your dynamic list
    selectedFiles.forEach((file, i) => {
        formData.append(`file_${i + 1}`, file);
    });

    fetch('../php/save_message.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(res => {
        sendBtn.disabled = false;
        sendBtn.innerText = "Send";
        
        if (res.status === "success") {
            alert(res.message);
            document.getElementById("adminNote").value = ""; 
            
            // Clear the file list and array
            selectedFiles = [];
            document.getElementById('file-list').innerHTML = '';
            document.getElementById('attach-btn').disabled = false;
            
            toggleMsg(false); 
        } else {
            alert("Error: " + res.message);
        }
    })
    .catch(err => {
        sendBtn.disabled = false;
        sendBtn.innerText = "Send";
        console.error("Error:", err);
    });
}





let selectedFiles = [];

document.addEventListener('DOMContentLoaded', function() {
    
    const fileInput = document.getElementById('file-input');
    
    // Check if the element exists before adding the listener
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            if (selectedFiles.length < 20) {
                selectedFiles.push(file);
                renderFileList();
            }

            if (selectedFiles.length >= 20) {
                const attachBtn = document.getElementById('attach-btn');
                if (attachBtn) attachBtn.disabled = true;
            }
            
            this.value = ''; 
        });
    } else {
        console.warn("Could not find 'file-input' yet. If this is in a modal, make sure the modal HTML is present in the DOM.");
    }
});



function renderFileList() {
    const list = document.getElementById('file-list');
	
	if (!list) {
        console.error("Error: Could not find the 'file-list' div in the HTML!");
        return;
    }
	
	console.log("Updating list with " + selectedFiles.length + " files.");
	
    list.innerHTML = '';
    selectedFiles.forEach((file, index) => {
        list.innerHTML += `
            <div class="file-item">
                <span>${file.name}</span>
                <button class="remove-btn" onclick="removeFile(${index})">✖</button>
            </div>`;
    });



}

function removeFile(index) {
    selectedFiles.splice(index, 1);
    document.getElementById('attach-btn').disabled = false;
    renderFileList();
}




let trueID=-1;

function handleRowClick(rowElement) {
   
    const recordId = rowElement.getAttribute('data-value');
    
  
    if (typeof listSelect === "function") {
       trueID=recordId;
	   
	    const myDiv = document.getElementById('accountDETS');

myDiv.scrollTo({
  top: 0,
  left: 0,
  behavior: 'smooth'
});
	   
	
        listSelect(recordId);
    }

    
}





function deleteRecord(id, btnElement) {
    btnElement.disabled = true;
    btnElement.style.opacity = "0.5";
    btnElement.style.cursor = "not-allowed";
	
	
    fetch('../php/delete_submission.php?id=' + id)
    .then(response => response.text())
    .then(data => {
        if (data.trim() === "success") {
            // 2. Find the closest Table Row (tr) to the button that was clicked
            const row = btnElement.closest('tr');
            
            // 3. Add a fade-out effect
            row.style.transition = "all 0.4s ease";
            row.style.opacity = "0";
            row.style.transform = "translateX(20px)";
            
            // 4. Remove it from the HTML after the animation finishes
            setTimeout(() => {
                row.remove();
                updateEntryCount(); 
            }, 400);
        } else {
            alert("Server Error: " + data);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Failed to connect to the server.");
    });
}


function updateEntryCount() {
   //   location.reload(); 
}



function toggleSortMenu() {
    document.getElementById("sortMenu").classList.toggle("show");
}


window.onclick = function(event) {
    if (!event.target.matches('.btn-sort')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}



function autoSearch() {
    const query = document.getElementById("searchInput").value;
    const sort = "<?php echo $sort_type; ?>"; // Keep current sort

    // Fetch the filtered table rows from the server
    fetch(`../php/searching.php?search=${encodeURIComponent(query)}&sort=${sort}`)
    .then(response => response.text())
    .then(html => {
        
        document.querySelector("#userTable tbody").innerHTML = html;
        
        
    })
    .catch(err => console.warn('Search Error:', err));
}


function reloadTableDiv() {
    const urlParams = new URLSearchParams(window.location.search);
    const search = urlParams.get('search') || '';
    const sort = urlParams.get('sort') || 'default';
    const page = urlParams.get('page') || '1';

   
    fetch(`../php/search_logic.php?search=${search}&sort=${sort}&page=${page}`)
    .then(response => response.text())
    .then(html => {
        
        document.querySelector("#userTable tbody").innerHTML = html;
       
    })
    .catch(err => console.warn("Failed to reload div:", err));
}



function DeleteAccount2(selectedEmail) {
   // var list = document.getElementById("userList");
    //var selectedEmail = list.value;

    if (!selectedEmail || selectedEmail === "") {
        alert("Please select an account first.");
        return;
    }

    // The Warning Popup
    const warningMessage = `⚠️ ALERT: ACTION CONFIRMATION!\n\n` +
                           `You are about to delete the account for: ${selectedEmail}\n\n` +
                           `This will permanently remove:\n` +
                           `- All the user registration data\n` +
                           `- All the user's applications and registrations\n` +
                           `- All the user's messages \n` +
                           `- ALL uploaded document files from the server.\n\n` +
                           `THIS PROCESS CANNOT BE UNDONE. Do you want to proceed?`;

    if (confirm(warningMessage)) {
        const formData = new FormData();
        formData.append('email', selectedEmail);

        // The Fetch Request
        fetch('../php/delete_full_account.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.status === "success") {
                alert("Account and all associated files have been wiped successfully.");
                location.reload(); // Refresh the page to update the UI
            } else {
                alert("Error: " + result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("A system error occurred while trying to delete the account.");
        });
    }
}








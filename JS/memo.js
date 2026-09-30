function sendMessage() {
    const adminNote = document.getElementById("adminNote").value;
  const sendBtn = document.querySelector(".btn-send");
	
    if (!adminNote.trim()) {
        alert("No Message Inputed.");
        return;
    }


sendBtn.disabled = true;
sendBtn.innerText = "Sending...";
    
   
    const formData = new FormData();
    
    formData.append('message', adminNote);
	
	formData.append('app_tag', -1000);
    
    // Add the files from your dynamic list
    selectedFiles.forEach((file, i) => {
        formData.append(`file_${i + 1}`, file);
    });


    fetch('../php/memo_message.php', {
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
			selectedFiles="";
            toggleMsg(false); // Close area
        } else {
            alert("Error: " + res.message);
        }
    })
    .catch(err => console.error("Error:", err));
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






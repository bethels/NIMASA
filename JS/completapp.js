



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
    // --- Section 1: Core Vessel/Agent Identity ---
    1: "Name of Company/Agent",
    2: "Name of Vessel (MT/MV)",
    3: "Type of Vessel",
    4: "GRT",
    5: "Vessel Registration Status",

    // --- Section 2: Ownership & Origin ---
    6: "Cabotage Affidavit Form (CAF) (Signed/Stamped by Federal High Court)",
    7: "Bill of Sale",
    8: "Evidence of Ownership or Copy of Nigeria Ship Registry Certificate",
    9: "Builders Certificate",

    // --- Section 3: Statutory Applications & Crewing ---
    10: "Application to participate in Cabotage Trade Form (Signed/Stamped by Federal High Court)",
    11: "Crew List Declaration Form (Signed/Stamped by Federal High Court)",
    12: "Declaration of Ownership Form (Signed/Stamped by Federal High Court)",
    13: "Detailed Crew list on company letterhead (Signed/Stamped/Dated by Master)",
    14: "Declaration of Eligibility (Signed/Stamped by Federal High Court)",
    15: "Copy of Minimum Safe Manning Certificate (SMC)",
    16: "Completed Maritime Labour Declaration Form",
    17: "",

    // --- Section 4: Regulatory Compliance & NIMASA ---
    18: "Evidence of Payment of Sea Protection Levy (SPL) (Exempt if < 100 GRT)",
    19: "Copy of Certificate of Incorporation",
    20: "Memorandum and Articles of Association",
    21: "CAC (2) & CAC (7) (Share capital ≥ ₦25M)",
	22: "Cabotage Application for Waiver Form",
    23: "Evidence of Registration as a Shipping Company with NIMASA",

    // --- Section 5: Renewals & Custom Documents ---
    24: "Evidence of 2% Surcharge Payment (For Renewal)",
    25: "Copy of last Waiver Certificate or receipt for waiver processing fees",
    26: "Evidence of active Training of Nigerian Cadet (For Renewal)",
    27: "Copy of survey report OR Temporary Importation document from customs"
};

const newTag={
	
		1: "IMO Number",
		2: "Vessel Official Number",
		
		3: "Tax Clearance Certificate",
		4: "Manning Liscence",
		5: "Maritime Labour Certificate",
		
		
	
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
            <div style="background-image: linear-gradient(to bottom,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185)), linear-gradient(to right,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185)); background-blend-mode: multiply; padding: 10px; border-radius: 5px; border-left: 4px solid rgb(255,50,100);">
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
  

	 //imo number
	 let nval = data['imo_number'] || "N/A";
        infoSection += `
            <div style="background-image: linear-gradient(to bottom,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185)), linear-gradient(to right,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185)); background-blend-mode: multiply; padding: 10px; border-radius: 5px; border-left: 4px solid #007bff;">
                <small style="color: white; font-weight: bold;">${newTag[1]}</small><br>
                <span style="color:rgb(220,220,220);">${nval}</span>
            </div>`;
    
	
	 //Vessel Official Number
	 nval = data['official_number'] || "N/A";
        infoSection += `
            <div style="background-image: linear-gradient(to bottom,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185)), linear-gradient(to right,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185)); background-blend-mode: multiply; padding: 10px; border-radius: 5px; border-left: 4px solid #007bff;">
                <small style="color: white; font-weight: bold;">${newTag[2]}</small><br>
                <span style="color:rgb(220,220,220);">${nval}</span>
            </div>`;
    









  infoSection += `</div><hr><h3>Uploaded Documents</h3>`;
    displayArea.innerHTML = infoSection;











//tax clearance Certificate

let nfieldKey = 'tax_clearance';
let nfilePath = data[nfieldKey];

// 2.nfileName fallback logic
let nfileName = data[nfieldKey + '_name'] || "Tax_Clearance.pdf";
let nlabel = newTag[3] || "Tax Clearance"; // Fallback if tags[2] is missing
let nsubmissionId = data.id;

// 3. Status Handling ( 0=Pending, 1=Accepted, 2=Rejected)
let ncurrentStatus = parseInt(data[nfieldKey + '_status']) || 0;

let row = document.createElement('div');
row.className = "document-row"; 
row.style = "display: flex; align-items: center; justify-content: space-between; padding: 10px; border-bottom: 1px solid #eee;";

if (nfilePath && nfilePath !== "") {
    let nstatusText = "";
    let nstatusColor = "";

    if (ncurrentStatus === 1) {
        nstatusText = "Accepted";
        nstatusColor = "green";
    } else if (ncurrentStatus === 2) {
        nstatusText = "Rejected";
        nstatusColor = "red";
    } else {
        nstatusText = "Pending Review";
        nstatusColor = "orange";
    }

    // Fixed: Changed IDs from -${i} to -tax-clearance to ensure uniqueness
    row.innerHTML = `
        <div style="flex: 1;">
            <span class="fonterstr" style="font-weight:bold;">${nlabel}:</span><br>
            📄 <a href="${nfilePath}" target="_blank" style="color: blue; text-decoration: underline;">${nfileName}</a>
        </div>
        <div id="actions-tax" style="display: flex; align-items: center; gap: 10px;">
            <span id="status-text-tax" style="font-weight:200; color: ${nstatusColor};">
                ${nstatusText}
            </span>
        </div>
    `;
} else {
    row.innerHTML = `
        <div style="flex: 1; color: gray;">
            <strong style="color: #333;">${nlabel}:</strong> Not Uploaded
        </div>`;
}

displayArea.appendChild(row);









//Maning Liscence Certificate

 nfieldKey = 'manning_liscense';
nfilePath = data[nfieldKey];

// 2.nfileName fallback logic
 nfileName = data[nfieldKey + '_name'] || "Manning_Liscense.pdf";
 nlabel = newTag[4] || "Manning_Liscense"; 
 nsubmissionId = data.id;

// 3. Status Handling ( 0=Pending, 1=Accepted, 2=Rejected)
ncurrentStatus = parseInt(data[nfieldKey + '_status']) || 0;

row = document.createElement('div');
row.className = "document-row"; 
row.style = "display: flex; align-items: center; justify-content: space-between; padding: 10px; border-bottom: 1px solid #eee;";

if (nfilePath && nfilePath !== "") {
    let nstatusText = "";
    let nstatusColor = "";

    if (ncurrentStatus === 1) {
        nstatusText = "Accepted";
        nstatusColor = "green";
    } else if (ncurrentStatus === 2) {
        nstatusText = "Rejected";
        nstatusColor = "red";
    } else {
        nstatusText = "Pending Review";
        nstatusColor = "orange";
    }

    // Fixed: Changed IDs from -${i} to -tax-clearance to ensure uniqueness
    row.innerHTML = `
        <div style="flex: 1;">
            <span class="fonterstr" style="font-weight:bold;">${nlabel}:</span><br>
            📄 <a href="${nfilePath}" target="_blank" style="color: blue; text-decoration: underline;">${nfileName}</a>
        </div>
        <div id="actions-tax" style="display: flex; align-items: center; gap: 10px;">
            <span id="status-text-tax" style="font-weight:200; color: ${nstatusColor};">
                ${nstatusText}
            </span>
        </div>
    `;
} else {
    row.innerHTML = `
        <div style="flex: 1; color: gray;">
            <strong style="color: #333;">${nlabel}:</strong> Not Uploaded
        </div>`;
}

displayArea.appendChild(row);
















//Maritime labour Certificate

 nfieldKey = 'maritime_labour';
 nfilePath = data[nfieldKey];

// 2.nfileName fallback logic
 nfileName = data[nfieldKey + '_name'] || "Maritime_Labour.pdf";
 nlabel = newTag[5] || "Maritime Labour"; // Fallback if tags[2] is missing
 nsubmissionId = data.id;

// 3. Status Handling ( 0=Pending, 1=Accepted, 2=Rejected)
 ncurrentStatus = parseInt(data[nfieldKey + '_status']) || 0;

row = document.createElement('div');
row.className = "document-row"; // Better to use classes for styling
row.style = "display: flex; align-items: center; justify-content: space-between; padding: 10px; border-bottom: 1px solid #eee;";

if (nfilePath && nfilePath !== "") {
    let nstatusText = "";
    let nstatusColor = "";

    if (ncurrentStatus === 1) {
        nstatusText = "Accepted";
        nstatusColor = "green";
    } else if (ncurrentStatus === 2) {
        nstatusText = "Rejected";
        nstatusColor = "red";
    } else {
        nstatusText = "Pending Review";
        nstatusColor = "orange";
    }

    // Fixed: Changed IDs from -${i} to -tax-clearance to ensure uniqueness
    row.innerHTML = `
        <div style="flex: 1;">
            <span class="fonterstr" style="font-weight:bold;">${nlabel}:</span><br>
            📄 <a href="${nfilePath}" target="_blank" style="color: blue; text-decoration: underline;">${nfileName}</a>
        </div>
        <div id="actions-tax" style="display: flex; align-items: center; gap: 10px;">
            <span id="status-text-tax" style="font-weight:200; color: ${nstatusColor};">
                ${nstatusText}
            </span>
        </div>
    `;
} else {
    row.innerHTML = `
        <div style="flex: 1; color: gray;">
            <strong style="color: #333;">${nlabel}:</strong> Not Uploaded
        </div>`;
}

displayArea.appendChild(row);










































   
   
   
   
   


   
    for (let i = 6; i <= 27; i++) {
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
			else {
        statusText = "Pending Review";
        statusColor = "orange";
    }

            row.innerHTML = `
                <div style="flex: 1;">
                    <span class="fonterstr" style="font-weight:bold;">${label}:</span><br>
                    📄 <a href="${filePath}" target="_blank" style="color: blue;">${fileName}</a>
                </div>
                <div id="actions-${i}" style="display: flex; align-items: center; gap: 10px;">
                    <span id="status-text-${i}" style="font-weight:200; color: ${statusColor};">${statusText}</span>
                    
                  </div>
            `;
        } else {
            row.innerHTML = `<div style="flex: 1; color: gray;"><strong>${label}:</strong> --None--</div>`;
        }
        displayArea.appendChild(row);
    }
    details.scrollTop = 0;
}






function foreign(data, displayArea, spacer, details) {
  const tags = {
    1: "Name of Company/Agent",
    2: "Name of Vessel (MT/MV)",
    3: "Type of Vessel",
    4: "GRT",
    5: "Vessel Registration Status",
    6: "Application to participate in Cabotage Trade Form (Signed and Stamped by the Federal High Court)",
    7: "Copy of Foreign Ship registry Certificate",
    8: "Evidence of Ownership or Copy of Nigeria Ship Registry Certificate",
    9: "Crew List Declaration Form (Signed and Stamped by the Federal High Court)",
    10: "Cabotage Application for Waiver Form",
    11: "Declaration of Ownership Form (Signed and Stamped by the Federal High Court)",
    12: "Detailed Crew list on company letterhead (Signed, stamped and dated by the Master)",
    13: "Copy Minimum Safe Manning Certificate (SMC)",
    14: "Completed Maritime Labour Declaration Form",
    15: "Certified True Copy of Certificate of Incorporation of the Owner/Charterer",
    16: "Certified True Copy of Memorandum and Articles of Association",
    17: "CAC (2) & CAC (3) (Share capital ≥ ₦25M, Particulars of Directors, Allotment of Shares)",
    18: "Company’s Current Tax Clearance Certificates (Last 3 years)",
    19: "Evidence of Registration as a Shipping Company with NIMASA",
    20: "Evidence of Payment of Sea Protection Levy (SPL)",
    21: "Copy of survey report OR Temporary Importation document from customs",
    22: "Charterer Party Agreement (Relationship between Charters and Owners)",
    23: "Evidence of pre-qualification and Tender / Contract for engagement",
    24: "Copy of Current Class Certificate of Vessel",
    25: "Copy of International Tonnage Certificate",
    26: "International Safety Management Certificate",
    27: "Copy of Vessel’s Current Q88 (where applicable)",
    28: "Copy of Vessel’s Insurance Policy (P&I)",
    29: "Evidence of 2% Surcharge Payment (For Renewal)",
    30: "Copy of last Waiver Certificate or receipt for waiver processing fees",
    31: "Evidence of active Training of Nigerian Cadet (For Renewal)",
    32: "Evidence of Dry-docking in Nigeria or letter attesting to non-availability",
    33: "Historical cargo operations data (Last five loading and discharging ports)"
};
const newTag={
	
		1: "IMO Number",
		2: "Vessel Official Number",
		
		3: "Tax Clearance Certificate",
		4: "Manning Liscence",
		5: "Maritime Labour Certificate",
		
		
	
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
        { label: "Application Type", val: "Foreign Waiver" }
    ];

    headerCards.forEach(card => {
        infoSection += `
            <div style="background-image: linear-gradient(to bottom,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185)), linear-gradient(to right,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185)); background-blend-mode: multiply; padding: 10px; border-radius: 5px; border-left: 4px solid rgb(255,50,100);">
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
   
	 //imo number
	 let nval = data['imo_number'] || "N/A";
        infoSection += `
            <div style="background-image: linear-gradient(to bottom,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185)), linear-gradient(to right,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185)); background-blend-mode: multiply; padding: 10px; border-radius: 5px; border-left: 4px solid #007bff;">
                <small style="color: white; font-weight: bold;">${newTag[1]}</small><br>
                <span style="color:rgb(220,220,220);">${nval}</span>
            </div>`;
    
	
	 //Vessel Official Number
	 nval = data['official_number'] || "N/A";
        infoSection += `
            <div style="background-image: linear-gradient(to bottom,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185)), linear-gradient(to right,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185)); background-blend-mode: multiply; padding: 10px; border-radius: 5px; border-left: 4px solid #007bff;">
                <small style="color: white; font-weight: bold;">${newTag[2]}</small><br>
                <span style="color:rgb(220,220,220);">${nval}</span>
            </div>`;
    









  infoSection += `</div><hr><h3>Uploaded Documents</h3>`;
    displayArea.innerHTML = infoSection;











//tax clearance Certificate

let nfieldKey = 'tax_clearance';
let nfilePath = data[nfieldKey];

// 2.nfileName fallback logic
let nfileName = data[nfieldKey + '_name'] || "Tax_Clearance.pdf";
let nlabel = newTag[3] || "Tax Clearance"; // Fallback if tags[2] is missing
let nsubmissionId = data.id;

// 3. Status Handling ( 0=Pending, 1=Accepted, 2=Rejected)
let ncurrentStatus = parseInt(data[nfieldKey + '_status']) || 0;

let row = document.createElement('div');
row.className = "document-row"; 
row.style = "display: flex; align-items: center; justify-content: space-between; padding: 10px; border-bottom: 1px solid #eee;";

if (nfilePath && nfilePath !== "") {
    let nstatusText = "";
    let nstatusColor = "";

    if (ncurrentStatus === 1) {
        nstatusText = "Accepted";
        nstatusColor = "green";
    } else if (ncurrentStatus === 2) {
        nstatusText = "Rejected";
        nstatusColor = "red";
    } else {
        nstatusText = "Pending Review";
        nstatusColor = "orange";
    }

    // Fixed: Changed IDs from -${i} to -tax-clearance to ensure uniqueness
    row.innerHTML = `
        <div style="flex: 1;">
            <span class="fonterstr" style="font-weight:bold;">${nlabel}:</span><br>
            📄 <a href="${nfilePath}" target="_blank" style="color: blue; text-decoration: underline;">${nfileName}</a>
        </div>
        <div id="actions-tax" style="display: flex; align-items: center; gap: 10px;">
            <span id="status-text-tax" style="font-weight:200; color: ${nstatusColor};">
                ${nstatusText}
            </span>
        </div>
    `;
} else {
    row.innerHTML = `
        <div style="flex: 1; color: gray;">
            <strong style="color: #333;">${nlabel}:</strong> Not Uploaded
        </div>`;
}

displayArea.appendChild(row);









//Maning Liscence Certificate

 nfieldKey = 'manning_liscense';
nfilePath = data[nfieldKey];

// 2.nfileName fallback logic
 nfileName = data[nfieldKey + '_name'] || "Manning_Liscense.pdf";
 nlabel = newTag[4] || "Manning_Liscense"; 
 nsubmissionId = data.id;

// 3. Status Handling ( 0=Pending, 1=Accepted, 2=Rejected)
ncurrentStatus = parseInt(data[nfieldKey + '_status']) || 0;

row = document.createElement('div');
row.className = "document-row"; 
row.style = "display: flex; align-items: center; justify-content: space-between; padding: 10px; border-bottom: 1px solid #eee;";

if (nfilePath && nfilePath !== "") {
    let nstatusText = "";
    let nstatusColor = "";

    if (ncurrentStatus === 1) {
        nstatusText = "Accepted";
        nstatusColor = "green";
    } else if (ncurrentStatus === 2) {
        nstatusText = "Rejected";
        nstatusColor = "red";
    } else {
        nstatusText = "Pending Review";
        nstatusColor = "orange";
    }

    // Fixed: Changed IDs from -${i} to -tax-clearance to ensure uniqueness
    row.innerHTML = `
        <div style="flex: 1;">
            <span class="fonterstr" style="font-weight:bold;">${nlabel}:</span><br>
            📄 <a href="${nfilePath}" target="_blank" style="color: blue; text-decoration: underline;">${nfileName}</a>
        </div>
        <div id="actions-tax" style="display: flex; align-items: center; gap: 10px;">
            <span id="status-text-tax" style="font-weight:200; color: ${nstatusColor};">
                ${nstatusText}
            </span>
        </div>
    `;
} else {
    row.innerHTML = `
        <div style="flex: 1; color: gray;">
            <strong style="color: #333;">${nlabel}:</strong> Not Uploaded
        </div>`;
}

displayArea.appendChild(row);
















//Maritime labour Certificate

 nfieldKey = 'maritime_labour';
 nfilePath = data[nfieldKey];

// 2.nfileName fallback logic
 nfileName = data[nfieldKey + '_name'] || "Maritime_Labour.pdf";
 nlabel = newTag[5] || "Maritime Labour"; // Fallback if tags[2] is missing
 nsubmissionId = data.id;

// 3. Status Handling ( 0=Pending, 1=Accepted, 2=Rejected)
 ncurrentStatus = parseInt(data[nfieldKey + '_status']) || 0;

row = document.createElement('div');
row.className = "document-row"; // Better to use classes for styling
row.style = "display: flex; align-items: center; justify-content: space-between; padding: 10px; border-bottom: 1px solid #eee;";

if (nfilePath && nfilePath !== "") {
    let nstatusText = "";
    let nstatusColor = "";

    if (ncurrentStatus === 1) {
        nstatusText = "Accepted";
        nstatusColor = "green";
    } else if (ncurrentStatus === 2) {
        nstatusText = "Rejected";
        nstatusColor = "red";
    } else {
        nstatusText = "Pending Review";
        nstatusColor = "orange";
    }

    // Fixed: Changed IDs from -${i} to -tax-clearance to ensure uniqueness
    row.innerHTML = `
        <div style="flex: 1;">
            <span class="fonterstr" style="font-weight:bold;">${nlabel}:</span><br>
            📄 <a href="${nfilePath}" target="_blank" style="color: blue; text-decoration: underline;">${nfileName}</a>
        </div>
        <div id="actions-tax" style="display: flex; align-items: center; gap: 10px;">
            <span id="status-text-tax" style="font-weight:200; color: ${nstatusColor};">
                ${nstatusText}
            </span>
        </div>
    `;
} else {
    row.innerHTML = `
        <div style="flex: 1; color: gray;">
            <strong style="color: #333;">${nlabel}:</strong> Not Uploaded
        </div>`;
}

displayArea.appendChild(row);










































   
   
   
   
   



   
    for (let i = 6; i <= 34; i++) {
		if(i==11){i+=1; }
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
				else {
        statusText = "Pending Review";
        statusColor = "orange";
    }

            row.innerHTML = `
                <div style="flex: 1;">
                    <span class="fonterstr" style="font-weight:bold;">${label}:</span><br>
                    📄 <a href="${filePath}" target="_blank" style="color: blue;">${fileName}</a>
                </div>
                <div id="actions-${i}" style="display: flex; align-items: center; gap: 10px;">
                    <span id="status-text-${i}" style="font-weight:200; color: ${statusColor};">${statusText}</span>
                    
                  </div>
            `;
        } else {
            row.innerHTML = `<div style="flex: 1; color: gray;"><strong>${label}:</strong> --None--</div>`;
        }
        displayArea.appendChild(row);
    }
    details.scrollTop = 0;
}






function bareboat(data, displayArea, spacer, details) {
    const tags = {
    // --- Section 1: Core Vessel & Company Identity ---
    1: "Name of Company/Agent",
    2: "Name of Vessel (MT/MV)",
    3: "Type of Vessel",
    4: "GRT",
    5: "Vessel Registration Status",

    // --- Section 2: Statutory Legal Forms (High Court Stamped) ---
    6: "Cabotage Affidavit Form (CAF) (Signed/Stamped by Federal High Court)",
    7: "Application to Participate in Cabotage Trade Form (Signed/Stamped by Federal High Court)",
    8: "Bill of Sale / Evidence of Ownership / Nigeria Ship Registry Certificate",
    9: "Crew List Declaration Form (Signed/Stamped by Federal High Court)",
    10: "Declaration of Ownership Form (Signed/Stamped by Federal High Court)",

    // --- Section 3: Crewing & Safety ---
    11: "Detailed Crew List on Company Letterhead (Signed/Stamped/Dated by Master)",
    12: "Copy of Minimum Safe Manning Certificate (SMC)",
    13: "Completed Maritime Labour Declaration Form",

    // --- Section 4: Corporate & Tax Compliance ---
    14: "CAC (2) & CAC (7) (Share Capital ≥ ₦25M)",
    15: "Copy of Certificate of Incorporation",
    16: "Memorandum and Articles of Association",
    17: "Tax Clearance Certificates (Last 3 Years)",

    // --- Section 5: NIMASA & Charter Agreements ---
    18: "Evidence of Registration as a Shipping Company with NIMASA",
    19: "Evidence of Payment of Sea Protection Levy (SPL) (Exempt if < 100 GRT)",
	20: "",
    21: "Bareboat Charter Agreement (Minimum 5-year period specification)",
    22: "Proof of Relationship: Foreign Owner & Nigerian Management (For Foreign Owned)",

    // --- Section 6: Registry & Customs ---
    23: "Copy of Survey Report OR Temporary Importation Document from Customs",
    24: "Consent to Delete from Foreign Registry",
    25: "Evidence of Flag Suspension",

    // --- Section 7: Renewal-Specific Evidence ---
    26: "Evidence of 2% Surcharge Payment (For Renewal)",
    27: "Copy of Last Waiver Certificate or Receipt for Waiver Processing Fees",
    28: "Evidence of Active Training of Nigerian Cadet (For Renewal)",
    29: "Evidence of Dry-docking in Nigeria or Non-availability Letter (For Renewal)",

    // --- Section 8: Operations & Waivers ---
    30: "Historical Cargo Operations Data (Last 5 Loading/Discharging Ports)",
    31: "Cabotage Application for Waiver Form"
};
const newTag={
	
		1: "IMO Number",
		2: "Vessel Official Number",
		
		3: "Tax Clearance Certificate",
		4: "Manning Liscence",
		5: "Maritime Labour Certificate"
		
		
	
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
        { label: "Application Type", val: "Bareboat Waiver" }
    ];

    headerCards.forEach(card => {
        infoSection += `
            <div style="background-image: linear-gradient(to bottom,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185)), linear-gradient(to right,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185)); background-blend-mode: multiply; padding: 10px; border-radius: 5px; border-left: 4px solid rgb(255,50,100);">
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
   
	 //imo number
	 let nval = data['imo_number'] || "N/A";
        infoSection += `
            <div style="background-image: linear-gradient(to bottom,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185)), linear-gradient(to right,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185)); background-blend-mode: multiply; padding: 10px; border-radius: 5px; border-left: 4px solid #007bff;">
                <small style="color: white; font-weight: bold;">${newTag[1]}</small><br>
                <span style="color:rgb(220,220,220);">${nval}</span>
            </div>`;
    
	
	 //Vessel Official Number
	 nval = data['official_number'] || "N/A";
        infoSection += `
            <div style="background-image: linear-gradient(to bottom,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185)), linear-gradient(to right,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185)); background-blend-mode: multiply; padding: 10px; border-radius: 5px; border-left: 4px solid #007bff;">
                <small style="color: white; font-weight: bold;">${newTag[2]}</small><br>
                <span style="color:rgb(220,220,220);">${nval}</span>
            </div>`;
    









  infoSection += `</div><hr><h3>Uploaded Documents</h3>`;
    displayArea.innerHTML = infoSection;











//tax clearance Certificate

let nfieldKey = 'tax_clearance';
let nfilePath = data[nfieldKey];

// 2.nfileName fallback logic
let nfileName = data[nfieldKey + '_name'] || "Tax_Clearance.pdf";
let nlabel = newTag[3] || "Tax Clearance"; // Fallback if tags[2] is missing
let nsubmissionId = data.id;

// 3. Status Handling ( 0=Pending, 1=Accepted, 2=Rejected)
let ncurrentStatus = parseInt(data[nfieldKey + '_status']) || 0;

let row = document.createElement('div');
row.className = "document-row"; 
row.style = "display: flex; align-items: center; justify-content: space-between; padding: 10px; border-bottom: 1px solid #eee;";

if (nfilePath && nfilePath !== "") {
    let nstatusText = "";
    let nstatusColor = "";

    if (ncurrentStatus === 1) {
        nstatusText = "Accepted";
        nstatusColor = "green";
    } else if (ncurrentStatus === 2) {
        nstatusText = "Rejected";
        nstatusColor = "red";
    } else {
        nstatusText = "Pending Review";
        nstatusColor = "orange";
    }

    // Fixed: Changed IDs from -${i} to -tax-clearance to ensure uniqueness
    row.innerHTML = `
        <div style="flex: 1;">
            <span class="fonterstr" style="font-weight:bold;">${nlabel}:</span><br>
            📄 <a href="${nfilePath}" target="_blank" style="color: blue; text-decoration: underline;">${nfileName}</a>
        </div>
        <div id="actions-tax" style="display: flex; align-items: center; gap: 10px;">
            <span id="status-text-tax" style="font-weight:200; color: ${nstatusColor};">
                ${nstatusText}
            </span>
        </div>
    `;
} else {
    row.innerHTML = `
        <div style="flex: 1; color: gray;">
            <strong style="color: #333;">${nlabel}:</strong> Not Uploaded
        </div>`;
}

displayArea.appendChild(row);









//Maning Liscence Certificate

 nfieldKey = 'manning_liscense';
nfilePath = data[nfieldKey];

// 2.nfileName fallback logic
 nfileName = data[nfieldKey + '_name'] || "Manning_Liscense.pdf";
 nlabel = newTag[4] || "Manning_Liscense"; 
 nsubmissionId = data.id;

// 3. Status Handling ( 0=Pending, 1=Accepted, 2=Rejected)
ncurrentStatus = parseInt(data[nfieldKey + '_status']) || 0;

row = document.createElement('div');
row.className = "document-row"; 
row.style = "display: flex; align-items: center; justify-content: space-between; padding: 10px; border-bottom: 1px solid #eee;";

if (nfilePath && nfilePath !== "") {
    let nstatusText = "";
    let nstatusColor = "";

    if (ncurrentStatus === 1) {
        nstatusText = "Accepted";
        nstatusColor = "green";
    } else if (ncurrentStatus === 2) {
        nstatusText = "Rejected";
        nstatusColor = "red";
    } else {
        nstatusText = "Pending Review";
        nstatusColor = "orange";
    }

    // Fixed: Changed IDs from -${i} to -tax-clearance to ensure uniqueness
    row.innerHTML = `
        <div style="flex: 1;">
            <span class="fonterstr" style="font-weight:bold;">${nlabel}:</span><br>
            📄 <a href="${nfilePath}" target="_blank" style="color: blue; text-decoration: underline;">${nfileName}</a>
        </div>
        <div id="actions-tax" style="display: flex; align-items: center; gap: 10px;">
            <span id="status-text-tax" style="font-weight:200; color: ${nstatusColor};">
                ${nstatusText}
            </span>
        </div>
    `;
} else {
    row.innerHTML = `
        <div style="flex: 1; color: gray;">
            <strong style="color: #333;">${nlabel}:</strong> Not Uploaded
        </div>`;
}

displayArea.appendChild(row);
















//Maritime labour Certificate

 nfieldKey = 'maritime_labour';
 nfilePath = data[nfieldKey];

// 2.nfileName fallback logic
 nfileName = data[nfieldKey + '_name'] || "Maritime_Labour.pdf";
 nlabel = newTag[5] || "Maritime Labour"; // Fallback if tags[2] is missing
 nsubmissionId = data.id;

// 3. Status Handling ( 0=Pending, 1=Accepted, 2=Rejected)
 ncurrentStatus = parseInt(data[nfieldKey + '_status']) || 0;

row = document.createElement('div');
row.className = "document-row"; // Better to use classes for styling
row.style = "display: flex; align-items: center; justify-content: space-between; padding: 10px; border-bottom: 1px solid #eee;";

if (nfilePath && nfilePath !== "") {
    let nstatusText = "";
    let nstatusColor = "";

    if (ncurrentStatus === 1) {
        nstatusText = "Accepted";
        nstatusColor = "green";
    } else if (ncurrentStatus === 2) {
        nstatusText = "Rejected";
        nstatusColor = "red";
    } else {
        nstatusText = "Pending Review";
        nstatusColor = "orange";
    }

    // Fixed: Changed IDs from -${i} to -tax-clearance to ensure uniqueness
    row.innerHTML = `
        <div style="flex: 1;">
            <span class="fonterstr" style="font-weight:bold;">${nlabel}:</span><br>
            📄 <a href="${nfilePath}" target="_blank" style="color: blue; text-decoration: underline;">${nfileName}</a>
        </div>
        <div id="actions-tax" style="display: flex; align-items: center; gap: 10px;">
            <span id="status-text-tax" style="font-weight:200; color: ${nstatusColor};">
                ${nstatusText}
            </span>
        </div>
    `;
} else {
    row.innerHTML = `
        <div style="flex: 1; color: gray;">
            <strong style="color: #333;">${nlabel}:</strong> Not Uploaded
        </div>`;
}

displayArea.appendChild(row);










































   
   
   
   
   



   
    for (let i = 6; i <= 34; i++) {
		
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
				else {
        statusText = "Pending Review";
        statusColor = "orange";
    }

            row.innerHTML = `
                <div style="flex: 1;">
                    <span class="fonterstr" style="font-weight:bold;">${label}:</span><br>
                    📄 <a href="${filePath}" target="_blank" style="color: blue;">${fileName}</a>
                </div>
                <div id="actions-${i}" style="display: flex; align-items: center; gap: 10px;">
                    <span id="status-text-${i}" style="font-weight:200; color: ${statusColor};">${statusText}</span>
                    
                  </div>
            `;
        } else {
            row.innerHTML = `<div style="flex: 1; color: gray;"><strong>${label}:</strong> --None--</div>`;
        }
        displayArea.appendChild(row);
    }
    details.scrollTop = 0;
}


function jointwaiver(data, displayArea, spacer, details) {
  const tags = {
    // --- Section 1: Vessel & Company Identity ---
    1: "Name of Company/Agent",
    2: "Name of Vessel (MT/MV)",
    3: "Type of Vessel",
    4: "GRT",
    5: "Vessel Registration Status",

    // --- Section 2: Statutory Legal Forms ---
    6: "Cabotage Affidavit Form (CAF) (Signed/Stamped by Federal High Court)",
    7: "Bill of Sale / Evidence of Ownership / Nigeria Ship Registry Certificate",
    8: "Application to Participate in Cabotage Trade Form (Signed/Stamped by Federal High Court)",
    9: "Crew List Declaration Form (Signed/Stamped by Federal High Court)",
	10: "",
    11: "Declaration of Eligibility (Signed/Stamped by Federal High Court)",
    12: "Declaration of Ownership Form (Signed/Stamped by Federal High Court)",

    // --- Section 3: Crewing & Safety ---
    13: "Detailed Crew List on Company Letterhead (Signed/Stamped/Dated by Master)",
    14: "Copy of Minimum Safe Manning Certificate (SMC)",
    15: "Completed Maritime Labour Declaration Form",

    // --- Section 4: Corporate & Tax Compliance ---
    16: "CAC (2) & CAC (7) (Share Capital ≥ ₦25M)",
    17: "Copy of Certificate of Incorporation",
    18: "Memorandum and Articles of Association",
    19: "Tax Clearance Certificates (Last 3 Years)",

    // --- Section 5: NIMASA & Operational Agreements ---
    20: "Evidence of Registration as a Shipping Company with NIMASA",
    21: "Evidence of Payment of Sea Protection Levy (SPL) (Exempt if < 100 GRT)",
    22: "Copy of Joint Venture Agreement",
    23: "Declaration on Compliance with Cabotage Act (2003) Section 4:3:1 (b) and (c)",

    // --- Section 6: Renewal Specific Requirements ---
    24: "Evidence of Active Training of Nigerian Cadet (For Renewal)",
    25: "Evidence of Dry-docking in Nigeria or Non-availability Letter (For Renewal)",
    26: "Evidence of 2% Surcharge Payment (For Renewal)",
    27: "Copy of Last Waiver Certificate or Receipt for Waiver Processing Fees",

    // --- Section 7: Customs & Foreign Ownership ---
    28: "Historical Cargo Operations Data (Last 5 Loading/Discharging Ports)",
    29: "Copy of Survey Report OR Temporary Importation Document from Customs",
    30: "Proof of Relationship: Foreign Owner & Nigerian Management (For Foreign Owned)",
    31: "Cabotage Application for Waiver Form"
};
const newTag={
	
		1: "IMO Number",
		2: "Vessel Official Number",
		
		3: "Tax Clearance Certificate",
		4: "Manning Liscence",
		5: "Maritime Labour Certificate",
		
		
	
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
        { label: "Application Type", val: "Joint-Venture Waiver" }
    ];

    headerCards.forEach(card => {
        infoSection += `
            <div style="background-image: linear-gradient(to bottom,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185)), linear-gradient(to right,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185)); background-blend-mode: multiply; padding: 10px; border-radius: 5px; border-left: 4px solid rgb(255,50,100);">
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
   
	 //imo number
	 let nval = data['imo_number'] || "N/A";
        infoSection += `
            <div style="background-image: linear-gradient(to bottom,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185)), linear-gradient(to right,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185)); background-blend-mode: multiply; padding: 10px; border-radius: 5px; border-left: 4px solid #007bff;">
                <small style="color: white; font-weight: bold;">${newTag[1]}</small><br>
                <span style="color:rgb(220,220,220);">${nval}</span>
            </div>`;
    
	
	 //Vessel Official Number
	 nval = data['official_number'] || "N/A";
        infoSection += `
            <div style="background-image: linear-gradient(to bottom,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185)), linear-gradient(to right,rgb(27,50,185), rgb(67,90,255),rgb(27,50,185)); background-blend-mode: multiply; padding: 10px; border-radius: 5px; border-left: 4px solid #007bff;">
                <small style="color: white; font-weight: bold;">${newTag[2]}</small><br>
                <span style="color:rgb(220,220,220);">${nval}</span>
            </div>`;
    









  infoSection += `</div><hr><h3>Uploaded Documents</h3>`;
    displayArea.innerHTML = infoSection;











//tax clearance Certificate

let nfieldKey = 'tax_clearance';
let nfilePath = data[nfieldKey];

// 2.nfileName fallback logic
let nfileName = data[nfieldKey + '_name'] || "Tax_Clearance.pdf";
let nlabel = newTag[3] || "Tax Clearance"; // Fallback if tags[2] is missing
let nsubmissionId = data.id;

// 3. Status Handling ( 0=Pending, 1=Accepted, 2=Rejected)
let ncurrentStatus = parseInt(data[nfieldKey + '_status']) || 0;

let row = document.createElement('div');
row.className = "document-row"; 
row.style = "display: flex; align-items: center; justify-content: space-between; padding: 10px; border-bottom: 1px solid #eee;";

if (nfilePath && nfilePath !== "") {
    let nstatusText = "";
    let nstatusColor = "";

    if (ncurrentStatus === 1) {
        nstatusText = "Accepted";
        nstatusColor = "green";
    } else if (ncurrentStatus === 2) {
        nstatusText = "Rejected";
        nstatusColor = "red";
    } else {
        nstatusText = "Pending Review";
        nstatusColor = "orange";
    }

    // Fixed: Changed IDs from -${i} to -tax-clearance to ensure uniqueness
    row.innerHTML = `
        <div style="flex: 1;">
            <span class="fonterstr" style="font-weight:bold;">${nlabel}:</span><br>
            📄 <a href="${nfilePath}" target="_blank" style="color: blue; text-decoration: underline;">${nfileName}</a>
        </div>
        <div id="actions-tax" style="display: flex; align-items: center; gap: 10px;">
            <span id="status-text-tax" style="font-weight:200; color: ${nstatusColor};">
                ${nstatusText}
            </span>
        </div>
    `;
} else {
    row.innerHTML = `
        <div style="flex: 1; color: gray;">
            <strong style="color: #333;">${nlabel}:</strong> Not Uploaded
        </div>`;
}

displayArea.appendChild(row);









//Maning Liscence Certificate

 nfieldKey = 'manning_liscense';
nfilePath = data[nfieldKey];

// 2.nfileName fallback logic
 nfileName = data[nfieldKey + '_name'] || "Manning_Liscense.pdf";
 nlabel = newTag[4] || "Manning_Liscense"; 
 nsubmissionId = data.id;

// 3. Status Handling ( 0=Pending, 1=Accepted, 2=Rejected)
ncurrentStatus = parseInt(data[nfieldKey + '_status']) || 0;

row = document.createElement('div');
row.className = "document-row"; 
row.style = "display: flex; align-items: center; justify-content: space-between; padding: 10px; border-bottom: 1px solid #eee;";

if (nfilePath && nfilePath !== "") {
    let nstatusText = "";
    let nstatusColor = "";

    if (ncurrentStatus === 1) {
        nstatusText = "Accepted";
        nstatusColor = "green";
    } else if (ncurrentStatus === 2) {
        nstatusText = "Rejected";
        nstatusColor = "red";
    } else {
        nstatusText = "Pending Review";
        nstatusColor = "orange";
    }

    // Fixed: Changed IDs from -${i} to -tax-clearance to ensure uniqueness
    row.innerHTML = `
        <div style="flex: 1;">
            <span class="fonterstr" style="font-weight:bold;">${nlabel}:</span><br>
            📄 <a href="${nfilePath}" target="_blank" style="color: blue; text-decoration: underline;">${nfileName}</a>
        </div>
        <div id="actions-tax" style="display: flex; align-items: center; gap: 10px;">
            <span id="status-text-tax" style="font-weight:200; color: ${nstatusColor};">
                ${nstatusText}
            </span>
        </div>
    `;
} else {
    row.innerHTML = `
        <div style="flex: 1; color: gray;">
            <strong style="color: #333;">${nlabel}:</strong> Not Uploaded
        </div>`;
}

displayArea.appendChild(row);
















//Maritime labour Certificate

 nfieldKey = 'maritime_labour';
 nfilePath = data[nfieldKey];

// 2.nfileName fallback logic
 nfileName = data[nfieldKey + '_name'] || "Maritime_Labour.pdf";
 nlabel = newTag[5] || "Maritime Labour"; // Fallback if tags[2] is missing
 nsubmissionId = data.id;

// 3. Status Handling ( 0=Pending, 1=Accepted, 2=Rejected)
 ncurrentStatus = parseInt(data[nfieldKey + '_status']) || 0;

row = document.createElement('div');
row.className = "document-row"; // Better to use classes for styling
row.style = "display: flex; align-items: center; justify-content: space-between; padding: 10px; border-bottom: 1px solid #eee;";

if (nfilePath && nfilePath !== "") {
    let nstatusText = "";
    let nstatusColor = "";

    if (ncurrentStatus === 1) {
        nstatusText = "Accepted";
        nstatusColor = "green";
    } else if (ncurrentStatus === 2) {
        nstatusText = "Rejected";
        nstatusColor = "red";
    } else {
        nstatusText = "Pending Review";
        nstatusColor = "orange";
    }

    // Fixed: Changed IDs from -${i} to -tax-clearance to ensure uniqueness
    row.innerHTML = `
        <div style="flex: 1;">
            <span class="fonterstr" style="font-weight:bold;">${nlabel}:</span><br>
            📄 <a href="${nfilePath}" target="_blank" style="color: blue; text-decoration: underline;">${nfileName}</a>
        </div>
        <div id="actions-tax" style="display: flex; align-items: center; gap: 10px;">
            <span id="status-text-tax" style="font-weight:200; color: ${nstatusColor};">
                ${nstatusText}
            </span>
        </div>
    `;
} else {
    row.innerHTML = `
        <div style="flex: 1; color: gray;">
            <strong style="color: #333;">${nlabel}:</strong> Not Uploaded
        </div>`;
}

displayArea.appendChild(row);










































   
   
   
   
   



   
    for (let i = 6; i <= 34; i++) {
		
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
				else {
        statusText = "Pending Review";
        statusColor = "orange";
    }

            row.innerHTML = `
                <div style="flex: 1;">
                    <span class="fonterstr" style="font-weight:bold;">${label}:</span><br>
                    📄 <a href="${filePath}" target="_blank" style="color: blue;">${fileName}</a>
                </div>
                <div id="actions-${i}" style="display: flex; align-items: center; gap: 10px;">
                    <span id="status-text-${i}" style="font-weight:200; color: ${statusColor};">${statusText}</span>
                    
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




function applySort(sortType) {
   
    const searchQuery = document.getElementById("searchInput").value;
    
   
    const urlParams = new URLSearchParams(window.location.search);
    const page = urlParams.get('page') || '1';

   
    window.location.href = `allapplications.php?search=${encodeURIComponent(searchQuery)}&sort=${sortType}&page=${page}`;
}




function filterTable() {
    let input = document.getElementById("searchInput").value.toUpperCase();
    let table = document.getElementById("userTable");
    let tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {
        let tdName = tr[i].getElementsByTagName("td")[1];
        let tdEmail = tr[i].getElementsByTagName("td")[2];
        if (tdName || tdEmail) {
            let txtValue = (tdName.textContent || tdName.innerText) + (tdEmail.textContent || tdEmail.innerText);
            tr[i].style.display = txtValue.toUpperCase().indexOf(input) > -1 ? "" : "none";
        }
    }
}

let trueID=-1;

function handleRowClick(rowElement) {
   
    const recordId = rowElement.getAttribute('data-value');
    
  
    if (typeof listSelect === "function") {
       trueID=recordId;
	   
	   
	   const badge = rowElement.querySelector('.status-badge');
    if (badge) {
        // Use toLowerCase() to make matching easier
        const statusText = badge.textContent.trim().toLowerCase();
        console.log("Status found in row:", statusText); 

        let statusValue = "";

        // Use .includes() to be safer against slight text variations
        if (statusText.includes('processing')) statusValue = "0";
        else if (statusText.includes('verifying') || statusText.includes('pending')) statusValue = "9";
        else if (statusText.includes('completed') || statusText.includes('approved')) statusValue = "1";
        else if (statusText.includes('rejected')) statusValue = "3";

        console.log("Calculated Value:", statusValue);

      
    }
	   
	   
	 const myDiv = document.getElementById('accountDETS');

myDiv.scrollTo({
  top: 0,
  left: 0,
  behavior: 'smooth'
});
	   
	   
        listSelect2(recordId);
    }

    
}



let trueStatus=-1;
let trueType=-1;
function listSelect2( selectedId) {
    var accounts = document.getElementById('accounts');
    accounts.style.width = '43vw';

    var details = document.getElementById('accountDETS');
    details.style.display = "block";

    var list = document.getElementById("userList");
   // var selectedEmail = list.value;
	//var selectedId = list.value;

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
            
			
			if(Number(data.status)==1){ trueStatus=data.tag; document.getElementById('renewal').style="display:block;"; }
			else{
				trueStatus=-10000;
		document.getElementById('renewal').style="display:none;";
			}
           
		   trueType=result.data.type;
		   
		   
            if(result.data.type==1){
           nigerian(data, displayArea, spacer, details);
			}
			else if(result.data.type==2){
				
			foreign(data, displayArea, spacer, details);
			}
			else if(result.data.type==3){ bareboat(data, displayArea, spacer, details); }
			else if(result.data.type==4){ jointwaiver(data, displayArea, spacer, details); }
        }
    })
    .catch(error => console.error('Error:', error));
}



function deleteRecord(id, btnElement) {
    btnElement.disabled = true;
    btnElement.style.opacity = "0.5";
    btnElement.style.cursor = "not-allowed";
	
	
    fetch('../php/delete_submission_none.php?id=' + id)
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
    fetch(`../php/searchapprove.php?search=${encodeURIComponent(query)}&sort=${sort}`)
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

   
    fetch(`../php/searchapprove.php?search=${search}&sort=${sort}&page=${page}`)
    .then(response => response.text())
    .then(html => {
        
        document.querySelector("#userTable tbody").innerHTML = html;
       
    })
    .catch(err => console.warn("Failed to reload div:", err));
}




function renewal(){
	
	
	duplicateApplication(trueStatus);
	
	
	 
   
   
}



function duplicateApplication(currentTag) {
    console.log("Duplicating tag:", currentTag);

    const formData = new FormData();
    formData.append('tag', currentTag);

    fetch('../php/duplicate_tag.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            
            // Use the NEW tag returned from the server, NOT the old trueStatus
            const newTagFromServer = data.new_tag; 

            const formDataT = new FormData();
            formDataT.append('tag', newTagFromServer); 
            
            // This call ensures the session 'user_tag' is set to the NEW record
            fetch('../php/nigerianload.php', {
                method: 'POST',
                body: formDataT
            })
            .then(response => response.json()) 
            .then(result => {
                // Check status1 because your PHP uses that specific key
                if (result.status1 === "success") {
                    if(trueType==1){ window.location.href = "../others/cabotage1.php"; }
					else if(trueType==2){ window.location.href = "../waiver/foreignwaiver.php";  }
                } else {
                    alert("Error loading new record session.");
                }
            })
            .catch(error => console.error('Error in Loader:', error));

        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(error => {
        console.error('Error in Duplication:', error);
        alert("An error occurred while duplicating the record.");
    });
}



















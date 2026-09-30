
const directorPrefixes = ["director", "director1", "director2", "director3", "director4", "director5", "director6", "director7", "director8"];


function switchTab(clickedId) {
    // Maps button IDs to Section IDs
    const sections = { 
        'cpf': 'profileSection', 
        'cdd': 'addressSection', 
        'cdr': 'directorSection' 
    };
    
    Object.keys(sections).forEach(id => {
        const btn = document.getElementById(id);
        const section = document.getElementById(sections[id]);
        
        if (id === clickedId) {
            btn.style.backgroundColor = "white";
            btn.style.color = "#333";
            btn.classList.add('active');
            section.style.display = 'block';
        } else {
            btn.style.background = "none";
            btn.style.border = "none";
            btn.style.color = "#007bff";
            btn.classList.remove('active', 'active1', 'active2');
            section.style.display = 'none';
        }
    });
}


function compProf() { switchTab('cpf'); }
function compAdd() { switchTab('cdd'); }
function compDir() { switchTab('cdr'); }



function updateDirectorSidebar() {
    const listContainer = document.getElementById('list_of_dir');
    if (!listContainer || typeof userData === 'undefined') return;

    let listHtml = "";
    directorPrefixes.forEach(prefix => {
        const firstName = userData[prefix + "_name1"];
        const lastName = userData[prefix + "_name2"];

        if (firstName || lastName) {
            const fullName = `${firstName || ''} ${lastName || ''}`.trim();
            listHtml += `<div class="director-item" 
                             style="cursor:pointer; color:#007bff; margin-bottom:8px; border-bottom:1px solid #eee; padding-bottom:4px;" 
                             onclick="loadDirectorData('${prefix}')">
                             ${fullName}
                         </div>`;
        }
    });

    listContainer.innerHTML = listHtml || "No directors found.";
}


document.addEventListener("DOMContentLoaded", function() {
    if (typeof userData === 'undefined') return;
	
	
	
	
	updateDirectorSidebar();
	
	
	
	
	

    // --- A. PROFILE SECTION ---
    document.getElementById('company_name').value = userData.company_name || "";
    document.getElementById('business_type').value = userData.business_type || "";
    document.getElementById('country').value = userData.nationality || "";
    // ... rest of profile fields ...

    // --- B. ADDRESS SECTION ---
    document.getElementById('address1').value = userData.company_address || "";
    document.getElementById('address2').value = userData.company_address2 || "";
    document.getElementById('city').value = userData.city || "";
    document.getElementById('postal').value = userData.postal_code || "";
    
    // Set country value first
    const addrCountry = document.getElementById('address_country');
    addrCountry.value = userData.country || "";

    // --- C. INITIALIZE COUNTRIES & STATES ---
    // We pass a callback to populateCountries so it knows to update states AFTER loading the list
    populateCountries('country', userData.nationality);
    
    populateCountries('address_country', userData.country, () => {
        // Force the state to populate once the country list exists
        handleCountryChange('address_country');
        document.getElementById('state').value = userData.state || "";
    });

    populateCountries('dir_country', userData.director_country);
	
	
	 populateCountries('dir_nation', userData.director_country);
    
    // Initial Director Load
    loadDirectorData("director");
});

// --- 3. DIRECTOR MANAGEMENT ---
function loadDirectorData(prefix) {
    const section = document.getElementById('directorSection');
    if (!section) return;
    
    section.setAttribute('data-current-prefix', prefix);
	
	const displayNum = prefix === 'director' ? '1' : prefix.replace('director', '');
    console.log(`Loading Director ${displayNum}`);
    
    document.getElementById('dir_name1').value = userData[prefix + "_name1"] || "";
    document.getElementById('dir_name2').value = userData[prefix + "_name2"] || "";
    document.getElementById('dir_phone').value = userData[prefix + "_no"] || "";
    document.getElementById('dir_nation').value = userData[prefix + "_nationality"] || "NIGERIA";
    document.getElementById('dir_address1').value = userData[prefix + "_address"] || "";
    document.getElementById('dir_address2').value = userData[prefix + "_address2"] || "";
    document.getElementById('dir_city').value = userData[prefix + "_city"] || "";
    document.getElementById('dir_postal').value = userData[prefix + "_postal"] || "";
    document.getElementById('dir_country').value = userData[prefix + "_country"] || "";
    
    // Refresh states for this specific director
    handleCountryChange('dir_country');
    document.getElementById('dir_state').value = userData[prefix + "_state"] || "Lagos";
}


// --- 4. COUNTRY & STATE LOGIC ---
const nigerianStates = [
    "Abia", "Adamawa", "Akwa Ibom", "Anambra", "Bauchi", "Bayelsa", "Benue", "Borno", "Cross River", 
    "Delta", "Ebonyi", "Edo", "Ekiti", "Enugu", "FCT - Abuja", "Gombe", "Imo", "Jigawa", "Kaduna", 
    "Kano", "Katsina", "Kebbi", "Kogi", "Kwara", "Lagos", "Nasarawa", "Niger", "Ogun", "Ondo", 
    "Osun", "Oyo", "Plateau", "Rivers", "Sokoto", "Taraba", "Yobe", "Zamfara"
];

async function populateCountries(selectId, currentValue, callback) {
    const selectElement = document.getElementById(selectId);
    if (!selectElement) return;

    try {
        const response = await fetch('https://restcountries.com/v3.1/all?fields=name');
        const countries = await response.json();
        const sortedCountries = countries.map(c => c.name.common).sort();

        selectElement.innerHTML = '<option value="">Select Country</option>';
        sortedCountries.forEach(country => {
            const option = new Option(country, country);
            if (currentValue && country.toLowerCase() === currentValue.toLowerCase()) option.selected = true;
            selectElement.appendChild(option);
        });

        // Run the specific state logic for this dropdown
        handleCountryChange(selectId);
        
        // If we provided a specific follow-up action (like setting the state), do it now
        if (callback) callback();
        
    } catch (e) { 
        console.error("Country load failed", e); 
    }
}
function handleCountryChange(countrySelectId) {
    const countrySelect = document.getElementById(countrySelectId);
    let stateSelectId = '';
    
    if (countrySelectId === 'address_country') stateSelectId = 'state';
    if (countrySelectId === 'dir_country') stateSelectId = 'dir_state';
    if (countrySelectId === 'new_dir_country') stateSelectId = 'new_dir_state'; 

    const stateSelect = document.getElementById(stateSelectId);
    if (!stateSelect) return;

    if (countrySelect.value === "Nigeria") {
        stateSelect.innerHTML = nigerianStates.map(s => `<option value="${s}">${s}</option>`).join('');
    } else {
        stateSelect.innerHTML = '<option value="">Other</option>';
    }
}

// Listeners for changes
document.addEventListener("change", (e) => {
    if (['address_country', 'dir_country', 'new_dir_country'].includes(e.target.id)) {
        handleCountryChange(e.target.id);
    }
});


// --- 5. DATABASE UPDATES ---
async function sendData(action, payload,typ) {
    payload.action = action;
    try {
        const response = await fetch('../php/update_profile.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        
        const result = await response.json();
        if(result.status === "success") {
            alert("Changes saved successfully!");
            if(typ==99){ location.reload(); }
			
        } else {
            alert("Error: " + result.message);
        }
    } catch (error) {
        console.error('Fetch Error:', error);
        alert("Failed to connect to the server.");
    }
}

// Form Submission Handlers
document.addEventListener("DOMContentLoaded", function() {
    // A. Profile Section
    const profileBtn = document.querySelector('#profileSection .submit-btn');
    if (profileBtn) {
        profileBtn.onclick = (e) => {
            e.preventDefault();
            sendData('update_profile', {
                company_name: document.getElementById('company_name').value,
                business_type: document.getElementById('business_type').value,
                nationality: document.getElementById('country').value,
                first_name: document.getElementById('first_name').value,
                last_name: document.getElementById('last_name').value,
                phone: document.getElementById('phone').value,
                year: document.getElementById('year').value,
                rc: document.getElementById('rc').value,
                tin: document.getElementById('tin').value
            });
        };
    }

    // B. Address Section
    const addressBtn = document.querySelector('#addressSection .submit-btn');
    if (addressBtn) {
        addressBtn.onclick = (e) => {
            e.preventDefault();
            sendData('update_address', {
                address1: document.getElementById('address1').value,
                address2: document.getElementById('address2').value,
                city: document.getElementById('city').value,
                postal: document.getElementById('postal').value,
                country: document.getElementById('address_country').value,
                state: document.getElementById('state').value
            });
        };
    }

    // C. Director Section
    const directorBtn = document.querySelector('#directorSection .submit-btn');
    if (directorBtn) {
        directorBtn.onclick = (e) => {
            e.preventDefault();
            const prefix = document.getElementById('directorSection').getAttribute('data-current-prefix') || 'director';
            sendData('update_director', {
                prefix: prefix,
                name1: document.getElementById('dir_name1').value,
                name2: document.getElementById('dir_name2').value,
                phone: document.getElementById('dir_phone').value,
                nation: document.getElementById('dir_nation').value,
                addr1: document.getElementById('dir_address1').value, 
                addr2: document.getElementById('dir_address2').value,
                city: document.getElementById('dir_city').value,
                postal: document.getElementById('dir_postal').value,
                country: document.getElementById('dir_country').value,
                state: document.getElementById('dir_state').value
            },99);
        };
    }
});




function AddDir() {
   
    if (typeof userData === 'undefined' || userData === null) {
        console.error("userData is not defined. Ensure your PHP is echoing this object.");
        alert("Error: Profile data not loaded.");
        return;
    }

    let nextPrefix = directorPrefixes.find(p => {
        let val = userData[p + "_name1"];
        return !val || val.toString().trim() === "";
    });

    if (!nextPrefix) {
        alert("Maximum limit of 9 directors reached. You cannot add more.");
        
        const addBtn = document.querySelector('button[onclick="AddDir()"]');
        if (addBtn) {
            addBtn.disabled = true;
            addBtn.style.opacity = "0.5";
        }
        return;
    }

    console.log("Next available slot found:", nextPrefix);
    
    const modalInputs = document.querySelectorAll('#addDirModal input');
    modalInputs.forEach(input => input.value = "");

    const modal = document.getElementById('addDirModal');
    modal.setAttribute('data-next-prefix', nextPrefix);

    modal.style.display = 'block';

    if (typeof populateCountries === 'function') {
        populateCountries('new_dir_country', 'Nigeria');
        populateCountries('new_dir_nation', 'NIGERIA');
        
      
        document.getElementById('new_dir_country').onchange = () => handleCountryChange('new_dir_country');
    }
}




async function saveNewDirector() {
    console.log("Save process started...");

    const modal = document.getElementById('addDirModal');
    const prefix = modal.getAttribute('data-next-prefix');

    if (!prefix) {
        console.error("Prefix is missing from modal attribute!");
        alert("Error: No target slot identified.");
        return;
    }

    // 1. Check if all required elements exist before trying to read .value
    const requiredIds = ['new_dir_name1', 'new_dir_name2', 'new_dir_phone', 'new_dir_nation', 'new_dir_address1', 'new_dir_city', 'new_dir_country', 'new_dir_state'];
    
    for (let id of requiredIds) {
        if (!document.getElementById(id)) {
            console.error(`ID not found in HTML: ${id}`);
            alert(`Development Error: Element ${id} is missing from the modal.`);
            return;
        }
    }

    const data = {
        prefix: prefix,
        name1: document.getElementById('new_dir_name1').value.trim(),
        name2: document.getElementById('new_dir_name2').value.trim(),
        phone: document.getElementById('new_dir_phone').value.trim(),
        nation: document.getElementById('new_dir_nation').value,
        addr1: document.getElementById('new_dir_address1').value.trim(),
        addr2: document.getElementById('new_dir_address2').value.trim(),
        city: document.getElementById('new_dir_city').value.trim(),
        postal: document.getElementById('new_dir_postal').value.trim(),
        country: document.getElementById('new_dir_country').value,
        state: document.getElementById('new_dir_state').value
    };

    if (!data.name1 || !data.phone) {
        alert("Please fill in the required fields.");
        return;
    }

    console.log("Sending data to PHP:", data);

    try {
        // NOTE: Double-check that this path is correct relative to your profile.php
        const response = await fetch('../php/update_profile.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'update_director',
                ...data
            })
        });

        // Log the raw response status to see if it's 404 or 500
        console.log("Server Response Status:", response.status);

        const result = await response.json();
        console.log("Server JSON Result:", result);

        if (result.status === "success") {
            // Update local object
            userData[prefix + "_name1"] = data.name1;
            userData[prefix + "_name2"] = data.name2;
            userData[prefix + "_no"] = data.phone;
            
            alert("Saved successfully!");
            closeAddDir();
            
             location.reload(); 

        } else {
            alert("Error: " + result.message);
        }
    } catch (error) {
        console.error("CRITICAL ERROR:", error);
        alert("Failed to save: Check the console (F12) for error details.");
    }
}




function closeAddDir() {
    const modal = document.getElementById('addDirModal');
    if (modal) {
        modal.style.display = 'none';
        
       
        const inputs = modal.querySelectorAll('input');
        inputs.forEach(input => input.value = '');
    }
}




async function deleteCurrentDirector() {
    
    const section = document.getElementById('directorSection');
    const prefix = section.getAttribute('data-current-prefix');

    if (!prefix) {
        alert("Please select a director to delete first.");
        return;
    }

   
    const firstName = userData[prefix + "_name1"] || "this director";
    if (!confirm(`Are you sure you want to remove all details for ${firstName}?`)) {
        return;
    }

   
    const payload = {
        action: 'update_director',
        prefix: prefix,
        name1: "", name2: "", phone: "", nation: "",
        addr1: "", addr2: "", city: "", postal: "",
        country: "", state: ""
    };

    try {
        const response = await fetch('../php/update_profile.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        const result = await response.json();

        if (result.status === "success") {
         //   alert("Director removed successfully.");
            
          
            Object.keys(payload).forEach(key => {
                if (key !== 'action' && key !== 'prefix') {
                    userData[prefix + "_" + (key === 'addr1' ? 'address' : key === 'addr2' ? 'address2' : key === 'phone' ? 'no' : key === 'nation' ? 'nationality' : key)] = "";
                }
            });

           
                location.reload();
            
        } else {
            alert("Error: " + result.message);
        }
    } catch (error) {
        console.error("Delete Error:", error);
        alert("Failed to delete director.");
    }
}









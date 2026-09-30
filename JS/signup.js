let currentDirectorCount = 1; // Start at 1 because Director 1 is already visible
const maxDirectors = 8;

// Handle Dynamic Directors
function addDirector() {
    // Since currentDirectorCount starts at 1 (for Director 1), 
    // the first hidden section is 'director_section_1'
    if (currentDirectorCount < maxDirectors) {
        let nextSection = document.getElementById('director_section_' + currentDirectorCount);
        
        if (nextSection) {
            nextSection.style.display = 'block';
            nextSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
            currentDirectorCount++;
        }

        // Disable button if we reached the max (Director 8)
        if (currentDirectorCount === maxDirectors) {
            const btn = document.getElementById('addDirectorBtn');
            btn.disabled = true;
            btn.innerText = "Maximum Directors Reached";
            btn.style.opacity = "0.5";
            btn.style.cursor = "not-allowed";
        }
    }
}

function isEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

function checkStrength() {
    const password = document.getElementById('password').value;
    const indicator = document.getElementById('password-strength');
    if (!indicator) return;

    let strength = "";
    let className = "";

    if (password.length === 0) {
        strength = "";
    } else if (password.length < 6) {
        strength = "Too Short";
        className = "strength-weak";
    } else if (password.match(/[a-z]/) && password.match(/[A-Z]/) && password.match(/[0-9]/) && password.match(/[^A-Za-z0-9]/)) {
        strength = "Strong Password";
        className = "strength-strong";
    } else {
        strength = "Medium (Add symbols & caps)";
        className = "strength-medium";
    }

    indicator.innerText = strength;
    indicator.className = className;
}

function createaccount() {
    const emailEl = document.getElementById('email');
    const usernameEl = document.getElementById('username');
    const passEl = document.getElementById('password');
    const confirmPassEl = document.getElementById('confirm_password');
    const coNameEl = document.getElementById('company_name');

    // Basic Validation
    if (!isEmail(emailEl.value)) {
        alert("Please enter a valid email address.");
        return;
    }
    if (!usernameEl.value || !coNameEl.value) {
        alert("Username and Company Name are required.");
        return;
    }
    if (passEl.value !== confirmPassEl.value) {
        alert("Passwords do not match!");
        return;
    }
    if (passEl.value.length < 8) {
        alert("Password must be at least 8 characters long.");
        return;
    }

    account_create_submit();
}

function account_create_submit() {
    const formData = new FormData();

    // 1. Core Fields
    const fields = [
        'username', 'email', 'password', 'company_name', 'business_type', 
        'nationality', 'rc_number', 'tin', 'year_incorporated',
        'contact_firstname', 'contact_lastname', 'contact_phone', 
        'company_address', 'company_address2', 'city', 'state', 
        'postal_code', 'country', 'director_name1', 'director_name2', 
        'director_no', 'director_nationality', 'director_address'
    ];

    fields.forEach(field => {
        const el = document.getElementById(field);
        if (el) {
            formData.append(field, el.value);
        }
    });

    // 2. Extra Directors (director1_ to director7_)
    for (let i = 1; i <= 7; i++) {
        const d_fields = ['name1', 'name2', 'no', 'nationality', 'address'];
        d_fields.forEach(suffix => {
            const id = `director${i}_${suffix}`;
            const el = document.getElementById(id);
            // If the element exists, use its value; otherwise, send empty string
            formData.append(id, el ? el.value : '');
        });
    }

    fetch('../php/signup.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(result => {
        if (result.status === "success") {
            alert(result.message);
            window.location.href = "../mailer/verification.php";
        } else {
            alert("Error: " + result.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Submission failed. Check the console for details.");
    });
}
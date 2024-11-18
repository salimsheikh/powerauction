// Event listener for closing the modal with the Escape key
document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' || event.keyCode === 27) { // Check for 'Escape' key
        if (popupTargetModel) { // Only hide if modal is active
            hideModal();
        }
    }
});

// Function to hide the modal
function hideModal() {

    if (!popupTargetModel) {
        return false;
    }

    // Remove the 'show' class and add the 'hide' class for animation
    const modalContent = popupTargetModel.querySelector(".modal-content");
    modalContent.classList.remove('show');
    modalContent.classList.add('hide');

    // After the animation ends, hide the modal
    setTimeout(() => {
        modalContent.classList.add('hidden');
        popupTargetModel.style.display = "none";
        //popupTargetModel = null;
    }, 600); // Match the animation duration
}

function showPopupForm() {

    popupTargetModel.style.display = "block";
    const modalContent = popupTargetModel.querySelector(".modal-content");

    modalContent.classList.remove('hidden', 'hide');
    modalContent.classList.add('show');

    // Find the .alert element within the current form
    const alertElement = popupTargetModel.querySelector(".alert");
    alertElement.classList.add("alert-hidden");

    const body_content = popupTargetModel.querySelector('.body-content');
    if(body_content) body_content.style.display = "block";    
}

function fatchResponseCatch(error, alertElement) {

    console.log("response 3");
    if (error.errors) {
        let errorMessage = '';
        for (const key in error.errors) {
            errorMessage += `${error.errors[key].join(', ')}\n`;
        }
        alertElement.classList.add("alert-danger");
        alertElement.textContent = errorMessage;
    } else {
        console.error('Error:', error);
        alertElement.classList.add("alert-danger");

        // Custom handling for unexpected response
        if (error.search('Unexpected token')) {
            alertElement.textContent = 'The server returned an invalid response. Please try again later.';
        } else {
            alertElement.textContent = 'An error occurred: ' + error;
        }
    }
}

function validateForm(currentForm, alertElement) {

    // Get all input and textarea elements
    const fields = currentForm.querySelectorAll("input, textarea");

    // Remove the invalid class and trim inputs
    fields.forEach((field) => {
        field.classList.remove("invalid");
        if (field.type === "text" || field.tagName === "TEXTAREA" || field.type === "email") {
            field.value = field.value.trim();
        }
    });

    let firstInvalidField = null;

    let validData = true;
    let requiredData = true;

    // Validate fields
    fields.forEach((field) => {
        if (field.classList.contains("required") && field.value === "") {
            field.classList.add("invalid");
            if (!firstInvalidField) firstInvalidField = field;
            requiredData = true;
        }

        if (field.classList.contains("email_validate") && field.value !== "") {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(field.value)) {
                field.classList.add("invalid");
                if (!firstInvalidField) firstInvalidField = field;
                validData = false;
            }
        }

        if (field.classList.contains("price_validate") && field.value !== "") {
            // Validate if it's a valid number with one decimal place
            const regex = /^[0-9]+(\.[0-9]{1,2})?$/;
            if (!regex.test(field.value)) {
                field.classList.add("invalid");
                if (!firstInvalidField) firstInvalidField = field;
                validData = false;
            }
        }
    });

    // Focus on the first invalid field
    if (firstInvalidField) {
        firstInvalidField.focus();
        alertElement.textContent = lang.enter_required_fields;
        alertElement.classList.add("alert-danger");
        return false;
    }

    const sanctum_token = get_local_storage_token('sanctum_token');
    if (!sanctum_token) {
        alertElement.textContent = lang.something_wrong;
        alertElement.classList.add("alert-danger");
        return false;
    }

    return true;
}

function cleanForm(fields) {
    // Remove the invalid class and trim inputs
    fields.forEach((field) => {
        if (field.type === "text" || field.tagName === "TEXTAREA" || field.type === "email") {
            field.value = '';
        }

        if (field.type === "color") {
            field.value = '#000001';
        }
    });
}


function get_csrf_token() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
}

function get_ajax_header() {

    const csrf_token = get_csrf_token();

    const sanctum_token = get_local_storage_token('sanctum_token');

    return {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${sanctum_token}`,
        'X-CSRF-TOKEN': csrf_token,
    }
}

function get_local_storage_token() {
    return localStorage.getItem('sanctum_token');
}

// Define get_token as an async function
const get_token = async function () {
    const token = get_local_storage_token('sanctum_token');

    const csrf_token = get_csrf_token();

    if (!token) {
        // Make the login request
        try {
            const response = await fetch('/create_token', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf_token,
                },
                body: JSON.stringify({ create_token: 'create_token' }),
            });

            const data = await response.json();
            if (data.token) {
                // Store the token in localStorage
                localStorage.setItem('sanctum_token', data.token);
                console.log('Token saved to localStorage');
                return data.token;
            } else {
                console.error('No token received');
                return false;
            }
        } catch (error) {
            console.error('Invalid login: ', error);
            return false;
        }
    } else {
        return token; // If the token is already in localStorage
    }
}

// Calling the function and awaiting the result in an async context
async function initialize() {
    const token = await get_token();
}

initialize(); // Call the async function
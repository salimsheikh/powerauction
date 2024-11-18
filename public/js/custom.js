var formProcessing = false;

var popupTargetModel = null;

var popupCustomModel = document.getElementsByClassName("custom-modal")[0];

// Get the button that opens the modal
var buttonPopupShowAddItemModel = document.getElementById("buttonPopupShowAddItemModel");

// Get the modal
var popupAddItemModal = document.getElementById("popupAddItemModal");

if (buttonPopupShowAddItemModel) {
    // When the user clicks the button, open the modal 
    buttonPopupShowAddItemModel.onclick = function () {

        popupTargetModel = popupAddItemModal;

        popupTargetModel.style.display = "block";
        const modalContent = popupTargetModel.querySelector(".modal-content");

        modalContent.classList.remove('hidden', 'hide');
        modalContent.classList.add('show');

        // Find the .alert element within the current form
        const alertElement = popupTargetModel.querySelector(".alert");
        alertElement.classList.add("alert-hidden");
    }
}

// Get all elements with the class 'popupCloseModel'
var popupCloseModels = document.querySelectorAll(".popupCloseModel");
if (popupCloseModels) {
    // Loop through each close button
    popupCloseModels.forEach(closeButton => {
        closeButton.addEventListener("click", function () {
            // Get the modal that this close button is associated with
            var popupCustomModel = closeButton.closest(".custom-modal");

            popupTargetModel = popupCustomModel;

            hideModal();
        });
    });
}

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
        popupTargetModel = null;
    }, 600); // Match the animation duration
}

// When the user clicks anywhere outside of the modal, close it
/*
window.onclick = function (event) {    
    if (event.target == popupTargetModel) {
        popupTargetModel.style.display = "none";
    }
}
*/

// Event listener for closing the modal with the Escape key
document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' || event.keyCode === 27) { // Check for 'Escape' key
        if (popupTargetModel) { // Only hide if modal is active
            hideModal();
        }
    }
});

const popupAddForm = document.getElementById("popupAddForm");
if (popupAddForm) {
    document.getElementById("popupAddForm").addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent form submission

        if (formProcessing) {
            return false;
        }

        // Get the current form
        const currentForm = event.target;

        // Find the .alert element within the current form
        const alertElement = currentForm.querySelector(".alert");

        // Get all input and textarea elements
        const fields = this.querySelectorAll("input, textarea");

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

        alertElement.classList.remove("alert-danger", "alert-info", "alert-success","alert-hidden");

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

        const headers = get_ajax_header();

        alertElement.textContent = lang.please_wait;
        alertElement.classList.add("alert-info");

        var formValues = {};
        fields.forEach((field) => {
            formValues[field.name] = field.value;
        });

        formProcessing = true;

        fetch(`${BASE_API_URL}/store`, {
            method: 'POST',
            headers: headers,
            body: JSON.stringify(formValues),
        }).then((response) => {
            console.log("response 1");
            //do not delete
            alertElement.classList.remove("alert-danger", "alert-info", "alert-success");
            formProcessing = false;

            if (!response.ok) {
                return response.json().then((error) => {
                    throw error;
                });
            }

            return response.json();
        }).then((data) => {
            console.log("response 2");
            alertElement.textContent = data.message;
            alertElement.classList.add("alert-success");

            // Remove the invalid class and trim inputs
            fields.forEach((field) => {
                if (field.type === "text" || field.tagName === "TEXTAREA" || field.type === "email") {
                    field.value = '';
                }

                if (field.type === "color") {
                    field.value = '#000001';
                }
            });

            setTimeout(function(){
                hideModal();
            },1500);

        }).catch((error) => {
            fatchResponseCatch(error, alertElement);
        });

        return false;
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

const tableContainer = document.getElementById('tableContainer');
const popupDeleteItemModal = document.getElementById('popupDeleteItemModal');
let selectedButton = null;
let delete_id = 0;

// Add event listener to dynamically created buttons
if (tableContainer) {
    tableContainer.addEventListener('click', function (e) {
        e.preventDefault();

        if (e.target && e.target.classList.contains('delete-button')) {
            selectedButton = e.target; // Store the clicked button

            delete_id = selectedButton.getAttribute('data-id');

            popupDeleteItemModal.style.display = "block";
            const modalContent = popupDeleteItemModal.querySelector(".modal-content");

            modalContent.classList.remove('hidden', 'hide');
            modalContent.classList.add('show');

            popupTargetModel = popupDeleteItemModal;

            // Find the .alert element within the current form
            const alertElement = popupTargetModel.querySelector(".alert");
            alertElement.classList.add('alert-hidden')

            const body_content = popupTargetModel.querySelector('.body-content');
            body_content.style.display = "block";

            const deleteButton = document.getElementById('btnPopupDelete');

            deleteButton.disabled = false;
        }
    });
}

const popupDeleteForm = document.getElementById("popupDeleteForm");
if (popupDeleteForm) {

    popupDeleteForm.addEventListener("submit", function (event) {

        event.preventDefault(); // Prevent form submission

        if (formProcessing) {
            return false;
        }

        // Get the current form
        const currentForm = event.target;

        // Find the .alert element within the current form
        const alertElement = currentForm.querySelector(".alert");

        const body_content = currentForm.querySelector('.body-content');

        const deleteButton = document.getElementById('btnPopupDelete');
        alertElement.classList.remove("alert-danger", "alert-info", "alert-success","alert-hidden");

        const sanctum_token = get_local_storage_token('sanctum_token');
        if (!sanctum_token) {
            alertElement.textContent = lang.something_wrong;
            alertElement.classList.add("alert-danger");
            return false;
        }

        const headers = get_ajax_header();

        alertElement.textContent = lang.please_wait;
        alertElement.classList.add("alert-info");        
        deleteButton.disabled = true;
        body_content.style.display = "none";

        formProcessing = true;
        
        fetch(`${BASE_API_URL}/${delete_id}`, {
            method: 'delete',
            headers: headers,
            body: JSON.stringify([]),
        }).then((response) => {
            console.log("response 1");
            //do not delete
            alertElement.classList.remove("alert-danger", "alert-info", "alert-success");
            formProcessing = false;

            if (!response.ok) {
                return response.json().then((error) => {
                    throw error;
                });
            }

            return response.json();
        }).then((data) => {
            console.log("response 2", data);
            alertElement.textContent = data.message;
            alertElement.classList.add("alert-success");
            setTimeout(function () {
                hideModal();
                deleteButton.disabled = false;
            }, 1500);
        }).catch((error) => {
            deleteButton.disabled = false;
            fatchResponseCatch(error, alertElement);
        });

    });
}

var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

// Change the icons inside the button based on previous settings
if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia(
    '(prefers-color-scheme: dark)').matches)) {
    themeToggleLightIcon.classList.remove('hidden');
} else {
    themeToggleDarkIcon.classList.remove('hidden');
}

var themeToggleBtn = document.getElementById('theme-toggle');

if (themeToggleBtn) {
    themeToggleBtn.addEventListener('click', function () {

        // toggle icons inside button
        themeToggleDarkIcon.classList.toggle('hidden');
        themeToggleLightIcon.classList.toggle('hidden');

        // if set via local storage previously
        if (localStorage.getItem('color-theme')) {
            if (localStorage.getItem('color-theme') === 'light') {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            }

            // if NOT set via local storage previously
        } else {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
        }

    });
}

var popupUpdateItemModal = document.getElementById("popupUpdateItemModal");
let edit_id = 0;

// Add event listener to dynamically created buttons
if (tableContainer) {
    tableContainer.addEventListener('click', function (e) {
        e.preventDefault();

        if (e.target && e.target.classList.contains('edit-button')) {
            selectedButton = e.target; // Store the clicked button

            if (formProcessing) {
                return false;
            }

            edit_id = e.target.getAttribute('data-id');

            popupTargetModel = popupUpdateItemModal;

            popupTargetModel.style.display = "block";
            const modalContent = popupTargetModel.querySelector(".modal-content");

            modalContent.classList.remove('hidden', 'hide');
            modalContent.classList.add('show');

            // Find the .alert element within the current form
            const alertElement = popupTargetModel.querySelector(".alert");

            const body_content = popupTargetModel.querySelector('.body-content');

            alertElement.classList.remove("alert-danger", "alert-info", "alert-success", "alert-hidden");         

            const sanctum_token = get_local_storage_token('sanctum_token');
            if (!sanctum_token) {
                alertElement.textContent = lang.something_wrong;
                alertElement.classList.add("alert-danger");
                return false;
            }

            const headers = get_ajax_header();

            alertElement.textContent = lang.please_wait;
            alertElement.classList.add("alert-info");

            formProcessing = true;
            console.log(edit_id);

            console.log(`${BASE_API_URL}/edit/${edit_id}`)

            fetch(`${BASE_API_URL}/edit/${edit_id}`, {
                method: 'get',
                headers: headers,
                // body: JSON.stringify([]),
            }).then((response) => {
                console.log("response 1");
                //do not delete
                alertElement.classList.remove("alert-danger", "alert-info", "alert-success");
                formProcessing = false;

                if (!response.ok) {
                    return response.json().then((error) => {
                        throw error;
                    });
                }

                return response.json();
            }).then((data) => {
                console.log("response 2", data);
                alertElement.textContent = data.message;
                alertElement.classList.add("alert-success");
                setTimeout(function () {
                    alertElement.classList.add('alert-hidden');
                }, 500);

                let formData = data.data;

                for (const key in formData) {
                    if (formData.hasOwnProperty(key)) {
                        if (formData[key] != "") {
                            document.getElementById('update_' + key).value = formData[key];
                        }
                    }
                }

            }).catch((error) => {
                fatchResponseCatch(error, alertElement);
            });

        }
    });
}

const popupUpdateForm = document.getElementById('popupUpdateForm');
if(popupUpdateForm){
    popupUpdateForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent form submission

        if (formProcessing) {
            return false;
        }

        // Get the current form
        const currentForm = event.target;

        // Find the .alert element within the current form
        const alertElement = currentForm.querySelector(".alert");

        // Get all input and textarea elements
        const fields = this.querySelectorAll("input, textarea");

        // Remove the invalid class and trim inputs
        fields.forEach((field) => {
            field.classList.remove("invalid");
            if (field.type === "text" || field.tagName === "TEXTAREA" || field.type === "email") {
                field.value = field.value.trim();
            }
        });

        let firstInvalidField = null;

        // Validate fields
        fields.forEach((field) => {
            if (field.classList.contains("required") && field.value === "") {
                field.classList.add("invalid");
                if (!firstInvalidField) firstInvalidField = field;
            }

            if (field.classList.contains("email_validate") && field.value !== "") {
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(field.value)) {
                    field.classList.add("invalid");
                    if (!firstInvalidField) firstInvalidField = field;
                }
            }

            if (field.classList.contains("price_validate") && field.value !== "") {
                // Validate if it's a valid number with one decimal place
                const regex = /^[0-9]+(\.[0-9]{1,2})?$/;
                if (!regex.test(field.value)) {
                    field.classList.add("invalid");
                    if (!firstInvalidField) firstInvalidField = field;
                }
            }
        });

        alertElement.classList.remove("alert-danger", "alert-info", "alert-success", "alert-hidden");
        
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

        const headers = get_ajax_header();

        alertElement.textContent = lang.please_wait;
        alertElement.classList.add("alert-info");

        var formValues = {};
        fields.forEach((field) => {
            formValues[field.name] = field.value;
        });

        formProcessing = true;

        fetch(`${BASE_API_URL}/${edit_id}`, {
            method: 'PUT',
            headers: headers,
            body: JSON.stringify(formValues),
        }).then((response) => {
            console.log("response 1");
            //do not delete
            alertElement.classList.remove("alert-danger", "alert-info", "alert-success");
            formProcessing = false;

            if (!response.ok) {
                return response.json().then((error) => {
                    throw error;
                });
            }

            return response.json();
        }).then((data) => {
            console.log("response 2");
            alertElement.textContent = data.message;
            alertElement.classList.add("alert-success");

            setTimeout(function () {
                hideModal();
            }, 1500);

        }).catch((error) => {
            fatchResponseCatch(error, alertElement);
        });

        return false;
    });
}


function fatchResponseCatch(error, alertElement){
    
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
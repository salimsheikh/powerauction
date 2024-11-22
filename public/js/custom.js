"use strick";

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

        showPopupForm();

        const focus_first = popupTargetModel.querySelector(".focus_first");

        focus_first.focus();
        
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

const popupAddForm = document.getElementById("popupAddForm");
if (popupAddForm) {
    document.getElementById("popupAddForm").addEventListener("submit", async function (event) {
        event.preventDefault(); // Prevent form submission

        if (formProcessing) {
            return false;
        }        

        // Get the current form
        const currentForm = event.target;

        // Find the .alert element within the current form
        const alertElement = currentForm.querySelector(".alert");

        // Get all input and textarea elements
        const fields = currentForm.querySelectorAll("input, textarea, select");
       
        alertElement.classList.remove("alert-danger", "alert-info", "alert-success", "alert-hidden");

        const validForm = validateForm(currentForm, alertElement);
        if (!validForm) {
            return false;
        }

        const headers = get_ajax_header();

        alertElement.textContent = lang.please_wait;
        alertElement.classList.add("alert-info");

        let formData = [];
        const imageInput = document.getElementById('image');
        if(imageInput){

            if (imageInput.files.length === 0) {
                console.error("No file selected.");
                return;
            }

            formData  = new FormData();
            formData.append('image', imageInput.files[0]); // Add the selected file to FormData

            console.log(formData);  

           
    
        }else{
            fields.forEach((field) => {
                formData[field.name] = field.value;
            });
    
        }       
          
        

        formProcessing = true;

        await fetch(`${BASE_API_URL}/store`, {
            method: 'POST',
            headers: headers,
            body: formData,
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

            cleanForm(fields);

            fetchAndRender(current_page);

            setTimeout(function () {
                hideModal();
            }, 1500);

        }).catch((error) => {
            fatchResponseCatch(error, alertElement);
        });

        return false;
    });
}

const tableContainer = document.getElementById('tableContainer');
var popupUpdateItemModal = document.getElementById("popupUpdateItemModal");
let selectedButton = null;
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

            // Get all input and textarea elements
            const fields = popupTargetModel.querySelectorAll("input, textarea");

            cleanForm(fields);

            const focus_first = popupTargetModel.querySelector(".focus_first");

            focus_first.focus();

            // Find the .alert element within the current form
            const alertElement = popupTargetModel.querySelector(".alert");

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

            const buttonText = selectedButton.querySelector(".buttonText");
            const loadingSpinner = selectedButton.querySelector(".loadingSpinner");

            buttonText.classList.add('hidden'); // Hide the text
            loadingSpinner.classList.remove('hidden'); // Show the spinner
           
            formProcessing = true;

            fetch(`${BASE_API_URL}/edit/${edit_id}`, {
                method: 'get',
                headers: headers
            }).then((response) => {
                console.log("response 1");
                //do not delete
                alertElement.classList.remove("alert-danger", "alert-info", "alert-success");
                formProcessing = false;

                buttonText.classList.remove('hidden'); // Hide the text
                loadingSpinner.classList.add('hidden'); // Show the spinner

                if (!response.ok) {
                    return response.json().then((error) => {
                        throw error;
                    });
                }

                return response.json();
            }).then((data) => {

                alertElement.textContent = data.message;
                alertElement.classList.add("alert-success");               

                focus_first.focus();

                setTimeout(function () {
                    alertElement.classList.remove("alert-success");
                    alertElement.classList.add('alert-hidden');
                }, 500);

                let formData = data.data;

                for (const key in formData) {
                    if (formData.hasOwnProperty(key)) {
                        if (formData[key] != "" && formData[key] != null) {
                            document.getElementById('update_' + key).value = formData[key];
                        }
                    }
                }

                showPopupForm();

            }).catch((error) => {
                fatchResponseCatch(error, alertElement);
            });

        }
    });
}

const popupUpdateForm = document.getElementById('popupUpdateForm');
if (popupUpdateForm) {
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

            console.log(current_page)

            fetchAndRender(current_page);
            
            setTimeout(function () {
                hideModal();
            }, 1500);

        }).catch((error) => {
            fatchResponseCatch(error, alertElement);
        });

        return false;
    });
}

const popupDeleteItemModal = document.getElementById('popupDeleteItemModal');
let delete_id = 0;

// Add event listener to dynamically created buttons
if (tableContainer) {
    tableContainer.addEventListener('click', function (e) {
        e.preventDefault();

        if (e.target && e.target.classList.contains('delete-button')) {
            selectedButton = e.target; // Store the clicked button

            delete_id = selectedButton.getAttribute('data-id');

            popupTargetModel = popupDeleteItemModal;

            showPopupForm();

            const deleteButton = document.getElementById('btnPopupDelete');
            deleteButton.disabled = false;
            deleteButton.focus();
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

            const table_body = document.getElementById('table-body');

             // Get all <tr> elements inside the table
            const rows = document.querySelectorAll('#table-body tr').length;           

            if(rows <=1){
                current_page = current_page >= 1 ?  current_page - 1 : current_page;
            }
            console.log(current_page);
            fetchAndRender(current_page);

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

const formSearch = document.getElementById("formSearch");
if (formSearch) {

    formSearch.addEventListener("submit", function (event) {

        event.preventDefault(); // Prevent form submission

        if (formProcessing) {
            return false;
        }       

        fetchAndRender();
    });
}

const tableSearch = document.getElementById('table-search');
if(tableSearch){
    tableSearch.addEventListener('keydown', function(event) {
        if (event.key === "Enter") {
            event.preventDefault();
            fetchAndRender();
        }
    });
}

const btnSearchText = document.getElementById("btnSearchText");
if (btnSearchText) {
    btnSearchText.addEventListener("click", function (event) {
        event.preventDefault(); // Prevent form submission

        if (formProcessing) {
            return false;
        }       

        fetchAndRender();
    });
}

document.getElementById('profile_type').value = 'men';
document.getElementById('type').value = 'batsman';
document.getElementById('style').value = 'Left Hand Batsman';
document.getElementById('dob').value = '02-02-1980';


document.getElementById('category').value = '1';
document.getElementById('nick_name').value = 'batsman';
document.getElementById('last_played_league').value = 'Left Hand Batsman';
document.getElementById('address').value = 'Kurla';

document.getElementById('city').value = 'Nehru Nagar';
document.getElementById('email').value = 'salimsheikh@gmail.com';
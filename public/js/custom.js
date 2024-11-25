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

        // Select the form by its ID
        const currentForm = document.getElementById('popupAddForm');

        // Find the submit button within the form and disable it
        if (currentForm) {
            const submitButton = currentForm.querySelector('button[type="submit"], input[type="submit"]');
            if (submitButton) {
                submitButton.disabled = false; // Disable the button
            }
        }
        
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
    document.getElementById("popupAddForm").addEventListener("submit", async (e) => {
        e.preventDefault(); // Prevent form submission        

        if (formProcessing) {
            return false;
        }        

        // Get the current form
        const currentForm = e.target;

        // Find the .alert element within the current form
        const alertElement = currentForm.querySelector(".alert");

        // Get all input and textarea elements
        const fields = currentForm.querySelectorAll("input, textarea, select");
       
        alertElement.classList.remove("alert-danger", "alert-info", "alert-success", "alert-hidden");

        const validForm = validateForm(currentForm, alertElement);
        if (!validForm) {
            return false;
        }
        
        alertElement.textContent = lang.please_wait;
        alertElement.classList.add("alert-info");

        let headers = get_ajax_header(true);
        let formData = new FormData();
        const imageInput = document.getElementById('image');        
        if(imageInput){            
            formData.append('image', imageInput.files[0]);
            fields.forEach((field) => {
                if(field.name != 'image'){
                    formData.append(field.name, field.value);
                }
            });
        }else{            
            fields.forEach((field) => {
                formData.append(field.name, field.value);
            });
        }

        formProcessing = true;

        const submitButton = currentForm.querySelector('button[type="submit"], input[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true; // Disable the button
        }

        const response = await  fetch(`${BASE_API_URL}/store`, {
            method: 'POST',
            headers: headers,
            body: formData,
        }).then((response) => {
            console.log("response 1");
            //do not delete
            alertElement.classList.remove("alert-danger", "alert-info", "alert-success");
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
                formProcessing = false;
            }, 1500);
        }).catch((error) => {
            if (submitButton) {
                setTimeout(function () {
                    submitButton.disabled = false; // Disable the button
                }, 1000);
            }
            formProcessing = false;
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

            const headers = get_ajax_header(false);

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

                // Select the form by its ID
                const currentForm = document.getElementById('popupUpdateForm');

                // Find the submit button within the form and disable it
                if (currentForm) {
                    const submitButton = currentForm.querySelector('button[type="submit"], input[type="submit"]');
                    if (submitButton) {
                        submitButton.disabled = false; // Disable the button
                    }
                }

                setTimeout(function () {
                    alertElement.classList.remove("alert-success");
                    alertElement.classList.add('alert-hidden');
                }, 500);

                let formData = data.data;
                let elem = null;
                for (const key in formData) {
                    if (formData.hasOwnProperty(key)) {
                        if (formData[key] != "" && formData[key] != null) {                            
                            elem = document.getElementById('update_' + key);
                            if(elem){
                                if(key == 'image'){                                    
                                    elem.value = "";
                                }else{
                                    elem.value = formData[key];
                                }
                            }
                                      
                        }
                    }
                }

                elem = document.getElementById('update_image');
                if(elem){
                    elem.value = "";
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
        const currentForm = document.getElementById('popupUpdateForm');

        // Find the .alert element within the current form
        const alertElement = currentForm.querySelector(".alert");

        // Get all input and textarea elements
        const fields = currentForm.querySelectorAll("input, textarea, select");

        const validForm = validateForm(currentForm, alertElement);
        if (!validForm) {
            return false;
        }

        alertElement.textContent = lang.please_wait;
        alertElement.classList.add("alert-info");

        let headers = get_ajax_header(true);
        let formData = new FormData();
        const imageInput = document.getElementById('update_image');        
        if(imageInput){            
            if(imageInput.files.length > 0){
                formData.append('image', imageInput.files[0]);
            }
            fields.forEach((field) => {
                if(field.name != 'image'){
                    // console.log(field.name);
                    formData.append(field.name, field.value);
                }
            });
        }else{            
            fields.forEach((field) => {               
                formData.append(field.name, field.value);
            });
        }       

        formProcessing = true;

        const submitButton = currentForm.querySelector('button[type="submit"], input[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true; // Disable the button
        }

        fetch(`${BASE_API_URL}/${edit_id}`, {
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

            fetchAndRender(current_page);
            
            setTimeout(function () {
                hideModal();
                formProcessing = false;
            }, 1500);

        }).catch((error) => {
            if (submitButton) {
                setTimeout(function () {
                    submitButton.disabled = false; // Disable the button
                }, 1000);
            }
            formProcessing = false;
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

        const headers = get_ajax_header(false);

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
          
            fetchAndRender(current_page);

            setTimeout(function () {
                hideModal();
            }, 1000);
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
document.getElementById('style').value = 'right_hand_batsman';
document.getElementById('dob').value = '1980-01-01';

document.getElementById('category_id').value = '1';
document.getElementById('nickname').value = 'batsman';
document.getElementById('last_played_league').value = 'Last Played League';
document.getElementById('address').value = 'Kurla';

document.getElementById('city').value = 'Nehru Nagar';
document.getElementById('email').value = 'salimsheikh@gmail.com';

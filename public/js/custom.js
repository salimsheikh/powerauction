"use strict";

var formProcessing = false;

var popupTargetModel = null;

var popupCustomModel = document.getElementsByClassName("custom-modal")[0];

// Get the button that opens the modal
var buttonPopupShowAddItemModel = document.getElementById("buttonPopupShowAddItemModel");

// Get the modal
var popupAddItemModal = document.getElementById("popupAddItemModal");


document.addEventListener('DOMContentLoaded', () => {
    const searchTags = document.querySelectorAll('.btn-search-tags');
    if (searchTags) {
        searchTags.forEach(searchButton => {

            searchButton.onclick = function(e){    

                const list_id = e.target.getAttribute('data-checkbox');                

                const prevSibling = e.target.previousElementSibling;

                const permissionItems = document.querySelectorAll(`ul.${list_id} li`);

                const query = prevSibling.value.trim().toLowerCase();

                prevSibling.value = query;

                permissionItems.forEach((item) => {
                    const text = item.textContent.trim().toLowerCase(); // Get the text of each permission in lowercase
        
                    // Show or hide the item based on whether it matches the query
                    if (text.includes(query)) {
                        item.style.display = "block"; // Show matching items
                    } else {
                        item.style.display = "none"; // Hide non-matching items
                    }
                });
            }            
        });      
    }
});


if (buttonPopupShowAddItemModel) {
    // When the user clicks the button, open the modal 
    buttonPopupShowAddItemModel.onclick = function () {

        popupTargetModel = popupAddItemModal;

        showPopupForm();

        const focus_first = popupTargetModel.querySelector(".focus_first");

        if(focus_first){
            focus_first.focus();
        }        

        // Select the form by its ID
        const currentForm = document.getElementById('popupAddForm');

        // Find the submit button within the form and disable it
        if (currentForm) {
            const submitButton = currentForm.querySelector('button[type="submit"], input[type="submit"]');
            if (submitButton) {
                submitButton.disabled = false; // Disable the button
            }
        }

        // Get all input and textarea elements
        const fields = currentForm.querySelectorAll("input, textarea, select");

        fields.forEach((field) => {
            field.classList.remove("invalid");
        });

        //cleanForm(fields);

        updateButtonText(document.querySelectorAll('.add_permission'),document.getElementById('add_permission'));

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
        let formData = getFormData(fields);

        formProcessing = true;

        const submitButton = currentForm.querySelector('button[type="submit"], input[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true; // Disable the button
        }

        await fetch(`${BASE_API_URL}/store`, {
            method: 'POST',
            headers: headers,
            body: formData,
        }).then((response) => {
            logConsole("response 1");
            
            //do not delete
            alertElement.classList.remove("alert-danger", "alert-info", "alert-success");

            if (!response.ok) {
                return response.json().then((error) => {
                    throw error;
                });
            }

            return response.json();
        }).then((data) => {
            logConsole("response 2");
            alertElement.textContent = data.message;
            alertElement.classList.add("alert-success");
            
            /** Team player popup select dropdonw */
            if (data?.team_player_ids) {
                disableSelectedPlayers(data.team_player_ids,'player_id');
            }

            cleanForm(fields);

            fetchAndRender(current_page);

            setTimeout(function () {

                if(typeof autoCloseAddPopup !== undefined){
                    if(autoCloseAddPopup){
                        hideModal();    
                    }else{                        
                        alertElement.classList.add("alert-hidden");
                        submitButton.disabled = false;
                    }
                }else{
                    hideModal();
                }
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
        if (e.target && e.target.classList.contains('edit-button')) {
            e.preventDefault();
            selectedButton = e.target; // Store the clicked button

            if (formProcessing) {
                showToast(lang.please_wait, 'warning',true);
                return false;
            }

            edit_id = e.target.getAttribute('data-id');

            document.getElementById('update_id').value = edit_id;

            popupTargetModel = popupUpdateItemModal;

            // Get all input and textarea elements
            const fields = popupTargetModel.querySelectorAll("input, textarea, select");

            cleanForm(fields);

            fields.forEach((field) => {
                field.classList.remove("invalid");
            });

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

            if (buttonText) buttonText.classList.add('hidden'); // Hide the text
            if (loadingSpinner) loadingSpinner.classList.remove('hidden'); // Show the spinner

            formProcessing = true;

            const searchButton = popupTargetModel.querySelector(".btn-search-tags");
            const list_id = searchButton.getAttribute('data-checkbox');                
            const permissionItems = document.querySelectorAll(`ul.${list_id} li`);
            permissionItems.forEach((item) => {
                item.style.display = "block";
            })

            console.log(searchButton);
            

            fetch(`${BASE_API_URL}/edit/${edit_id}`, {
                method: 'get',
                headers: headers
            }).then((response) => {
                logConsole("response 1");
                //do not delete
                alertElement.classList.remove("alert-danger", "alert-info", "alert-success");
                

                buttonText.classList.remove('hidden'); // Hide the text
                loadingSpinner.classList.add('hidden'); // Show the spinner

                if (!response.ok) {
                    return response.json().then((error) => {
                        throw error;
                    });
                }

                return response.json();
            }).then((data) => {
                formProcessing = false;
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
                            if (elem) {
                                if (elem.type != 'file') {
                                    elem.value = formData[key];
                                }
                            }
                        }
                    }
                }

                elem = document.getElementById('update_image');
                if (elem) {
                    elem.value = "";
                }

                if(data?.rolePermissions){
                    const checkboxes = document.querySelectorAll('.update_permission');

                    // Loop through the checkboxes and uncheck them
                    checkboxes.forEach((checkbox) => {
                        checkbox.checked = false;
                    });

                    for (const [id] of Object.entries(data?.rolePermissions)) {
                        elem = document.getElementById('update_permission_' + id);
                        if (elem) {
                            elem.checked = true;
                        }
                    }
                    updateButtonText(checkboxes,document.getElementById('update_permission'));
                }

                showPopupForm();
                // setTimeout(initializeSearch,1000);
              

            }).catch((error) => {
                setTimeout(function () {                    
                    formProcessing = false;
                }, 1000);
                
                fatchResponseCatch(error, null);
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

        alertElement.classList.remove("alert-danger", "alert-info", "alert-success", "alert-hidden");

        // Get all input and textarea elements
        const fields = currentForm.querySelectorAll("input, textarea, select");

        const validForm = validateForm(currentForm, alertElement);
        if (!validForm) {
            return false;
        }

        alertElement.textContent = lang.please_wait;
        alertElement.classList.add("alert-info");

        document.getElementById('update_id').value = edit_id;

        let headers = get_ajax_header(true);
        let formData = getFormData(fields);

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
            logConsole("response 1");
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
            logConsole("response 2");
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
        if (e.target && e.target.classList.contains('delete-button')) {
            e.preventDefault();
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
            logConsole("response 1");
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
            logConsole("response 2", data);
            alertElement.textContent = data.message;
            alertElement.classList.add("alert-success");

            const table_body = document.getElementById('table-body');

            // Get all <tr> elements inside the table
            const rows = document.querySelectorAll('#table-body tr').length;

            if (rows <= 1) {
                current_page = current_page >= 1 ? current_page - 1 : current_page;
            }

            fetchAndRender(current_page);

            if (data?.team_player_ids) {                
                disableSelectedPlayers(data.team_player_ids,'player_id');
            }

            setTimeout(function () {

                hideModal();
                
            }, 1000);
        }).catch((error) => {
            console.log("Errors:", error)
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
if (tableSearch) {
    tableSearch.addEventListener('keydown', function (event) {
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

// Add event listener to dynamically created buttons
if (tableContainer) {
    tableContainer.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('view-button')) {
            e.preventDefault();
            selectedButton = e.target; // Store the clicked button

            if (formProcessing) {
                return false;
            }

            edit_id = e.target.getAttribute('data-id');

            popupTargetModel = document.getElementById('popupViewItemModal');

            // Find the .alert element within the current form
            const alertElement = popupTargetModel.querySelector(".alert");
            alertElement.classList.remove("alert-danger", "alert-info", "alert-success", "alert-hidden");

            alertElement.textContent = lang.please_wait;
            alertElement.classList.add("alert-info");

            const headers = get_ajax_header(false);

            formProcessing = true;

            fetch(`${BASE_API_URL}/view/${edit_id}`, {
                method: 'get',
                headers: headers
            }).then((response) => {
                logConsole("response 1");
                formProcessing = false;
                alertElement.classList.remove("alert-danger", "alert-info", "alert-success");
                if (!response.ok) {
                    return response.json().then((error) => {
                        throw error;
                    });
                }

                return response.json();

            }).then((data) => {

                alertElement.textContent = data.message;
                alertElement.classList.add("alert-success");

                let formData = data.data;
                let rows = data.rows;
                createPlayerProfile(rows, formData);
                showPopupForm();

            }).catch((error) => {
                logConsole(error);
                createPlayerProfile(null, null);
                showPopupForm();
                fatchResponseCatch(error, alertElement);
            });

        }
    });
}

let team_transactions_data = [];
let team_id = "";
let ttd_storage_key = "team_transactions_data_" + team_id;

// Add event listener to dynamically created buttons
if (tableContainer) {
    tableContainer.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('booster-button')) {
            e.preventDefault();
            selectedButton = e.target; // Store the clicked button

            if (formProcessing) {
                return false;
            }

            team_id = e.target.getAttribute('data-id');

            ttd_storage_key = "team_transactions_data_" + team_id;

            const popupid = e.target.getAttribute('data-popupid');

            popupTargetModel = document.getElementById(popupid);

            // Get all input and textarea elements
            const fields = popupTargetModel.querySelectorAll("input, textarea, select");

            cleanForm(fields);

            fields.forEach((field) => {
                field.classList.remove("invalid");
            });

            const focus_first = popupTargetModel.querySelector(".focus_first");

            if (focus_first) focus_first.focus();

            showPopupForm();

            if (plan_type) {
                plan_type.value = "";
                plan_type.focus();
            }

            if (planAmountEle) {
                planAmountEle.value = '';
                planAmountEle.setAttribute("readonly", true);
            }

            // Retrieve the array from localStorage
            let team_transactions_data = getLocalStorage(ttd_storage_key, true);
            if (team_transactions_data.length > 0) {
                populateTableTransactions(team_transactions_data);
                return false;
            }

            populateTableTransactions([]);

            let headers = get_ajax_header(true);

            fetch(`${TRANS_API_URL}/${team_id}`, {
                method: 'get',
                headers: headers,
            }).then((response) => {
                if (!response.ok) {
                    return response.json().then((error) => {
                        throw error;
                    });
                }
                return response.json();
            }).then((data) => {

                team_transactions_data = data.data;

                setLocalStorage(ttd_storage_key, data.data, true)

                // Populate the table with the transactions
                populateTableTransactions(team_transactions_data);


            }).catch((error) => {
                logConsole(error);
                // fatchResponseCatch(error, alertElement);
            });
        }
    });
}


const planAmountEle = document.getElementById("plan_amount");
if (planAmountEle) {
    // If the value is 0, clear it when focused
    planAmountEle.addEventListener("focus", function (event) {
        event.preventDefault();
        if (event.target.value === "0") {
            event.target.value = "";
        }
    });

    // If the input is empty, set it to 0
    planAmountEle.addEventListener("blur", function (event) {
        event.preventDefault();
        if (event.target.value === "") {
            event.target.value = "0";
        }
    });

    // Allow only numeric input (prevent non-numeric characters)
    planAmountEle.addEventListener("input", function (event) {
        event.preventDefault();
        const value = event.target.value;
        event.target.value = value.replace(/[^0-9]/g, ''); // Remove any non-numeric characters
    });
}

const plan_type = document.getElementById("plan_type");
if (plan_type) {
    plan_type.addEventListener("change", function (event) {

        event.preventDefault();

        let selectedId = event.target.value;
        const planAmountEle = document.getElementById('plan_amount');

        if (selectedId == "") {
            planAmountEle.value = '';
            planAmountEle.setAttribute("readonly", true);
        } else {
            selectedId = parseInt(selectedId);// Get selected dropdown value as integer
            const plan = booster_plans.find(plan => plan.id === selectedId); // Find the matching plan by ID
            const amount = plan ? plan.amount : 0; // If plan exists, get the amount; otherwise, 0

            planAmountEle.value = amount; // Update the amount display
            if (amount > 0) {
                planAmountEle.setAttribute("readonly", true);
            } else {
                planAmountEle.removeAttribute("readonly");
            }
        }
    });
}

const popupBoosterForm = document.getElementById("popupBoosterForm");
if (popupBoosterForm) {
    popupBoosterForm.addEventListener("submit", function (event) {

        event.preventDefault(); // Prevent form submission

        if (formProcessing) {
            return false;
        }

        // Get the current form
        const currentForm = event.target;

        // Find the .alert element within the current form
        const alertElement = currentForm.querySelector(".alert");

        alertElement.classList.remove("alert-danger", "alert-info", "alert-success", "alert-hidden");

        // Get all input and textarea elements
        const fields = currentForm.querySelectorAll("input, textarea, select");

        const validForm = validateForm(currentForm, alertElement);
        if (!validForm) {
            return false;
        }

        const sanctum_token = get_local_storage_token('sanctum_token');
        if (!sanctum_token) {
            alertElement.textContent = lang.something_wrong;
            alertElement.classList.add("alert-danger");
            return false;
        }

        ttd_storage_key = "team_transactions_data_" + team_id;

        let headers = get_ajax_header(true);
        let formData = getFormData(fields);
        formData.append('team_id', team_id);

        alertElement.textContent = lang.please_wait;
        alertElement.classList.add("alert-info");

        formProcessing = true;

        const submitButton = currentForm.querySelector('button[type="submit"], input[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true; // Disable the button
        }

        showToast(lang.please_wait, 'info');

        fetch(`${TRANS_API_URL}/store`, {
            method: 'POST',
            headers: headers,
            body: formData,
        }).then((response) => {
            logConsole("response 1");
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
            logConsole("response 2", data);
            alertElement.textContent = data.message;
            alertElement.classList.add("alert-success");

            team_transactions_data = data.transactions;

            // Save the updated array back to localStorage
            localStorage.setItem(ttd_storage_key, JSON.stringify(team_transactions_data));

            // Populate the table with the transactions
            populateTableTransactions(team_transactions_data);

            // Get all <tr> elements inside the table
            const rows = document.querySelectorAll('#table-body tr').length;

            if (rows <= 1) {
                current_page = current_page >= 1 ? current_page - 1 : current_page;
            }

            fetchAndRender(current_page);

            setTimeout(function () {
                hideModal();

                if (submitButton) {
                    submitButton.disabled = false; // Disable the button
                }
            }, 1000);

        }).catch((error) => {
            if (submitButton) {
                submitButton.disabled = false; // Disable the button
            }
            logConsole(error);
            fatchResponseCatch(error, alertElement);
        });
    });
}

// Function to toggle checkboxes
document.querySelectorAll('.selectAllPermission').forEach(button => {
    const checkboxClass = button.getAttribute('data-checkbox');
    const checkboxes = document.querySelectorAll(`.${checkboxClass}`);

    button.addEventListener('click', function (event) {
        event.preventDefault(); // Prevent default action of the link        
        const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);

        // If all checkboxes are checked, uncheck them
        if (allChecked) {
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            //button.textContent = `Select All ${checkboxClass.replace('_', ' ').toUpperCase()}`; // Update button text
            button.textContent = lang.select_all;

            // Trigger the change event manually on each checkbox
           //checkbox.dispatchEvent(new Event('change'));
        } else {
            // Otherwise, check all checkboxes
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;

                // Trigger the change event manually on each checkbox
                //checkbox.dispatchEvent(new Event('change'));
            });
            //button.textContent = `Unselect All ${checkboxClass.replace('_', ' ').toUpperCase()}`; // Update button text
            button.textContent = lang.unselect_all;
        }
    });

    // Listen for any changes in the checkboxes and update button text
    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', function (event) {
            event.preventDefault(); // Prevent default action of the link 
            updateButtonText(checkboxes, button);
        });
    });
});

function updateButtonText(checkboxes,button) {
    if(checkboxes && button){
        const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
        if (allChecked) {
            button.textContent = lang.unselect_all;
        } else {
            button.textContent = lang.select_all;
        }
    }
}

const role_name = document.querySelector('.role_name');
if(role_name){
    role_name.addEventListener('input', function (event) {
        // Replace characters that are not A-Z, a-z, 0-9, space, or hyphen
        this.value = this.value.replace(/[^a-zA-Z0-9\- ]/g, '');
    });
}

function initializeSearch() {
  
    const searchInput = document.querySelector('.search-tags');
    const permissionItems = document.querySelectorAll('ul li');

    if (!searchInput || permissionItems.length === 0) {
        console.error("Search input or permission items not found. Ensure the popup is open and the structure is correct.");
        return;
    }

    // Add an event listener to the search input
    searchInput.addEventListener("input", function () {
        const query = searchInput.value.toLowerCase(); // Get the search query in lowercase

        console.log(query);

        permissionItems.forEach((item) => {
            const text = item.textContent.trim().toLowerCase(); // Get the text of each permission in lowercase

            // Show or hide the item based on whether it matches the query
            if (text.includes(query)) {
                item.style.display = "block"; // Show matching items
            } else {
                item.style.display = "none"; // Hide non-matching items
            }
        });
    });
}


// Initial load
fetchAndRender(current_page);

/*
document.getElementById('player_name').value = 'Salim Shaikh';
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
*/
/*
if(document.getElementById('sponsor_name')){
    document.getElementById('sponsor_name').value = 'Salim Shaikh';
    document.getElementById('sponsor_description').value = 'batsman';
    document.getElementById('sponsor_website_url').value = 'right_hand_batsman';
    document.getElementById('sponsor_type').value = 'gold';
}
*/
/*
if(document.getElementById('team_name')){
    const sampleName = getRandomSample();
    document.getElementById('team_name').value = sampleName.team;
    document.getElementById('league_id').value = sampleName.randomNumber;
    document.getElementById('owner_name').value = sampleName.name;
    document.getElementById('owner_email').value = sampleName.email;
    document.getElementById('owner_phone').value = sampleName.mobile;
    document.getElementById('owner_password').value = '123';
}
*/
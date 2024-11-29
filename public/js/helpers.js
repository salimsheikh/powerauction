"use strict"

// Event listener for closing the modal with the Escape key
document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' || event.keyCode === 27) { // Check for 'Escape' key
        if (popupTargetModel) { // Only hide if modal is active
            hideModal();
        }
    }
});

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


// Select all inputs in the form
const inputs = document.querySelectorAll('input, textarea, select');

// Add onchange event listener to all inputs
inputs.forEach(input => {
    input.addEventListener('keydown', function (e) {
        // Check if the input has 'invalid' class
        if (input.classList.contains('invalid')) {
            input.classList.remove('invalid'); // Remove the 'invalid' class
        }

        if (input.classList.contains('base_price')) {
            var v = input.classList.contains('base_price')
            if (isNumberKey(v, e)) {
                input.classList.remove('invalid'); // Remove the 'invalid' class
            }
        }
    });

    input.addEventListener('change', function (e) {
        // Check if the input has 'invalid' class
        if (input.classList.contains('invalid')) {
            input.classList.remove('invalid'); // Remove the 'invalid' class
        }
    });
});

function isNumberKey(txt, evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode == 46) {
        //Check if the text already contains the . character
        if (txt.value.indexOf('.') === -1) {
            return true;
        } else {
            return false;
        }
    } else {
        if (charCode > 31 &&
            (charCode < 48 || charCode > 57))
            return false;
    }
    return true;
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

    // Get all input and textarea elements
    const fields = modalContent.querySelectorAll("input, textarea");

    // Remove the invalid class and trim inputs
    fields.forEach((field) => {
        field.classList.remove("invalid");
    });

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
    if (alertElement) {
        alertElement.classList.add("alert-hidden");
    }

    const body_content = popupTargetModel.querySelector('.body-content');
    if (body_content) {
        body_content.style.display = "block";
    }
}

function fatchResponseCatch(error, alertElement) {

    logConsole("response 3");
    if (error.errors) {
        let errorMessage = '';
        let firstInvalidField = false;
        for (const field_name in error.errors) {
            errorMessage += `${error.errors[field_name].join(', ')}` + "<br>";

            // Select the input element by its name attribute
            const errorInput = document.querySelector('[name="' + field_name + '"]');
            if (errorInput) {
                errorInput.classList.add('invalid'); // Replace 'your-class-name' with the desired class
                if (!firstInvalidField) {
                    firstInvalidField = true;
                    errorInput.focus();
                }
            }
        }

        if (alertElement) {
            alertElement.classList.add("alert-danger");
            alertElement.classList.remove("alert-hidden");
            alertElement.innerHTML = errorMessage;
        }
    } else {
        logConsole('Error:', error);
        if (alertElement) {
            alertElement.classList.add("alert-danger");
            alertElement.classList.remove("alert-hidden");
            alertElement.textContent = 'The server returned an invalid response. Please try again later.';
        }
    }
}

function validateForm(currentForm, alertElement) {

    // Get all input and textarea elements
    const fields = currentForm.querySelectorAll("input, textarea, select");

    // Remove the invalid class and trim inputs
    fields.forEach((field) => {
        field.classList.remove("invalid");
        if (field.type === "text" || field.tagName === "TEXTAREA" || field.type === "email" || field.type === "select") {
            field.value = field.value.trim();
        }
    });

    let firstInvalidField = null;

    let validData = true;
    let requiredData = true;

    let formdata = []
    fields.forEach((field) => {
        formdata[field.name] = field.value;
    });

    // Validate fields
    fields.forEach((field) => {
        if (field.classList.contains("required") && field.value === "") {
            field.classList.add("invalid");
            if (!firstInvalidField) {
                firstInvalidField = field;
            }
            requiredData = true;
        }

        if (field.classList.contains("email_validate") && field.value !== "") {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(field.value)) {
                field.classList.add("invalid");
                if (!firstInvalidField) {
                    firstInvalidField = field;
                }
                validData = false;
            }
        }

        if (field.classList.contains("virtual_point") && field.value !== "") {
            // Validate if it's a valid number with one decimal place
            const regex = /^[0-9]+$/;
            if (!regex.test(field.value)) {
                field.classList.add("invalid");
                if (!firstInvalidField) {
                    firstInvalidField = field;
                }
                validData = false;
            } else {
                if (field.value <= 0) {
                    field.classList.add("invalid");
                    if (!firstInvalidField) {
                        firstInvalidField = field;
                    }
                    validData = false;
                }
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
    let ft = '';

    // Remove the invalid class and trim inputs
    fields.forEach((field) => {

        ft = field.type;

        if (ft === "color") {
            field.value = '#000001';
        } else {
            field.value = "";
        }
    });
}

function getFormData(fields) {
    let formData = new FormData();
    fields.forEach((field) => {
        if (field.type == 'file') {
            if (field.files.length > 0) {
                formData.append(field.name, field.files[0]);
            }
        } else {
            formData.append(field.name, field.value.trim());
        }
    });

    return formData;
}


function get_csrf_token() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
}

function get_ajax_header(isFileUpload) {

    const csrf_token = get_csrf_token();

    const sanctum_token = get_local_storage_token('sanctum_token');

    const headers = {
        //'Content-Type': 'application/json',
        'Authorization': `Bearer ${sanctum_token}`,
        'X-CSRF-TOKEN': csrf_token,
    }

    // Example dynamic logic to set Content-Type
    if (!isFileUpload) {
        headers['Content-Type'] = 'application/json';
    }

    return headers;
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
                logConsole('Token saved to localStorage');
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

//initialize(); // Call the async function

let current_page = 1;

// Fetch data and render table
async function fetchAndRender(page = 1) {

    const tbody = document.getElementById('table-body');
    if (!tbody) {
        return false;
    }

    var SearchedText = document.getElementById('table-search');
    var tableSearch = "";
    if (SearchedText) {
        tableSearch = SearchedText.value;
    }

    let url = `${BASE_API_URL}?page=${page}`;

    url += tableSearch != "" ? "&query=" + encodeURIComponent(tableSearch) : "";

    const headers = get_ajax_header(false);

    // showToast(lang.please_wait, 'info');

    const response = await fetch(url, {
        method: 'get',
        headers: headers
    });

    const data = await response.json();

    const columns = data.columns;

    const items = data.items;

    renderTableHeader(columns);

    renderTable(data);

    renderPagination(items.links, items.current_page, items.last_page, items.total);

    // Apply to all elements with class "dynamic-box"
    document.querySelectorAll('span.color-code').forEach(setTextColorBasedOnBg);

    formProcessing = false;
}

function renderTableHeader(columns) {
    document.getElementById('table-head').innerHTML = `<tr>${Object.entries(columns).map(([key, value,]) => `<th class="${key}">${value}</th>`).join('')}</tr>`;
}
// Render table rows
function renderTable(data) {

    const columns = data.columns;

    const items = data.items.data;

    const totalItems = data.items.total;

    const tbody = document.getElementById('table-body');

    if (totalItems > 0) {
        tbody.innerHTML = renderTableRows(items, columns, data.items);
    } else {
        const colspan = Object.keys(columns).length;
        tbody.innerHTML = `
            <tr>
                <td colspan="${colspan}">
                    <p class="text-center text-gray-800 dark:text-white">${lang.not_found}</p>
                </td>
            </tr>
        `;
    }
}

function renderTableRows(rows, columns, page) {

    let id = 0;
    let output = "";
    let cell_value = "";
    let cell_class = "";
    let cell_edit = `<span class="block buttonText">${lang.edit}</span>
                     <svg class="hidden w-5 h-5 animate-spin text-white absolute loadingSpinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 000 8v4a8 8 0 01-8-8z"></path>
                     </svg>`;
    let view_icon = `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-4.553m0 0L21 5.447m-1.447 1.553L10 15m5 0l-4.553 4.553m0 0L3 15" />
    </svg>`

    const buttons = [];

    let i = 0;
    rows.forEach(row => {
        id = row.id;

        if (id <= 1 && rows.length <= 1) {
            buttons['delete'] = `<button class="btn delete-btn delete-button material-icons" data-id="${id}" title="${lang.delete}" disabled>delete</button>`;
        } else {
            buttons['delete'] = `<button class="btn delete-btn delete-button material-icons" data-id="${id}" title="${lang.delete}">delete</button>`;
        }

        buttons['edit'] = `<button class="btn edit-btn edit-button" data-id="${id}" title="${lang.edit}">${cell_edit}</button>`;
        buttons['view'] = `<button class="btn view-btn view-button hover:bg-purple-800" data-id="${id}" title="${lang.view}">${lang.view}</button>`;
        buttons['booster'] = `<button class="btn view-btn booster-button hover:bg-purple-800" data-popupid="popupBoosterModal" data-id="${id}" title="${lang.view}">Booster</button>`;

        buttons['auction'] = `<a href="" class="btn view-btn booster-button hover:bg-purple-800" title="${lang.view}">Auction</a>`;

        output += "<tr>";
        for (const [cn] of Object.entries(columns)) {
            cell_value = "";
            cell_class = cn;
            cell_value = row[cn] == undefined ? '' : row[cn];
            switch (cn) {
                case "sr":
                    cell_value = ((page.current_page - 1) * page.per_page) + i + 1;
                    i++;
                    break;
                case "color_code":
                    cell_value = cell_value == null ? '' : `<span class="color-code" style="background-color: ${cell_value};">${cell_value}</span>`;
                    break;
                case "image":
                    if (cell_value) {
                        if (cell_value.includes('http')) {
                            cell_value = `<img src="${cell_value}" class="profile-image w-15 rounded-full shadow-md">`;
                        } else {
                            cell_value = `<img src="${image_url}/players/thumbs/${cell_value}" class="profile-image w-15 rounded-full shadow-md">`;
                        }
                    }
                    break;
                case "sponsor_logo":
                    if (cell_value) {
                        if (cell_value.includes('http')) {
                            cell_value = `<img src="${cell_value}" class="profile-image w-15 rounded-full shadow-md">`;
                        } else {
                            cell_value = `<img src="${image_url}/sponsors/thumbs/${cell_value}" class="profile-image w-15 rounded-full shadow-md">`;
                        }
                    }
                    break;
                case "team_logo_thumb":
                case "team_log":
                    if (cell_value) {
                        if (cell_value.includes('http')) {
                            cell_value = `<img src="${cell_value}" class="profile-image w-15 rounded-full shadow-md">`;
                        } else {
                            cell_value = `<img src="${image_url}/teams/thumbs/${cell_value}" class="profile-image w-15 rounded-full shadow-md">`;
                        }
                    }
                    break;
                case "league_actions":
                    cell_class += " actions";
                    cell_value += "<div>";
                    cell_value += buttons['auction'];
                    cell_value += buttons['edit'];
                    cell_value += buttons['delete'];
                    cell_value += "</div>";
                    break;
                case "actions":
                    cell_class += " actions";
                    cell_value += "<div>";
                        cell_value += buttons['edit'];
                        cell_value += buttons['delete'];
                    cell_value += "</div>";
                    break;
                case "view_actions":
                    cell_class += " actions";
                    cell_value += "<div>";
                    cell_value += buttons['view'];
                    cell_value += buttons['edit'];
                    cell_value += buttons['delete'];
                    cell_value += "</div>";
                    break;
                case "team_actions":
                    cell_class += " actions";
                    cell_value += "<div>";
                    cell_value += buttons['booster'];
                    cell_value += buttons['edit'];
                    cell_value += buttons['delete'];
                    cell_value += "</div>";
                    break;
                case "description":
                case "sponsor_description":
                    cell_value = nl2br(cell_value);
                    break;

            }
            output += `<td class="${cell_class}">${cell_value}</td>`;

        }
        output += "</tr>";
    });

    return output;
}

function renderRowByMap(rows) {
    return rows.map(row => `
        <tr>
            <td>${row.id}</td>
            <td>${row.category_name}</td>            
            <td>${row.base_price}</td>
            <td>${row.color_code == null ? '' : `<span class="color-code" style="background-color: ${row.color_code};">${row.color_code}</span>`}</td>
            <td>${row.description}</td>
            <td class="text-center">
                <a href="#" class="btn edit-btn edit-button" data-id="${row.id}">Edit</a>
                <a href="#" class="btn delete-btn delete-button" data-id="${row.id}">Delete</a>
            </td>
        </tr>
    `).join('');
}

// Render pagination
function renderPagination(links, currentPage, totalPages, totalItems) {
    const pagination = document.getElementById('pagination');
    const paginationInfo = document.getElementById('pagination-info');
    if (totalItems > 0 && totalPages > 1) {
        pagination.innerHTML = links.map(link => `
            <li class="${link.active ? 'active' : ''} ${!link.url ? 'disabled' : ''}">
                <a href="#" onclick="handlePagination(event, '${link.url}')">${link.label}</a>
            </li>
        `).join('');

        // Render pagination info (e.g., current page, total pages, total items)        
        paginationInfo.innerHTML = `Page ${currentPage} of ${totalPages} | Total items: ${totalItems}`;
    } else {
        pagination.innerHTML = ``;
        paginationInfo.innerHTML = ``;
    }
}

// Handle pagination click
function handlePagination(event, url) {
    event.preventDefault();
    if (url) {
        const page = new URL(url).searchParams.get('page');
        current_page = page;
        fetchAndRender(page);
    }
}

// Initial load
fetchAndRender(current_page);

function setTextColorBasedOnBg(element) {
    // Get the background color
    const bgColor = window.getComputedStyle(element).backgroundColor;

    // Extract RGB values
    const rgb = bgColor.match(/\d+/g).map(Number); // Extract numbers from "rgb(x, y, z)"

    // Calculate luminance
    const luminance = (0.299 * rgb[0] + 0.587 * rgb[1] + 0.114 * rgb[2]) / 255;

    // Set text color based on luminance
    element.style.color = luminance > 0.5 ? "black" : "white";
}

function createPlayerProfile(rows, formData) {

    const tbody = popupTargetModel.querySelector("table tbody");

    const img = popupTargetModel.querySelector("img");

    if (formData == null) {
        img.display = 'none';
        return false;
    }


    tbody.innerHTML = "";
    if (formData.image_thumb) {
        const url = `${image_url}/players/thumbs/${formData.image_thumb}`;
        img.src = url;
        img.display = 'block';
    }

    let elem = null;
    for (const key in rows) {
        if (rows.hasOwnProperty(key)) {

            // Create a new table row
            let newRow = document.createElement('tr');

            // Create and append a <th> cell
            let thCell = document.createElement('th');
            thCell.textContent = rows[key];
            thCell.scope = 'row';
            newRow.appendChild(thCell);

            // Create and append <td> cells
            let tdCell1 = document.createElement('td');
            tdCell1.textContent = formData[key];
            newRow.appendChild(tdCell1);

            // Append the newly created row to the <tbody>
            tbody.appendChild(newRow);
        }
    }
}

function nl2br(str, is_xhtml) {
    if (typeof str === 'undefined' || str === null) {
        return '';
    }
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

function getRandomSample() {
    const firstNames = ["John", "Jane", "Chris", "Katie", "Michael", "Sarah", "David", "Emily", "Robert", "Laura"];
    const lastNames = ["Smith", "Johnson", "Williams", "Brown", "Jones", "Garcia", "Miller", "Davis", "Martinez", "Lopez"];
    const domains = ["example.com", "testmail.com", "sample.org", "mailinator.com", "fakemail.net"];
    const teamNames = ["Team Alpha", "Team Beta", "Team Gamma", "Team Delta", "Team Omega"];

    // Generate random first name, last name, email
    const firstName = firstNames[Math.floor(Math.random() * firstNames.length)];
    const lastName = lastNames[Math.floor(Math.random() * lastNames.length)];
    const domain = domains[Math.floor(Math.random() * domains.length)];
    const email = `${firstName.toLowerCase()}.${lastName.toLowerCase()}@${domain}`;

    // Generate random 10-digit mobile number
    const mobileNumber = Math.floor(1000000000 + Math.random() * 9000000000).toString();

    // Generate random team name
    const teamName = teamNames[Math.floor(Math.random() * teamNames.length)];

    // Generate random number between 1 and 4
    const randomNumber = Math.floor(Math.random() * 4) + 1;

    return {
        name: `${firstName} ${lastName}`,
        email: email,
        mobile: mobileNumber,
        team: teamName,
        randomNumber: randomNumber
    };
}

// Function to append transactions to the table
function populateTableTransactions(transactions) {
    const tableBody = document.querySelector("#transactionTable tbody");

    // Clear existing rows
    tableBody.innerHTML = "";

    if (transactions.length <= 0) {

        logConsole("transactions(1): " + transactions.length);

        document.getElementById("teamTransaction").style.display = 'none';
    } else {
        // Loop through transactions and create table rows
        transactions.forEach(transaction => {
            const row = document.createElement("tr");

            // Create and append cells for each property
            const nameCell = document.createElement("td");
            nameCell.textContent = transaction.name;
            row.appendChild(nameCell);

            const amountCell = document.createElement("td");
            amountCell.textContent = transaction.amount;
            row.appendChild(amountCell);

            const createdAtCell = document.createElement("td");
            createdAtCell.textContent = new Date(transaction.created_at).toLocaleString(); // Format date
            row.appendChild(createdAtCell);

            // Append the row to the table body
            tableBody.appendChild(row);
        });

        logConsole("transactions(1): " + transactions.length);

        document.getElementById("teamTransaction").style.display = 'block';
    }
}

function setLocalStorage(StorageKey, StorageValue, stringify = false) {
    // Save the updated array back to localStorage
    if (stringify == true) {
        localStorage.setItem(StorageKey, JSON.stringify(StorageValue));
    } else {
        localStorage.setItem(StorageKey, StorageValue);
    }
}

function getLocalStorage(StorageKey, stringify = false) {
    // Save the updated array back to localStorage
    if (stringify == true) {
        return JSON.parse(localStorage.getItem(StorageKey)) || [];
    } else {
        return localStorage.getItem(StorageKey) || '';
    }
}


// Function to create toaster notifications
function showToast(message, type = 'info') {
    // Create a container if it doesn't exist
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        document.body.appendChild(container);
    }

    // Create a toast element
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;

    // Append the toast to the container
    container.appendChild(toast);

    // Remove the toast after 3 seconds
    setTimeout(() => {
        toast.classList.add('hide');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3000);
}

/*
showToast('This is a success message!', 'success');
showToast('This is an error message!', 'error');
showToast('This is a warning message!', 'warning');
showToast('This is an info message!', 'info');
*/

const displayLog = true;

// Function to log messages with any number of parameters
function logConsole(...args) {
    if (displayLog) {
        const error = new Error();
        const stackLine = error.stack?.split("\n")[2] || "";
        const match = stackLine.match(/\((.*):(\d+):(\d+)\)/);

        if (match) {
            const fileName = match[1];
            const lineNumber = match[2];
            console.log(`[${fileName}:${lineNumber}]`, ...args);
        } else {
            console.log(...args);
        }
    }
}

//logConsole('Test message', { key: 'value' }, [1, 2, 3]);
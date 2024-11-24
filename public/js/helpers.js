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
    alertElement.classList.add("alert-hidden");

    const body_content = popupTargetModel.querySelector('.body-content');
    if (body_content) body_content.style.display = "block";
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
        alertElement.textContent = 'The server returned an invalid response. Please try again later.';
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
    let ft = '';
    return false;
    // Remove the invalid class and trim inputs
    fields.forEach((field) => {
        
        ft = field.type;

        if (ft === "text" || ft === "email" || ft === "select" || ft == 'select-one') {
            field.value = '';
        }

        if(field.tagName === "TEXTAREA" || field.tagName === "textarea"){
            field.value = "";
        }

        if (ft === "color") {
            field.value = '#000001';
        }
    });
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

    const response = await fetch(url, {
        method: 'get',
        headers: headers
    });

    const data = await response.json();

    const columns = data.columns;

    const items = data.items;

    renderTableHeader(columns);

    renderTable(items.data, items.total, columns);

    renderPagination(items.links, items.current_page, items.last_page, items.total);

    // Apply to all elements with class "dynamic-box"
    document.querySelectorAll('span.color-code').forEach(setTextColorBasedOnBg);

    formProcessing = false;
}

function renderTableHeader(columns) {
    document.getElementById('table-head').innerHTML = `<tr>${Object.entries(columns).map(([key, value,]) => `<th class="${key}">${value}</th>`).join('')}</tr>`;
}
// Render table rows
function renderTable(rows, totalItems, columns) {
    const tbody = document.getElementById('table-body');

    if (totalItems > 0) {
        tbody.innerHTML = renderTableRows(rows, columns);
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

function renderTableRows(rows, columns) {
    let id = 0;
    let output = "";
    let cell_value = "";
    let cell_class = "";
    let cell_edit = `<span class="block buttonText">${lang.edit}</span>
                     <svg class="hidden w-5 h-5 animate-spin text-white absolute loadingSpinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 000 8v4a8 8 0 01-8-8z"></path>
                     </svg>`;
    rows.forEach(row => {
        console.log(row);
        id = row.id;
        output += "<tr>";
        for (const [cn] of Object.entries(columns)) {
            cell_value = "";
            cell_class = cn;
            switch (cn) {
                case "action":
                    cell_value = row[cn];
                    break;
                case "color_code":
                    cell_value = row[cn];
                    cell_value = cell_value == null ? '' : `<span class="color-code" style="background-color: ${cell_value};">${cell_value}</span>`;
                    break;
                case "image":
                    cell_value = row[cn];
                    cell_value = `<img src="${image_url }/players/thumbs/${cell_value}">`;
                    break;
                case "actions":
                    cell_value += "<div>";
                    cell_value += `<a href="#" class="btn edit-btn edit-button"  data-id="${id}">${cell_edit}</a>`;
                    cell_value += `<a href="#" class="btn delete-btn delete-button" data-id="${id}">${lang.delete}</a>`;
                    cell_value += "</div>";
                    break;
                default:
                    cell_value = row[cn];
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



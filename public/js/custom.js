
const popupAddForm = document.getElementById("popupAddForm");
if (popupAddForm) {
    document.getElementById("popupAddForm").addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent form submission

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

        alertElement.classList.remove("alert-danger", "alert-info", "alert-success");
        alertElement.style.display = "block";

        // Focus on the first invalid field
        if (firstInvalidField) {
            firstInvalidField.focus();
            alertElement.textContent = "Please enter the required field.";
            alertElement.classList.add("alert-danger");
            return false;
        }

        const csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const sanctum_token = get_local_storage_token('sanctum_token');
        if(!sanctum_token){
            alertElement.textContent = "Some thing is wrong.";
            alertElement.classList.add("alert-danger");
            return false;
        }

        const headers = {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${sanctum_token}`,
            'X-CSRF-TOKEN': csrf_token,
        }

        alertElement.textContent = "Please Wait!";
        alertElement.classList.add("alert-info");

        var formValues = {};
        fields.forEach((field) => {
            formValues[field.name] = field.value;
        });

        console.log(sanctum_token);

        console.log(formValues);

        fetch("/api/backend/categories/store", {
            method: 'POST',
            headers: headers,
            body: JSON.stringify(formValues),
        }).then((response) => {
            if (!response.ok) {
                return response.json().then((error) => {
                    throw error;
                });
            }
            return response.json();
        }).then((data) => {
            alertElement.classList.remove("alert-danger", "alert-info", "alert-success");
            alertElement.textContent = data.message;
            alertElement.classList.add("alert-success");
        }).catch((error) => {            
            if (error.errors) {
                alertElement.classList.remove("alert-danger", "alert-info", "alert-success");
                let errorMessage = '';
                for (const key in error.errors) {
                    errorMessage += `${error.errors[key].join(', ')}\n`;
                }
                alertElement.classList.add("alert-danger");
                alertElement.textContent = errorMessage;
            }else{
                console.error('Error:', error);
            }
        });

        return false;
    });

    // localStorage.setItem('sanctum_token', '');

    function get_local_storage_token(){
        return localStorage.getItem('sanctum_token');
    }

    // Define get_token as an async function
    const get_token = async function () {
        const token = get_local_storage_token('sanctum_token');
        if (!token) {
            // Make the login request
            try {
                const response = await fetch('/create_token', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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
}
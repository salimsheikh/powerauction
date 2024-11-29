"use strict"

const loginForm = document.getElementById('loginForm');

let formProcessing = false;

if (loginForm) {
    loginForm.addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent form submission

        if (formProcessing) {
            return false;
        }

        // Get the current form
        const currentForm = event.target;

        // Find the .alert element within the current form
        const alertElement = currentForm.querySelector(".alert");        

        alertElement.classList.remove("alert-danger", "alert-info", "alert-success", "alert-hidden", "hidden");

        alertElement.textContent = lang.please_wait;
        alertElement.classList.add("alert-info");

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const remember_me = document.getElementById('remember_me');

        // Check if it is checked
        const remember = remember_me.checked ? true : false;

        var formValues = { password: email, email: password, remember: remember };      

        const csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const header = {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf_token,
        }       

        fetch(`csutom-login`, {
            method: 'POST',
            headers: header,
            body: JSON.stringify(formValues),
        }).then((response) => {
            logConsole("response 1");
            logConsole(response);
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

            if (data.access_token != "") {

                alertElement.textContent = data.message;
                alertElement.classList.add("alert-success");

                localStorage.setItem('sanctum_token', data.access_token);
                setTimeout(function () {
                    window.location = redirect_url;
                }, 1000);

            } else {
                alertElement.textContent = data;
                alertElement.classList.add("alert-success");
            }
        }).catch((error) => {
            logConsole("response 3");
            logConsole(error);

            alertElement.classList.add("alert-danger");
            if (error.message) {
                alertElement.textContent = error.message;                
            } else {
                alertElement.textContent = 'The server returned an invalid response. Please try again later.';
            }
        });

        return false;

    });
}
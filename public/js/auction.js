"use strict";

// Get the dropdown and form elements
const auction_category_id = document.getElementById('auction_category_id');
if (auction_category_id) {
    // Add an event listener for the change event on the dropdown
    auction_category_id.addEventListener('change', function () {
        // Submit the form when the dropdown value changes        
        document.getElementById('form_auction_category').submit();
    });
}


const start_bidding = document.getElementById('start_bidding');
if(start_bidding){
    start_bidding.addEventListener('submit', function(e){
        e.preventDefault();        

        if (formProcessing) {
            return false;
        }        

        // Get the current form
        const currentForm = e.target;

        // Get all input and textarea elements
        const fields = currentForm.querySelectorAll("input, textarea, select");

        const submitButton = currentForm.querySelector('button[type="submit"], input[type="submit"]');

        let headers = get_ajax_header(true);
        let formData = getFormData(fields);

        submitButton.disabled = true; // Disable the button

        showToast(lang.please_wait, 'info');        
        
        fetch(`${BASE_API_URL}/start-bidding`, {
            method: 'POST',
            headers: headers,
            body: formData,
        }).then((response) => {
            logConsole("response 1");
            if (!response.ok) {
                return response.json().then((error) => {
                    throw error;
                });
            }
            return response.json();
        }).then((data) => {
            logConsole("response 2");           
            
            showToast('Successfully Edited!', 'success',true);

            if(data.bid_id > 0){
                setTimeout(function() {
                    if(data.url){
                        window.location.href = data.url;
                    }                    
                    formProcessing = false;
                }, 500);
            }

            console.log(data);

        }).catch((error) => {
            showToast('Cancelled', 'error',true);
            console.log("response 3", error);        
            if (submitButton) {
                setTimeout(function () {
                    submitButton.disabled = false; // Disable the button
                }, 1000);
            }
            formProcessing = false;
        });

        return false;
    });
}

const startBiddingForm = document.getElementById('startBiddingForm');
if(startBiddingForm){
    startBiddingForm.addEventListener('submit', function(e){

        e.preventDefault();        

        if (formProcessing) {
            return false;
        }

        // Get the current form
        const currentForm = e.target;

        // Get all input and textarea elements
        const fields = currentForm.querySelectorAll("input, textarea, select");

        const submitButton = currentForm.querySelector('button[type="submit"], input[type="submit"]');

        let headers = get_ajax_header(true);

        let formData = getFormData(fields);

        showToast(lang.please_wait, 'info',true);

        formProcessing = true;

        submitButton.disabled = false; // Disable the button        

        fetch(`${BASE_API_URL}/bid`, {
            method: 'POST',
            headers: headers,
            body: formData,
        }).then((response) => {
            logConsole("response 1");

            if (!response.ok) {
                return response.json().then((error) => {
                    throw error;
                });
            }

            return response.json();
        }).then((data) => {
            logConsole("response 2");
            showToast(data.message, 'success', true);
            console.log(data);

            setTimeout(function() {
                location.reload();
                formProcessing = false;
            }, 3000);


        }).catch((error) => {
            console.log(error);
            formProcessing = false;
            showToast(error.message, 'error', true);
        });

        return false;
    });
}

function win_Check(){

    let headers = get_ajax_header(true);
    
    fetch(bidWinURL, {
        method: 'GET',
        headers: headers     
    }).then((response) => {
        logConsole("response 1");

        if (!response.ok) {
            return response.json().then((error) => {
                throw error;
            });
        }

        return response.json();
    }).then((response) => {
        logConsole("response 2");
        
        console.log(response);

        if (response.status === 'success') {
            showToast(response.message, 'success', true);

            setTimeout(function() {
                window.location.href = response.bidding_url;
            }, 3000);
        }


    }).catch((error) => {
        logConsole("response 3");
        logConsole(error);

        if(error?.serverTime){
            serverTimeMs = new Date(error.serverTime).getTime();            
        }else{
            showToast(error.message, 'error', true);
        }

        formProcessing = false;
    });
}

function updateTimer() {
    serverTimeMs = serverTimeMs + 1000;
    const remainingMs = endTimeMs - serverTimeMs;
    
    if (remainingMs <= 0) {
        document.getElementById("auctionBidTimer").innerHTML = "00:00";
        clearInterval(timerInterval);        
        win_Check(); 
    } else {
        const remainingMinutes = Math.floor(remainingMs / 60000);
        const remainingSeconds = Math.floor((remainingMs % 60000) / 1000);
        document.getElementById("auctionBidTimer").innerHTML =
            (remainingMinutes < 10 ? "0" : "") + remainingMinutes + ":" +
            (remainingSeconds < 10 ? "0" : "") + remainingSeconds;
    }
}

// Function to start the timer
function startTimer(startTime, endTime) {
    serverTimeMs = new Date(serverTime).getTime();
    endTimeMs = new Date(endTime).getTime();      
    
    // Update the timer every second
    timerInterval = setInterval(updateTimer, 1000);
    updateTimer(); // Initial call to display immediately
}

// Define the start time and duration
let endTimeMs = 0;
let serverTimeMs = 0;
let timerInterval = null;
let durationMs = 0;

// Start the timer
if(document.getElementById("auctionBidTimer")){
    setTimeout(function(){
        startTimer(sessionStartTime, sessionEndTime)
    },500);    
}
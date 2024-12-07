"use strict";

document.addEventListener("DOMContentLoaded", function () {
    const sliderTrack = document.querySelector(".slider-track");
    const sliderItems = document.querySelectorAll(".slider-item");
    const prevButton = document.getElementById("prev");
    const nextButton = document.getElementById("next");

    let currentIndex = 0;
    const totalSlides = sliderItems.length;

    const updateSliderPosition = () => {

        const offset = -currentIndex * 100; // 100% per slide
        sliderTrack.style.transform = `translateX(${offset}%)`;

        // Get data-id of the current slider-item
        const currentSlide = sliderItems[currentIndex];
        const dataId = currentSlide.getAttribute("data-id");
        // console.log("Current Slide data-id:", dataId);
        document.getElementById('player_id').value = dataId;

        // Show/Hide navigation buttons based on the current index
        prevButton.style.display = currentIndex === 0 ? "none" : "flex";
        nextButton.style.display = currentIndex === totalSlides - 1 ? "none" : "flex";
    };

    prevButton.addEventListener("click", () => {
        if (currentIndex > 0) {
            currentIndex--;
            updateSliderPosition();
        }
    });

    nextButton.addEventListener("click", () => {
        if (currentIndex < totalSlides - 1) {
            currentIndex++;
            updateSliderPosition();
        }
    });

    // Initialize slider
    updateSliderPosition();
});

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
            
            showToast('Successfully Edited!', 'success');

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
            showToast('Cancelled', 'error');
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

        submitButton.disabled = false; // Disable the button

        showToast(lang.please_wait, 'info'); 

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
        });

        return false;
    });
}

function win_Check(){
    var base__url = bidWinURL;
    var bid_id = bidID;

    $.ajax({
        url: base__url + 'index.php/admin/bidding/bid-win/' + bid_id, // The action URL from the form
        type: $(this).attr('method'), // The method type from the form
        data: $(this).serialize(), // Serialize form data
        dataType: 'json', // Expect a JSON response
        success: function(response) {
            // Clear any previous alerts
            $('#scroll-top').next('#floating-top-right').remove();


            // Check if the response status is success or error
            if (response.status === 'success') {
                // Success alert
                var successAlert = `
                    <div id="floating-top-right" class="floating-container">
                        <div class="alert-wrap in animated fadeIn">
                            <div class="alert alert-success" role="alert">
                                <button class="close" type="button"><i class="fa fa-times-circle"></i></button>
                                <div class="media">
                                    <div class="media-left">
                                        <span class="icon-wrap icon-wrap-xs icon-circle alert-icon"><i class="fa fa-check"></i></span>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="alert-title"></h4>
                                        <p class="alert-message">Successfully Edited!</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                $('#scroll-top').after(successAlert);

                // Close button click event
                $('.alert-success .close').click(function() {
                    $(this).closest('#floating-top-right').remove();
                });

                setTimeout(function() {
                    //window.location.href = base__url + 'index.php/admin/bidding';
                }, 3000);

            } else {
                // Error alert
                var errorAlert = `
                    <div id="floating-top-right" class="floating-container">
                        <div class="alert-wrap in animated fadeIn">
                            <div class="alert alert-danger" role="alert">
                                <button class="close" type="button"><i class="fa fa-times-circle"></i></button>
                                <div class="media">
                                    <div class="media-left">
                                        <span class="icon-wrap icon-wrap-xs icon-circle alert-icon"><i class="fa fa-minus"></i></span>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="alert-title"></h4>
                                        <p class="alert-message">Cancelled 3</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                $('#scroll-top').after(errorAlert);

                // Close button click event
                $('.alert-danger .close').click(function() {
                    $(this).closest('#floating-top-right').remove();
                });

            }


        },
        error: function(xhr, status, error) {
            // Clear any previous alerts
            $('#scroll-top').next('#floating-top-right').remove();

            // Error alert
            var errorAlert = `
                <div id="floating-top-right" class="floating-container">
                    <div class="alert-wrap in animated fadeIn">
                        <div class="alert alert-danger" role="alert">
                            <button class="close" type="button"><i class="fa fa-times-circle"></i></button>
                            <div class="media">
                                <div class="media-left">
                                    <span class="icon-wrap icon-wrap-xs icon-circle alert-icon"><i class="fa fa-minus"></i></span>
                                </div>
                                <div class="media-body">
                                    <h4 class="alert-title"></h4>
                                    <p class="alert-message">Cancelled 4 </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
            $('#scroll-top').after(errorAlert);

            // Close button click event
            $('.alert-danger .close').click(function() {
                $(this).closest('#floating-top-right').remove();
            });
        }
    });
}

function updateTimer() {
    const now = new Date(serverTime).getTime();
    const remainingMs = endTimeMs - now;    

    if (remainingMs <= 0) {
        document.getElementById("timer").innerHTML = "00:00";
        clearInterval(timerInterval);
        // alert("Time is up!");  
        win_Check(); 
    } else {
        const remainingMinutes = Math.floor(remainingMs / 60000);
        const remainingSeconds = Math.floor((remainingMs % 60000) / 1000);
        document.getElementById("timer").innerHTML =
            (remainingMinutes < 10 ? "0" : "") + remainingMinutes + ":" +
            (remainingSeconds < 10 ? "0" : "") + remainingSeconds;

            console.log(remainingSeconds);
    }
}

// Function to start the timer
function startTimer(startTime, endTime) {

    console.log(startTime);
    console.log(endTime);

    startTimeMs = new Date(startTime).getTime();
    endTimeMs = new Date(endTime).getTime();  
    
    console.log(startTimeMs);
    console.log(endTimeMs);

    // Update the timer every second
    timerInterval = setInterval(updateTimer, 1000);
    updateTimer(); // Initial call to display immediately
}

// Define the start time and duration
let startTimeMs = 0;
let endTimeMs = 0;
let timerInterval = null;
let durationMs = 0;

// Start the timer
if(document.getElementById("timer")){
    startTimer(sessionStartTime, sessionEndTime);
}
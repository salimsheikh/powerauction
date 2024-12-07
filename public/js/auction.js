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



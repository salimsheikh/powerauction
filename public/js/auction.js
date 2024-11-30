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


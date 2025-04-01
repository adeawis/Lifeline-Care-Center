document.addEventListener('DOMContentLoaded', function () {
    // Form Element
    const caregiverForm = document.getElementById('caregiver-form');
    const popup = document.getElementById('popup');
    const popupMessage = popup.querySelector('.popup-message');
    const popupCloseButton = popup.querySelector('button');
    const popupOverlay = document.getElementById('popup-overlay');

    // Form Validation
    caregiverForm.addEventListener('submit', function (event) {
        let isValid = true;

        // Required fields
        const requiredFields = document.querySelectorAll('#caregiver-form [required]');

        // Clear previous error messages
        document.querySelectorAll('.message.error').forEach(msg => msg.remove());

        // Check required fields
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.insertAdjacentHTML('afterend', '<div class="message error">This field is required.</div>');
            }
        });

        // Payment validation
        const cardNumberField = caregiverForm.querySelector('input[name="card_number"]');
        const expMonthField = caregiverForm.querySelector('input[name="exp_month"]');
        const expYearField = caregiverForm.querySelector('input[name="exp_year"]');
        const cvvField = caregiverForm.querySelector('input[name="cvv"]');

        // Card Number Validation
        if (cardNumberField && !/^\d{16}$/.test(cardNumberField.value.trim())) {
            isValid = false;
            cardNumberField.insertAdjacentHTML('afterend', '<div class="message error">Please enter a valid 16-digit card number.</div>');
        }

        // Expiration Month Validation
        if (expMonthField && (!/^\d{1,2}$/.test(expMonthField.value.trim()) || parseInt(expMonthField.value.trim()) < 1 || parseInt(expMonthField.value.trim()) > 12)) {
            isValid = false;
            expMonthField.insertAdjacentHTML('afterend', '<div class="message error">Please enter a valid expiration month (1-12).</div>');
        }

        // Expiration Year Validation
        const currentYear = new Date().getFullYear();
        const enteredYear = parseInt(expYearField.value.trim());
        if (expYearField && (!/^\d{2,4}$/.test(expYearField.value.trim()) || (enteredYear < currentYear && enteredYear < currentYear % 100))) {
            isValid = false;
            expYearField.insertAdjacentHTML('afterend', '<div class="message error">Please enter a valid expiration year.</div>');
        }

        // CVV Validation
        if (cvvField && !/^\d{3,4}$/.test(cvvField.value.trim())) {
            isValid = false;
            cvvField.insertAdjacentHTML('afterend', '<div class="message error">Please enter a valid CVV code.</div>');
        }

        // If validation fails, prevent form submission
        if (!isValid) {
            event.preventDefault();
        } else {
            // If validation passes, show the popup
            event.preventDefault(); // Prevent default submission for popup
            popupMessage.textContent = "Payment and Booking Submitted Successfully!";
            openPopup();
        }
    });

    // Open Popup
    function openPopup() {
        popup.style.display = 'block';
        popupOverlay.style.display = 'block';
    }

    // Close Popup and Submit Form
    popupCloseButton.addEventListener('click', function () {
        popup.style.display = 'none';
        popupOverlay.style.display = 'none';
        caregiverForm.submit(); // Submit the form after closing the popup
    });

    // Close Popup Overlay
    popupOverlay.addEventListener('click', function () {
        popup.style.display = 'none';
        popupOverlay.style.display = 'none';
        caregiverForm.submit(); // Submit the form after closing the overlay
    });
});

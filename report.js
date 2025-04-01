// Calculate dates and amounts
document.addEventListener('DOMContentLoaded', function() {
    // Format currency
    function formatCurrency(amount) {
        return new Intl.NumberFormat('en-LK', {
            style: 'currency',
            currency: 'LKR'
        }).format(amount);
    }

    // Print functionality
    document.querySelector('.print-btn').addEventListener('click', function() {
        window.print();
    });

    // Validate form before printing
    function validateForm() {
        const requiredFields = document.querySelectorAll('.value');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.textContent.trim()) {
                isValid = false;
                field.style.backgroundColor = '#ffe6e6';
            } else {
                field.style.backgroundColor = 'transparent';
            }
        });

        return isValid;
    }

    // Add event listener for any dynamic calculations
    const paymentInputs = document.querySelectorAll('.payment-details input');
    paymentInputs.forEach(input => {
        input.addEventListener('change', function() {
            updateTotals();
        });
    });
});

// Function to update totals when values change
function updateTotals() {
    const dailyRate = parseFloat(document.querySelector('[data-daily-rate]').value) || 0;
    const days = parseInt(document.querySelector('[data-days]').value) || 0;
    const advancePaid = parseFloat(document.querySelector('[data-advance]').value) || 0;

    const totalAmount = dailyRate * days;
    const balance = totalAmount - advancePaid;

    document.querySelector('[data-total]').textContent = formatCurrency(totalAmount);
    document.querySelector('[data-balance]').textContent = formatCurrency(balance);
}

// Export to PDF functionality (if needed)
function exportToPDF() {
    window.print();
}
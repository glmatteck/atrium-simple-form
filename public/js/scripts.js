"use strict";

// Hide URL parameters on page load
// (no longer needed, but keeing for the record)
// window.addEventListener('load', function() {    
//     const url = new URL(window.location.href);

//     if (this.window.history.replaceState) {
//         // Remove URL parameters by replacing the current history state
//         url.search = '';
//         window.history.replaceState({}, document.title, url.toString());
//     }
// });

// This script handles the auto-formatting of numeric inputs for loan and value fields,
// calculates the Loan-to-Value (LTV) ratio, and formats currency cells in a table.
document.addEventListener('DOMContentLoaded', function() {
    const loanInput = document.getElementById('loan');
    const valueInput = document.getElementById('value');
    const ltvInput = document.getElementById('ltv');

    // Auto-format numeric entry for loan and value fields
    function setupAutoDecimal(input) {
        input.addEventListener('input', function(e) {
            let digits = input.value.replace(/\D/g, '');
            while (digits.length < 3) digits = '0' + digits;
            let formatted = digits.replace(/^(\d+)(\d{2})$/, '$1.$2');
            formatted = formatted.replace(/^0*(\d*\.\d{2})$/, (m, g1) => g1.startsWith('.') ? '0' + g1 : g1);
            input.value = formatted;
        });
    }

    if (loanInput) setupAutoDecimal(loanInput);
    if (valueInput) setupAutoDecimal(valueInput);

    function updateLTV() {
        const loan = parseFloat(loanInput.value);
        const value = parseFloat(valueInput.value);

        if (!isNaN(loan) && !isNaN(value) && value !== 0) {
            const ltv = ((loan / value) * 100).toFixed(2);
            ltvInput.value = ltv + '%';
        } else {
            ltvInput.value = 'N/A';
        }
    }

    if (loanInput && valueInput && ltvInput) {
        loanInput.addEventListener('input', updateLTV);
        valueInput.addEventListener('input', updateLTV);
        updateLTV();
    }
});

const currencyCells = document.querySelectorAll('table td.currency');
currencyCells.forEach(cell => {
    const formattedValue = Number(cell.textContent).toLocaleString('en-US', { style: 'currency', currency: 'USD' });
    cell.textContent = formattedValue;
});

// Handle the message display
const messageSlotElem = document.querySelector('.message');
if (messageSlotElem) {
    const loc = window.location;

    setTimeout(() => {
        messageSlotElem.style.display = 'none';
        // document.querySelector('.message').innerHTML = '';
        if (messageSlotElem)
             messageSlotElem.innerHTML = '';
            
        if (loc.pathname.length  > 1 && loc.pathname !== '/') {
            // If the current path is not the root, redirect to the root
            window.location.href = '/';
        }
    }, 2000); // Hide message after 2 seconds
}
export function applyCustomValidation(formId, inputId, message) {
    const form = document.getElementById(formId);
    const input = document.getElementById(inputId);

    if (!form || !input) {
        console.error('Form or input element not found.');
        return;
    }

    form.addEventListener('submit', function(event) {
        if (!input.value) {
            input.setCustomValidity(message);
            input.reportValidity();
            event.preventDefault();
        } else {
            input.setCustomValidity('');
        }
    });

    input.addEventListener('input', function() {
        input.setCustomValidity('');
    });

    input.addEventListener('invalid', function() {
        if (!input.value) {
            input.setCustomValidity(message);
        } else {
            input.setCustomValidity('');
        }
    });
}

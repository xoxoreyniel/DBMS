documedocument.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('reset-password-form');
    const errorMessage = document.getElementById('error-message');
    const successMessage = document.getElementById('success-message');

    // Function to clear previous messages and reset input styles
    function clearMessages() {
        errorMessage.textContent = '';
        successMessage.textContent = '';
        const inputs = form.querySelectorAll('input');
        inputs.forEach(input => input.classList.remove('invalid'));
    }

    // Handle form submission
    form.addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent the default form submission
        clearMessages(); // Clear any previous messages

        const formData = new FormData(form);
        const xhr = new XMLHttpRequest();
        xhr.open('POST', form.action, true); // Send the data to the form action URL

        xhr.onload = function () {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText); // Parse the JSON response

                    if (response.success) {
                        successMessage.textContent = response.message; // Show success message
                        setTimeout(() => {
                            window.location.href = 'login.html'; // Redirect to login after 1 second
                        }, 1000);
                    } else {
                        errorMessage.textContent = response.message; // Show error message
                    }
                } catch (e) {
                    errorMessage.textContent = 'Invalid server response. Please try again.'; // If the response isn't valid JSON
                }
            } else {
                errorMessage.textContent = 'An error occurred, please try again.'; // Show error message if request failed
            }
        };

        xhr.send(formData); // Send the form data to the server
    });
});

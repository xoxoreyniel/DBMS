document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('loginForm');
    const errorMessage = document.getElementById('error-message');
    const successMessage = document.getElementById('success-message');

    // Function to clear messages
    function clearMessages() {
        errorMessage.textContent = '';
        successMessage.textContent = '';
    }

    // Handle form submission
    form.addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent normal form submission
        clearMessages(); // Clear any previous messages

        let valid = true;
        const usernameEmail = document.getElementById('usernameEmail').value.trim();
        const password = document.getElementById('password').value.trim();

        // Validate username or email
        if (usernameEmail === '') {
            valid = false;
            errorMessage.textContent = 'Username or Email is required.';
        }

        // Validate password
        if (password === '') {
            valid = false;
            errorMessage.textContent += '\nPassword is required.';
        }

        if (valid) {
            // Create a FormData object to send the form data
            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true); // Send the form data to login.php

            // When the request is complete
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const response = xhr.responseText.trim();

                    // If login is successful, redirect the user
                    if (response === 'Logged in successfully!') {
                        successMessage.textContent = response;
                        window.location.href = "about.html"; // Redirect to the 'about.html' page
                    } else {
                        errorMessage.textContent = response; // Show error message returned from PHP
                    }
                } else {
                    errorMessage.textContent = 'An error occurred, please try again.'; // General error message
                }
            };

            // Send the form data to the PHP script
            xhr.send(formData);
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('signupForm');
    const errorMessage = document.getElementById('error-message');
    const successMessage = document.getElementById('success-message');

    // Function to clear messages and reset styles
    function clearMessages() {
        errorMessage.textContent = ''; // Clear previous error messages
        successMessage.textContent = ''; // Clear success message
        // Reset input field styles
        const inputs = form.querySelectorAll('input');
        inputs.forEach(input => input.classList.remove('invalid'));
    }

    // Handle form submission
    form.addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent normal form submission
        clearMessages(); // Clear any previous messages

        let valid = true;
        let errors = []; // Initialize an array to collect error messages

        // Validate username
        const usernameInput = document.getElementById('username');
        if (usernameInput.value.trim() === '') {
            valid = false;
            errors.push('Username is required.');
            usernameInput.classList.add('invalid'); // Add red border to invalid field
        }

        // Validate email
        const emailInput = document.getElementById('email');
        const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (!emailPattern.test(emailInput.value.trim())) {
            valid = false;
            errors.push('Please enter a valid email address.');
            emailInput.classList.add('invalid'); // Add red border to invalid field
        }

        // Validate password
        const passwordInput = document.getElementById('password');
        if (passwordInput.value.trim().length < 8) {
            valid = false;
            errors.push('Password must be at least 8 characters long.');
            passwordInput.classList.add('invalid'); // Add red border to invalid field
        }

        // Validate confirm password
        const confirmPasswordInput = document.getElementById('confirmPassword');
        if (passwordInput.value !== confirmPasswordInput.value) {
            valid = false;
            errors.push('Passwords do not match.');
            confirmPasswordInput.classList.add('invalid'); // Add red border to invalid field
        }

        // If validation failed, display all error messages
        if (!valid) {
            errorMessage.innerHTML = errors.join('<br>'); // Join errors with line breaks
        }

        // If validation passed, send the form data via AJAX
        if (valid) {
            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true); // Send the form data to signup.php

            // When the request is complete
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const response = xhr.responseText.trim();
                    if (response === 'New account created successfully!') {
                        successMessage.textContent = response; // Show success message
                        form.reset(); // Reset the form on success
                    } else {
                        errorMessage.textContent = response; // Show error message from PHP
                    }
                } else {
                    errorMessage.textContent = 'An error occurred, please try again.'; // General error
                }
            };

            xhr.send(formData); // Send the form data
        }
    });
});

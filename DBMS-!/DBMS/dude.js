document.addEventListener('DOMContentLoaded', function () {
    const signupForm = document.getElementById('signupForm');
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const errorMessage = document.getElementById('error-message');
    const successMessage = document.getElementById('success-message');

    // Function to clear error messages
    function clearErrors() {
        document.querySelectorAll('.error').forEach(errorElem => errorElem.textContent = '');
        errorMessage.textContent = ''; // Clear general error message
    }

    // Function to show a specific error message
    function showError(message) {
        errorMessage.textContent = message;
    }

    // Handle form submission
    signupForm.addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent the form from submitting the traditional way

        clearErrors(); // Clear previous errors

        let valid = true;
        
        // Validate username
        if (usernameInput.value.trim() === '') {
            valid = false;
            document.getElementById('usernameError').textContent = 'Username is required.';
        }

        // Validate email
        const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (emailInput.value.trim() === '') {
            valid = false;
            document.getElementById('emailError').textContent = 'Email is required.';
        } else if (!emailPattern.test(emailInput.value.trim())) {
            valid = false;
            document.getElementById('emailError').textContent = 'Please enter a valid email address.';
        }

        // Validate password
        if (passwordInput.value.trim() === '') {
            valid = false;
            document.getElementById('passwordError').textContent = 'Password is required.';
        }

        // Validate confirm password
        if (confirmPasswordInput.value.trim() === '') {
            valid = false;
            document.getElementById('confirmPasswordError').textContent = 'Please confirm your password.';
        } else if (passwordInput.value !== confirmPasswordInput.value) {
            valid = false;
            document.getElementById('confirmPasswordError').textContent = 'Passwords do not match.';
        }

        // If all validations pass, submit the form via AJAX
        if (valid) {
            // Create the form data
            const formData = new FormData(signupForm);
            
            // Perform AJAX request to submit the form data to the PHP script
            const xhr = new XMLHttpRequest();
            xhr.open('POST', signupForm.action, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const response = xhr.responseText.trim();
                    
                    if (response === 'New account created successfully!') {
                        successMessage.style.display = 'block';
                        signupForm.reset();
                    } else {
                        showError(response); // Show the PHP error message (like username/email already taken)
                    }
                } else {
                    showError('An error occurred while processing your request. Please try again.');
                }
            };
            xhr.send(formData);
        }
    });
});

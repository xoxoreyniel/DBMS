document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("reset-password-form");
    const popupMessage = document.getElementById("popup-message");

    form.addEventListener("submit", function (e) {
        e.preventDefault(); // Prevent default form submission

        const formData = new FormData(form);
        console.log("Form data: ", formData); // Log form data for debugging

        const xhr = new XMLHttpRequest();
        xhr.open("POST", form.action, true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = xhr.responseText.trim();
                
                // Display the response message in the popup div
                popupMessage.textContent = response;
                popupMessage.style.display = "block"; // Make the popup message visible

                // Redirect if success message
                if (response.includes("Password reset successfully")) {
                    setTimeout(() => {
                        window.location.href = "login.html"; // Redirect after 2 seconds
                    }, 2000); 
                } else {
                    // Hide popup after 3 seconds for error messages
                    setTimeout(() => {
                        popupMessage.style.display = "none";
                    }, 3000);
                }
            } else {
                popupMessage.textContent = "An error occurred, please try again.";
                popupMessage.style.display = "block";
                setTimeout(() => {
                    popupMessage.style.display = "none";
                }, 3000);
            }
        };

        xhr.send(formData);
    });
});

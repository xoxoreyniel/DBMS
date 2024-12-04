document.addEventListener('DOMContentLoaded', function() {
    const usernameElement = document.getElementById('username');
    const logoutBtn = document.getElementById('logout-btn');

    // Check if a user is logged in by reading the localStorage or cookies
    const username = localStorage.getItem('username'); // Assuming you stored it in localStorage

    // If the user is logged in, display their username, otherwise show "Guest"
    if (username) {
        usernameElement.textContent = `Loggen in as ${username}`;
    } else {
        usernameElement.textContent = 'Browsing as guest';
    }

    // Log out functionality (clear the username from localStorage)
    logoutBtn.addEventListener('click', function() {
        localStorage.removeItem('username'); // Remove the username from localStorage
        window.location.href = 'logout.php'; // Redirect to logout page (if PHP is needed for session handling)
    });
});

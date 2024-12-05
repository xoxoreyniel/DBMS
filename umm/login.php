<?php
$con = mysqli_connect('localhost', 'root', '', 'datababes');

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ensure the required fields are present
    if (empty($_POST["usernameEmail"])) {
        echo "Enter your username or email";
        exit;
    }

    if (empty($_POST["password"])) {
        echo "Enter your password";
        exit;
    }

    // Sanitize and fetch user input
    $usernameEmail = $_POST["usernameEmail"];
    $password = $_POST["password"];

    // Query to check if username or email matches
    $sql = sprintf("SELECT * FROM users WHERE username = '%s' OR email = '%s'", 
                   mysqli_real_escape_string($con, $usernameEmail),
                   mysqli_real_escape_string($con, $usernameEmail));

    $result = mysqli_query($con, $sql);
    
    // Check if a matching user is found
    $user = mysqli_fetch_assoc($result);
    
    if ($user) {
        // Verify the password if the user exists
        if (password_verify($password, $user["password"])) {
            // Start the session
            session_start();
            session_regenerate_id();
            $_SESSION["user_id"] = $user["user_ID"]; // Store user ID in session
            
            echo "Logged in successfully!"; // Respond back to JavaScript
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "Account not found.";
    }
}

mysqli_close($con);
?>

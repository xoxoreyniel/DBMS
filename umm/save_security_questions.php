<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.html"); // Redirect to login if no active session
    exit;
}

// Database connection
$con = mysqli_connect('localhost', 'root', '', 'datababes');

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Validate and sanitize user inputs
$fave_color = isset($_POST["fave_color"]) ? trim($_POST["fave_color"]) : '';
$first_recipe = isset($_POST["first_recipe"]) ? trim($_POST["first_recipe"]) : '';

if (empty($fave_color) || empty($first_recipe)) {
    echo "Both security questions must be answered.";
    exit;
}

$user_id = $_SESSION["user_id"]; // Retrieve user ID from the session

// Insert security questions into the database
$sql = "INSERT INTO password_ques (user_ID, color_ques, recipe_ques) VALUES (?, ?, ?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("iss", $user_id, $fave_color, $first_recipe);

if ($stmt->execute()) {
    // Log out the user by clearing the session
    session_unset();
    session_destroy();

    // Redirect to login.html
    header("Location: login.html");
    exit;
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
mysqli_close($con);
?>

<?php
// Start the session to access logged-in user data
session_start();

// Database connection
$con = mysqli_connect('localhost', 'root', '', 'datababes'); // Replace with your DB credentials
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get query parameters
$query = isset($_GET['query']) ? $_GET['query'] : '';
$difficulty = isset($_GET['difficulty']) ? $_GET['difficulty'] : [];
$dishTypes = isset($_GET['dish_types']) ? $_GET['dish_types'] : [];

// Build the SQL query based on filters
$sql = "SELECT * FROM recipes WHERE 1";  // '1' is a placeholder to always make the WHERE clause valid

// Search using LIKE
if ($query) {
    $sql .= " AND recipename LIKE '%" . mysqli_real_escape_string($con, $query) . "%'";
}

if (!empty($difficulty)) {
    // Sanitize each value in the array
    $difficulty = array_map(function($level) use ($con) {
        return "'" . mysqli_real_escape_string($con, $level) . "'";
    }, $difficulty);
    
    $sql .= " AND difficulty IN (" . implode(",", $difficulty) . ")";
}

if (!empty($dishTypes)) {
    // Sanitize each value in the array
    $dishTypes = array_map(function($type) use ($con) {
        return "'" . mysqli_real_escape_string($con, $type) . "'";
    }, $dishTypes);
    
    $sql .= " AND dish_types IN (" . implode(",", $dishTypes) . ")";
}

// Execute the query
$result = mysqli_query($con, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($con));
}

$recipes = [];
while ($row = mysqli_fetch_assoc($result)) {
    $recipes[] = $row;  // Add the recipe to the array
}

// Close the database connection
mysqli_close($con);

// Return the recipes array as a JSON object
header('Content-Type: application/json');
echo json_encode($recipes);
?>

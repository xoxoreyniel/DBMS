<?php
// Database connection code
$con = mysqli_connect('localhost', 'root', '', 'datababes');

// Check if the connection was successful
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the POST data
$username = isset($_POST['username']) ? $_POST['username'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$confirmPassword = isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : '';

// Check if the email or username already exists in the database
$sql_check_user = "SELECT * FROM users WHERE USERNAME = ? OR EMAIL = ?";
$stmt_check = mysqli_prepare($con, $sql_check_user);
mysqli_stmt_bind_param($stmt_check, 'ss', $username, $email);
mysqli_stmt_execute($stmt_check);
$result = mysqli_stmt_get_result($stmt_check);

// If any record is found with the same username or email, show an error
if (mysqli_num_rows($result) > 0) {
    // Check if username is taken
    $row = mysqli_fetch_assoc($result);
    if ($row['USERNAME'] === $username) {
        echo "Username already used!";
        exit;
    }
    // Check if email is taken
    if ($row['EMAIL'] === $email) {
        echo "Email already used!";
        exit;
    }
}

// Validate that password and confirmPassword are not empty and match
if (empty($password) || empty($confirmPassword)) {
    echo "All fields are required!";
    exit;
}

if ($password !== $confirmPassword) {
    echo "Passwords do not match!";
    exit;
}

// Proceed to insert the new user into the database
$sql_insert = "INSERT INTO users (USERNAME, EMAIL, PASSWORD) VALUES (?, ?, ?)";
$stmt_insert = mysqli_prepare($con, $sql_insert);
$password_hashed = password_hash($password, PASSWORD_BCRYPT);  // Hash password for security
mysqli_stmt_bind_param($stmt_insert, 'sss', $username, $email, $password_hashed);

// Execute the insert query
if (mysqli_stmt_execute($stmt_insert)) {
    echo "New account created successfully!";
} else {
    echo "Error: " . mysqli_error($con);
}

// Close the connection
mysqli_close($con);
?>

<?php
$con = mysqli_connect('localhost', 'root', '', 'datababes');

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (empty($_POST["username"])) {
    echo "Username is required"; // Send message back as AJAX response
    exit;
}

if (empty($_POST["email"])) {
    echo "Email is required"; // Send message back as AJAX response
    exit;
}

if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    echo "Email is not valid"; // Send message back as AJAX response
    exit;
}

if (strlen($_POST["password"]) < 8) {
    echo "Password must be at least 8 characters"; // Send message back as AJAX response
    exit;
}

if ($_POST["password"] !== $_POST["confirmPassword"]) {
    echo "Passwords do not match"; // Send message back as AJAX response
    exit;
}

$username = $_POST["username"];
$email = $_POST["email"];

// Check if username or email already exists
$checkQuery = "SELECT * FROM users WHERE username = ? OR email = ?";
$stmtCheck = $con->prepare($checkQuery);
$stmtCheck->bind_param("ss", $username, $email);
$stmtCheck->execute();
$result = $stmtCheck->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['username'] === $username) {
            echo "Username already used!"; // Send message back as AJAX response
            exit;
        }
        if ($row['email'] === $email) {
            echo "Email already used!"; // Send message back as AJAX response
            exit;
        }
    }
}

$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("sss", $username, $email, $password_hash);

if ($stmt->execute()) {
    echo "New account created successfully! You can now log in."; // Send success message back as AJAX response
} else {
    echo "Error: " . $stmt->error; // Send error message back as AJAX response
}

$stmt->close();
mysqli_close($con);
?>

<?php
session_start(); // Start a session at the beginning

$con = mysqli_connect('localhost', 'root', '', 'datababes');

if (!$con) {
    echo json_encode(["success" => false, "message" => "Connection failed: " . mysqli_connect_error()]);
    exit;
}

if (empty($_POST["username"])) {
    echo json_encode(["success" => false, "message" => "Username is required"]);
    exit;
}

if (empty($_POST["email"])) {
    echo json_encode(["success" => false, "message" => "Email is required"]);
    exit;
}

if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "message" => "Email is not valid"]);
    exit;
}

if (strlen($_POST["password"]) < 8) {
    echo json_encode(["success" => false, "message" => "Password must be at least 8 characters"]);
    exit;
}

if ($_POST["password"] !== $_POST["confirmPassword"]) {
    echo json_encode(["success" => false, "message" => "Passwords do not match"]);
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
            echo json_encode(["success" => false, "message" => "Username already used!"]);
            exit;
        }
        if ($row['email'] === $email) {
            echo json_encode(["success" => false, "message" => "Email already used!"]);
            exit;
        }
    }
}

$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("sss", $username, $email, $password_hash);

if ($stmt->execute()) {
    $_SESSION["user_id"] = $stmt->insert_id; 
    echo json_encode(["success" => true, "message" => "New account created successfully!"]);
} else {
    echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
}

$stmt->close();
mysqli_close($con);
?>

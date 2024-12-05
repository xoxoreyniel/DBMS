<?php
$con = mysqli_connect('localhost', 'root', '', 'datababes');

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}


$username_email = isset($_POST["username_email"]) ? trim($_POST["username_email"]) : '';
$fave_color = isset($_POST["fave_color"]) ? trim($_POST["fave_color"]) : '';
$first_recipe = isset($_POST["first_recipe"]) ? trim($_POST["first_recipe"]) : '';
$new_password = isset($_POST["new_password"]) ? trim($_POST["new_password"]) : '';
$confirm_password = isset($_POST["confirm_password"]) ? trim($_POST["confirm_password"]) : '';


if (empty($username_email) || empty($fave_color) || empty($first_recipe) || empty($new_password) || empty($confirm_password)) {
    echo "All fields are required.";
    exit;
}

if ($new_password !== $confirm_password) {
    echo "Passwords do not match.";
    exit;
}

if (strlen($new_password) < 8) {
    echo "Password must be at least 8 characters long.";
    exit;
}


$sql_user = "SELECT * FROM users WHERE username = ? OR email = ?";
$stmt_user = $con->prepare($sql_user);
$stmt_user->bind_param("ss", $username_email, $username_email);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows === 0) {
    echo "User not found.";
    exit;
}

$user = $result_user->fetch_assoc();
$user_id = $user["user_ID"];


$sql_security = "SELECT * FROM password_ques WHERE user_ID = ? AND color_ques = ? AND recipe_ques = ?";
$stmt_security = $con->prepare($sql_security);
$stmt_security->bind_param("iss", $user_id, $fave_color, $first_recipe);
$stmt_security->execute();
$result_security = $stmt_security->get_result();

if ($result_security->num_rows === 0) {
    echo "Security answers do not match.";
    exit;
}


$new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
$sql_update = "UPDATE users SET password = ? WHERE user_ID = ?";
$stmt_update = $con->prepare($sql_update);
$stmt_update->bind_param("si", $new_password_hash, $user_id);

if ($stmt_update->execute()) {
    echo "Password reset successfully. You can now log in.";
    header("Location: login.html"); // Redirect to login page after successful reset
    exit;
} else {
    echo "Error: " . $stmt_update->error;
}

$stmt_user->close();
$stmt_security->close();
$stmt_update->close();
mysqli_close($con);
?>

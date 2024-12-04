<?php
// Start the session to check if the user is logged in
session_start();

// Database connection
$con = mysqli_connect('localhost', 'root', '', 'datababes');

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the user is logged in and has an order
if (!isset($_SESSION['user_id']) || !isset($_POST['order_details'])) {
    echo "You must be logged in and have an order to proceed.";
    exit();
}

$userID = $_SESSION['user_id']; // User ID from session
$orderDetails = json_decode($_POST['order_details'], true); // Order details from POST
$totalAmount = 0;

// Calculate total order amount
foreach ($orderDetails as $item) {
    $totalAmount += $item['total_price'];
}

// Verify password and confirm order
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if password field is set
    if (empty($_POST["password"])) {
        echo "Please enter your password.";
        exit();
    }

    $password = $_POST["password"];

    // Query the database to check for the user's password
    $sql = "SELECT * FROM users WHERE user_ID = '$userID'";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Verify the password using password_verify
        if (password_verify($password, $user['password'])) {
            // Password is correct, insert order into the database
            // Insert the order into the orders table
            $insertOrder = "INSERT INTO orders (user_ID, total_amount, password_verified) 
                            VALUES ('$userID', '$totalAmount', 1)";  // Assuming the password is verified

            if (mysqli_query($con, $insertOrder)) {
                $orderID = mysqli_insert_id($con);  // Get the last inserted order ID

                // Now insert the order items into the order_items table
                foreach ($orderDetails as $item) {
                    $productName = $item['product_name'];
                    $quantity = $item['quantity'];
                    $price = $item['price'];
                    $totalPrice = $item['total_price'];

                    $insertItem = "INSERT INTO order_items (order_ID, product_name, quantity, price, total_price) 
                                    VALUES ('$orderID', '$productName', '$quantity', '$price', '$totalPrice')";

                    if (!mysqli_query($con, $insertItem)) {
                        echo "Error inserting item: " . mysqli_error($con);
                    }
                }

                echo "Order placed successfully! Total: ₱" . $totalAmount;
            } else {
                echo "Error placing order: " . mysqli_error($con);
            }
        } else {
            echo "Incorrect password. Please try again.";
        }
    } else {
        echo "User not found.";
    }
}

mysqli_close($con);
?>








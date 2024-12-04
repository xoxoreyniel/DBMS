<?php
// Start the session to store order details
session_start();

// Database connection
$con = mysqli_connect('localhost', 'root', '', 'datababes');

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize variables
    $orderDetails = [];
    $totalAmount = 0;
    $userID = $_SESSION['user_id']; // Assuming the user is logged in, stored in session

    // Loop through the products and calculate the total
    if (isset($_POST['microwave_quantity']) && $_POST['microwave_quantity'] > 0) {
        $microwaveQuantity = intval($_POST['microwave_quantity']);
        $microwavePrice = intval($_POST['microwave_price']);
        $microwaveTotal = $microwaveQuantity * $microwavePrice;

        $orderDetails[] = [
            'product_id' => $_POST['microwave_product_id'],
            'quantity' => $microwaveQuantity,
            'total' => $microwaveTotal
        ];
        $totalAmount += $microwaveTotal;
    }

    if (isset($_POST['stove_quantity']) && $_POST['stove_quantity'] > 0) {
        $stoveQuantity = intval($_POST['stove_quantity']);
        $stovePrice = intval($_POST['stove_price']);
        $stoveTotal = $stoveQuantity * $stovePrice;

        $orderDetails[] = [
            'product_id' => $_POST['stove_product_id'],
            'quantity' => $stoveQuantity,
            'total' => $stoveTotal
        ];
        $totalAmount += $stoveTotal;
    }

    if (isset($_POST['baking_kit_quantity']) && $_POST['baking_kit_quantity'] > 0) {
        $bakingKitQuantity = intval($_POST['baking_kit_quantity']);
        $bakingKitPrice = intval($_POST['baking_kit_price']);
        $bakingKitTotal = $bakingKitQuantity * $bakingKitPrice;

        $orderDetails[] = [
            'product_id' => $_POST['baking_kit_product_id'],
            'quantity' => $bakingKitQuantity,
            'total' => $bakingKitTotal
        ];
        $totalAmount += $bakingKitTotal;
    }

    if (isset($_POST['blender_quantity']) && $_POST['blender_quantity'] > 0) {
        $blenderQuantity = intval($_POST['blender_quantity']);
        $blenderPrice = intval($_POST['blender_price']);
        $blenderTotal = $blenderQuantity * $blenderPrice;

        $orderDetails[] = [
            'product_id' => $_POST['blender_product_id'],
            'quantity' => $blenderQuantity,
            'total' => $blenderTotal
        ];
        $totalAmount += $blenderTotal;
    }

    // Insert the order into the orders table
    $sqlOrder = "INSERT INTO orders (user_ID, total_amount) VALUES ('$userID', '$totalAmount')";
    if (mysqli_query($con, $sqlOrder)) {
        // Get the last inserted order ID
        $orderID = mysqli_insert_id($con);

        // Loop through each item and insert it into the order_items table
        foreach ($orderDetails as $item) {
            $productID = $item['product_id'];
            $quantity = $item['quantity'];
            $totalPrice = $item['total'];

            $sqlItem = "INSERT INTO order_items (order_ID, product_name, quantity, price, total_price) 
                        VALUES ('$orderID', '$productID', '$quantity', '$totalPrice', '$totalPrice')";
            mysqli_query($con, $sqlItem);
        }

        // Store order details in session
        $_SESSION['order'] = [
            'details' => $orderDetails,
            'total_amount' => $totalAmount
        ];

        // Redirect to a confirmation page
        header("Location: checkout.html");
        exit();
    } else {
        echo "Error: " . $sqlOrder . "<br>" . mysqli_error($con);
    }
} else {
    // If the form is not submitted, redirect back to the purchase page
    header("Location: purchase.html");
    exit();
}
?>


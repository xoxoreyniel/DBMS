<?php
// Start the session to access order details
session_start();

// Check if the order details are set in the session
if (!isset($_SESSION['order'])) {
    // If not set, redirect to the purchase page
    header("Location: purchase.html");
    exit();
}

// Retrieve order details from the session
$order = $_SESSION['order'];
$orderDetails = $order['details'];
$totalAmount = $order['total_amount'];

// Clear the order from the session after displaying
unset($_SESSION['order']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" type="text/css" href="confirmation.css"> <!-- Optional CSS for styling -->
</head>
<body>
    <div class="confirmation-container">
        <h1>Order Confirmation</h1>
        <h2>Thank you for your order!</h2>
        <h3>Your Order Details:</h3>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderDetails as $item): ?>
                    <tr>
                        <td>Product ID: <?php echo htmlspecialchars($item['product_id']); ?></td>
                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                        <td>₱<?php echo htmlspecialchars($item['total'] / $item['quantity']); ?></td>
                        <td>₱<?php echo htmlspecialchars($item['total']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h3>Total Amount: ₱<?php echo htmlspecialchars($totalAmount); ?></h3>
        <p>Your order will be processed shortly. You will receive a confirmation email soon.</p>
        <a href="purchase.html">Continue Shopping</a>
    </div>
</body>
</html>
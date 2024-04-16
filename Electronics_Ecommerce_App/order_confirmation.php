<?php
session_start();
include 'db.php'; // Include your database connection file

// Check if the user is logged in and has an order ID stored
if (!isset($_SESSION['user_id']) || !isset($_SESSION['order_id'])) {
    header('Location: index.php'); // Redirect to home if no order information is found
    exit;
}

$orderId = $_SESSION['order_id'];

// Fetch order details from the database
$stmt = $conn->prepare("SELECT od.product_id, p.name, od.quantity, od.price FROM order_details od JOIN products p ON od.product_id = p.product_id WHERE od.order_id = ?");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$result = $stmt->get_result();

// Fetch order summary
$orderSummary = $conn->prepare("SELECT total_price, created_at FROM orders WHERE order_id = ?");
$orderSummary->bind_param("i", $orderId);
$orderSummary->execute();
$orderSummaryResult = $orderSummary->get_result();
$orderInfo = $orderSummaryResult->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Order Confirmation</title>
</head>
<body>
<div class="container mt-5">
    <h2>Order Confirmation</h2>
    <p>Thank you for your purchase!</p>
    <h4>Order Summary</h4>
    <p>Order Placed: <?= date("F j, Y, g:i a", strtotime($orderInfo['created_at'])) ?></p>
    <p>Total Price: $<?= number_format($orderInfo['total_price'], 2) ?></p>
    <ul>
        <?php while ($item = $result->fetch_assoc()): ?>
            <li><?= htmlspecialchars($item['name']) ?> - $<?= number_format($item['price'], 2) ?> x <?= $item['quantity'] ?></li>
        <?php endwhile; ?>
    </ul>
    <a href="index.php" class="btn btn-primary">Continue Shopping</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

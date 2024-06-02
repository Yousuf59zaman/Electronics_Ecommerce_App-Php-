<?php
session_start();
include 'db.php'; // Include your database connection file

// Redirect user if the cart is empty
if (empty($_SESSION['cart'])) {
    header('Location: index.php'); // Redirect to products page if the cart is empty
    exit;
}

// Initialize total price
$totalPrice = 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['place_order'])) {
     // Check if user_id is set in the session
     if (!isset($_SESSION['user_id'])) {
        echo "User is not logged in.";
        exit; // Stop script execution if user_id is not set
    }

    $userId = $_SESSION['user_id']; // Retrieve user id from session
    $address = $_POST['address']; // Retrieve shipping address from form
   
    // Calculate total price
    foreach ($_SESSION['cart'] as $id => $item) {
        $totalPrice += $item['price'] * $item['quantity'];
    }

    // Insert the order into the database
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
    $stmt->bind_param("id", $userId, $totalPrice);
    $stmt->execute();
    $orderId = $stmt->insert_id;
    // Store the order ID in the session
    $_SESSION['order_id'] = $orderId;
    
    // Insert order details
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $stmt = $conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $orderId, $product_id, $item['quantity'], $item['price']);
        $stmt->execute();
    }

    // Clear the cart
    unset($_SESSION['cart']);

    // Redirect to a confirmation page or display a success message
    header('Location: order_confirmation.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Checkout</title>
</head>
<body>
<div class="container mt-5">
    <h2>Checkout</h2>
    <form action="checkout.php" method="post">
        <div class="mb-3">
            <label for="address" class="form-label">Shipping Address</label>
            <textarea class="form-control" id="address" name="address" required></textarea>
        </div>
        <h4>Order Summary</h4>
        <ul>
            <?php foreach ($_SESSION['cart'] as $id => $item): ?>
            <li><?= htmlspecialchars($item['name']) ?>: $<?= number_format($item['price'], 2) ?> x <?= $item['quantity'] ?></li>
            <?php
            $totalPrice += $item['price'] * $item['quantity'];
            endforeach; ?>
        </ul>
        <p><strong>Total Amount: $<?= number_format($totalPrice, 2) ?></strong></p>
        <button type="submit" name="place_order" class="btn btn-primary">Place Order</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

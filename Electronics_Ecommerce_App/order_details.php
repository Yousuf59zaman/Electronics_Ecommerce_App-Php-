<?php
session_start();
include 'db.php'; // Include your database connection file

if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
    header('Location: login.php');
    exit;
}

$orderId = $_GET['order_id'];

// Fetch order details
$stmt = $conn->prepare("SELECT p.name, od.quantity, od.price FROM order_details od JOIN products p ON od.product_id = p.product_id WHERE od.order_id = ?");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Order Details</title>
</head>
<body>
<div class="container mt-5">
    <h2>Order Details</h2>
    <ul>
        <?php while ($item = $result->fetch_assoc()): ?>
            <li><?= htmlspecialchars($item['name']) ?> - $<?= number_format($item['price'], 2) ?> x <?= $item['quantity'] ?></li>
        <?php endwhile; ?>
    </ul>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

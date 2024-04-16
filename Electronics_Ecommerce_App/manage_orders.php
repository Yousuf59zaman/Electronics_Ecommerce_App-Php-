<?php
session_start();
include 'db.php';

// Redirect if not admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: login.php');
    exit;
}

// Fetch all orders from the database
$result = $conn->query("SELECT orders.order_id, users.username, orders.total_price, orders.created_at FROM orders JOIN users ON orders.user_id = users.user_id ORDER BY orders.created_at DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Manage Orders</title>
</head>
<body>
<div class="container mt-5">
    <h2>Manage Orders</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer Username</th>
                <th>Total Price</th>
                <th>Order Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['order_id'] ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td>$<?= number_format($row['total_price'], 2) ?></td>
                <td><?= date("F j, Y, g:i a", strtotime($row['created_at'])) ?></td>
                <td>
                    <a href="order_details.php?order_id=<?= $row['order_id'] ?>" class="btn btn-info">View Details</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>

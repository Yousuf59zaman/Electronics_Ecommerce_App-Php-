<?php
session_start();
include 'db.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}

$userId = $_SESSION['user_id'];

// Fetch user orders from the database
$stmt = $conn->prepare("SELECT order_id, total_price, created_at FROM orders WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Your Account</title>
</head>
<body>
<div class="container mt-5">
    <h2>Your Orders</h2>
    <?php if ($result->num_rows > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Total Price</th>
                    <th>Date</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['order_id'] ?></td>
                        <td>$<?= number_format($row['total_price'], 2) ?></td>
                        <td><?= date("F j, Y, g:i a", strtotime($row['created_at'])) ?></td>
                        <td><a href="order_details.php?order_id=<?= $row['order_id'] ?>" class="btn btn-info">View Details</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have no orders yet.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

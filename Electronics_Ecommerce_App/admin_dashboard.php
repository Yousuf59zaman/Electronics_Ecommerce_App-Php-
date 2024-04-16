<?php
session_start();
include 'db.php';

// Redirect if the user is not logged in or not an admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: login.php');
    exit;
}

// Admin dashboard content here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Admin Dashboard</title>
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <br>
        <div>
            <h2>Manage Products</h2>
            <a href="add_product.php" class="btn btn-primary">Add New Product</a>
            <a href="edit_product.php" class="btn btn-info">Edit Products</a>
        </div>
        <br>
        <div>
            <h2>View Orders</h2>
            <a href="manage_orders.php" class="btn btn-primary">Manage Orders</a>
        </div>
        
        <br>
        <div>
            <h2>View Users</h2>
            <a href="view_users.php" class="btn btn-primary">view users</a>
        </div>
        <div>
            <a href="login.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</body>
</html>

<?php
session_start();
include 'db.php';

// Redirect if not admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: login.php');
    exit;
}

// Fetch product details based on the product ID
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $product_query = $conn->query("SELECT * FROM products WHERE product_id = $product_id");
    $product = $product_query->fetch_assoc();
} else {
    // Handle error: No product ID provided
    // You can redirect or display an error message here
}

// Process form submission for updating product details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve updated data from form fields
    $new_name = $_POST['name'];
    $new_description = $_POST['description'];
    $new_price = $_POST['price'];
    $new_stock = $_POST['stock'];

    // Update product details in the database
    $update_query = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ? WHERE product_id = ?");
    $update_query->bind_param("ssdii", $new_name, $new_description, $new_price, $new_stock, $product_id);
    $update_result = $update_query->execute();

    if ($update_result) {
        header('Location: manage_product.php');
        // Product details updated successfully
        // You can redirect to a success page or display a success message
    } else {
        // Handle error: Failed to update product details
        // You can redirect or display an error message here
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Product</title>
</head>
<body>
<div class="container">
    <h2>Edit Product</h2>
    <form method="post">
        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Product Description</label>
            <textarea class="form-control" id="description" name="description"><?= htmlspecialchars($product['description']) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Product Price</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?= $product['price'] ?>">
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Product Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" value="<?= $product['stock'] ?>">
        </div>
        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>
</div>
</body>
</html>

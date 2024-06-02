<?php
// Start a new session or resume the existing one
session_start();

// Include the database connection file
include 'db.php';

// Check if the form was submitted via POST and the 'update_cart' button was clicked
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['update_cart'])) {
    // Loop through each item's quantity in the form
    foreach ($_POST['quantities'] as $id => $quantity) {
        // Check if the item exists in the session cart
        if (isset($_SESSION['cart'][$id])) {
            // If the quantity is zero, remove the item from the cart
            if ($quantity == 0) {
                unset($_SESSION['cart'][$id]);
            } else {
                // Otherwise, update the item's quantity in the cart
                $_SESSION['cart'][$id]['quantity'] = $quantity;
            }
        }
    }
}

// Check if the form was submitted via POST and the 'remove_item' button was clicked
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['remove_item'])) {
    // Get the product ID to be removed
    $productId = $_POST['product_id'];
    // Check if the item exists in the session cart
    if (isset($_SESSION['cart'][$productId])) {
        // Remove the item from the cart
        unset($_SESSION['cart'][$productId]);
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>View Cart</title>
</head>
<body>
<div class="container mt-5">
    <h2>Your Shopping Cart</h2>
    <?php
// Check if the session cart is not empty
if (!empty($_SESSION['cart'])):
?>
    <!-- Start of the form that posts back to 'cart.php' -->
    <form action="cart.php" method="post">
        <!-- Start of the table that displays cart items -->
        <table class="table">
            <!-- Table header with column names -->
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <!-- Table body where cart items will be displayed -->
            <tbody>
                <!-- Loop through each item in the session cart -->
                <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                    <tr>
                        <!-- Display the product name, escape any special characters to prevent XSS -->
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <!-- Display the product price, formatted to 2 decimal places -->
                        <td>$<?= number_format($item['price'], 2) ?></td>
                        <!-- Input field for updating the quantity of the item -->
                        <td>
                            <input type="number" name="quantities[<?= $id ?>]" value="<?= $item['quantity'] ?>" min="0" class="form-control">
                        </td>
                        <!-- Display the total price for the item (price * quantity), formatted to 2 decimal places -->
                        <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                        <!-- Button to remove the item from the cart -->
                        <td>
                                <!-- Start of form for removing an item -->
                                <form method="post" action="cart.php">
                                    <input type="hidden" name="product_id" value="<?= $id ?>">
                                    <button type="submit" name="remove_item" class="btn btn-danger">Remove</button>
                                </form>
                                <!-- End of form for removing an item -->
                            </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!-- Button to submit the form and update the cart -->
        <button type="submit" name="update_cart" value="Update Cart" class="btn btn-primary">Update Cart</button>
    </form>
<?php
// If the session cart is empty, display this message
else:
?>
    <p>Your cart is empty!</p>
<?php
// End the if statement
endif;
?><br> <br>
    
    <a href="index.php" class="btn btn-secondary">Continue Shopping</a>
    <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

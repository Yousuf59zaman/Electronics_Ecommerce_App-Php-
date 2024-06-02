<?php
// Start or resume a session to maintain cart data across different pages
session_start();

// Include the database connection settings from the 'db.php' file
include 'db.php';

// Check if the 'add_to_cart' button has been clicked and the form submitted
if (isset($_POST['add_to_cart'])) {
    // Retrieve the product ID and quantity from the form submission
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Prepare a SQL query to fetch product details from the database using the product ID
    $sql = "SELECT * FROM products WHERE product_id = '$product_id'";
    // Execute the query and store the result
    $result = $conn->query($sql);

    // Check if the product exists in the database
    if ($result->num_rows > 0) {
        // Fetch the product details as an associative array
        $product = $result->fetch_assoc();

        // If the product is already in the cart, update the quantity
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            // If the product is not in the cart, add it with its details
            $_SESSION['cart'][$product_id] = array(
                'name' => $product['name'], // Product name
                'price' => $product['price'], // Product price
                'quantity' => $quantity // Quantity being added
            );
        }
    }
}

// Fetch products with stock available
$sql = "SELECT * FROM products WHERE stock > 0";
$result = $conn->query($sql);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>My E-commerce Site</title>
</head>
<body>
<!--  Navbar  -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Electron</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Link</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Category
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="phone.php">Phones</a></li>
            <li><a class="dropdown-item" href="laptop.php">Laptops</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Contact Us</a>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" aria-disabled="true">Disabled</a>
        </li>

      </ul>
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
      <!-- Cart Button -->
    <a href="cart.php" class="btn btn-outline-primary ms-2">Cart</a>
    </div>
  </div>
</nav>

<!--  End of Navbar -->

<div class="container mt-4">
    <h2>Our Products</h2>
    <div class="row">
        <?php if ($result->num_rows > 0) : ?>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <img src="images/<?= htmlspecialchars($row['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['name']) ?>" style="height: 200px; width: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($row['description']) ?></p>
                            <p class="card-text">Price: $<?= number_format($row['price'], 2) ?></p>
                            <form method="post">
                                <input type="hidden" name="product_id" value="<?= htmlspecialchars($row['product_id']) ?>">
                                <input type="number" name="quantity" value="1" min="1" required><br><br>
                                <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <p>No products found!</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

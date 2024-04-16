<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
   <title>Login</title>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form action="login.php" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="login">Login</button>
          
</form>
<p>Don't have an account? <a href='register.php'>Register here</a>. </p>
        
    </div>
</body>
</html>

<?php
session_start();
include 'db.php'; // Includes the database connection

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Check if the 'id' column is retrieved and not null
            if (!empty($row['user_id'])) {
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['is_admin'] = $row['is_admin']; // Store admin status in session

    if ($row['is_admin']) {
        header('Location: admin_dashboard.php'); // Redirect to admin dashboard if user is an admin
        exit;
    } else {
        header('Location: index.php'); // Redirect to the main page if not an admin
        exit;
    }} else {
                echo "User ID could not be retrieved from the database.";
            }
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No user found with that email address!";
    }
}
?>



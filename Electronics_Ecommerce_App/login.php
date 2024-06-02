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
session_start(); // Starts a new or resumes an existing session
include 'db.php'; // Includes the database connection script

if (isset($_POST['login'])) { // Checks if the form has been submitted (user clicked the "Login" button)
    $email = mysqli_real_escape_string($conn, $_POST['email']); // Escapes special characters in the email
    $password = mysqli_real_escape_string($conn, $_POST['password']); // Escapes special characters in the password

    $sql = "SELECT * FROM users WHERE email = '$email'"; // SQL query to retrieve user data based on email
    $result = $conn->query($sql); // Executes the query

    if ($result->num_rows > 0) { // If user with the given email exists
        $row = $result->fetch_assoc(); // Fetches the user data
        if (password_verify($password, $row['password'])) { // Verifies the hashed password
            if (!empty($row['user_id'])) { // Checks if the 'user_id' column is retrieved and not null
                $_SESSION['user_id'] = $row['user_id']; // Stores user ID in the session
                $_SESSION['email'] = $row['email']; // Stores email in the session
                $_SESSION['is_admin'] = $row['is_admin']; // Stores admin status in the session

                if ($row['is_admin']) {
                    header('Location: admin_dashboard.php'); // Redirects to admin dashboard if user is an admin
                    exit;
                } else {
                    header('Location: index.php'); // Redirects to the main page if not an admin
                    exit;
                }
            } else {
                echo "User ID could not be retrieved from the database.";
            }
        } else {
            echo "Invalid password!"; // Incorrect password
        }
    } else {
        echo "No user found with that email address!"; // User not found
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
   <title>Register</title>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form action="register.php" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="register">Register</button>
        </form>
        <p>Already have an account? <a href='login.php'>Login here</a>. </p>
    </div>
</body>
</html>


<?php
include 'db.php'; // Includes the database connection script.

if (isset($_POST['register'])) { // Checks if the form has been submitted.
    $username = mysqli_real_escape_string($conn, $_POST['username']); // Escapes special characters in the username.
    $email = mysqli_real_escape_string($conn, $_POST['email']); // Escapes special characters in the email.
    $password = mysqli_real_escape_string($conn, $_POST['password']); // Escapes special characters in the password.
    $password = password_hash($password, PASSWORD_DEFAULT); // Hashes the password for security.

    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')"; // SQL query to insert new user.
    if ($conn->query($sql) === TRUE) { // Executes the SQL query.
        echo "New record created successfully"; // Success message.
        header('Location: login.php'); // Redirects to the login page.
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error; // Error message if the query fails.
    }
}
?>

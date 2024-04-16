<?php
session_start();
include 'db.php'; // Include your database connection file

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$message = '';

// Fetch the current user information
$stmt = $conn->prepare("SELECT username, email FROM users WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle the POST request to update user details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Consider adding password validation or hashing

    // Prepare SQL to update the user's information
    if (empty($password)) {
        // Update without changing password
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE user_id = ?");
        $stmt->bind_param("ssi", $username, $email, $userId);
    } else {
        // Update including password change
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE user_id = ?");
        $stmt->bind_param("sssi", $username, $email, $hashedPassword, $userId);
    }

    if ($stmt->execute()) {
        $message = 'Profile updated successfully!';
    } else {
        $message = 'Error updating profile.';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Update Profile</title>
</head>
<body>
<div class="container mt-5">
    <h2>Update Your Profile</h2>
    <p><?= $message ?></p>
    <form method="POST" action="update_profile.php">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">New Password (leave blank to keep current)</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
        <div class="mb-3">
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

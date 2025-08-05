<?php
// Start the session (for storing login status if needed)
session_start();

// Check if the user is already logged in (Optional)
if (isset($_SESSION['username'])) {
    header('Location: dashboard.php'); // Redirect to dashboard if logged in
    exit();
}

// Handle the login logic (this should be improved with better validation and security like hashed passwords)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the login credentials from the POST request
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sample credentials (you should replace this with a database check)
    $valid_username = "admin";  // Example username
    $valid_password = "password123";  // Example password

    // Check if the entered credentials match the valid ones
    if ($username === $valid_username && $password === $valid_password) {
        // Set session variable and redirect to dashboard
        $_SESSION['username'] = $username;
        header('Location: dashboard.php');  // Replace with your desired page
        exit();
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        .login-container img {
            width: 100px;
            margin-bottom: 20px;
        }

        .login-container h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .login-container button:hover {
            background-color: #218838;
        }

        .error-message {
            color: red;
            margin-bottom: 15px;
        }

        .login-container a {
            display: block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }

        .login-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <!-- Replace the logo image URL with your actual logo -->
<img src="../images/logo.png" alt="Logo">
    <h2>Login</h2>
    
    <!-- Display error message if credentials are wrong -->
    <?php if (isset($error_message)): ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- Login Form -->
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

    <a href="#">Forgot Password?</a> <!-- Link to a password reset page (optional) -->
</div>

</body>
</html>

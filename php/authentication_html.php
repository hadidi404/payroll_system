<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/authentication.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <title>Login</title>
</head>
<body>

<div id="parent_div">
    <div class="login-container">
<img src="../images/logo_authentication.png" alt="Logo">
        
        <!-- Display error message if credentials are wrong -->
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Login Form -->
        <form id="details_form" method="POST" action="">
            <div id="elements_in_form">
                <input id="detail" type="text" name="username" placeholder="Username" required>
            </div>
            <div id="elements_in_form">
                <input id="detail" type="password" name="password" placeholder="Password" required>
            </div>
            <div id="elements_in_form">
                <button id="btn_detail" type="submit">Login</button>
            </div>
        </form>

        <a href="#">Forgot Password?</a>
    </div>
</div>

</body>
</html>

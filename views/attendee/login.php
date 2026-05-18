<?php
session_start();

if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit();
}

$saved_email = "";

if (isset($_COOKIE["attendee_email"])) {
    $saved_email = $_COOKIE["attendee_email"];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="../../public/css/attendee.css">
</head>
<body>
<div class="auth-page">
    <div class="auth-box">
        <div class="auth-logo">
            <img src="../../public/uploads/Logo.png" alt="Logo">
            <h2>Event Platform</h2>
            <p>Login to your attendee account</p>
        </div>
        <?php if (isset($_SESSION["success"])): ?>
            <p class="success-msg"><?php echo $_SESSION["success"]; ?></p>
            <?php unset($_SESSION["success"]); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION["error"])): ?>
            <p class="error-msg"><?php echo $_SESSION["error"]; ?></p>
            <?php unset($_SESSION["error"]); ?>
        <?php endif; ?>
        <form method="post" action="../../controllers/authController.php">
            <input type="hidden" name="action" value="login">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo $saved_email; ?>" placeholder="Enter your email">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password">
            </div>
            <div class="remember-box">
                <input type="checkbox" name="remember" value="yes" <?php if ($saved_email != "") { echo "checked"; } ?>>
                <label>Remember Me</label>
            </div>
            <button type="submit" class="auth-btn">Login</button>
            <p class="auth-link">
                Don't have an account?
                <a href="register.php">Register here</a>
            </p>
        </form>
    </div>
</div>
</body>
</html>
<?php
session_start();

if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="../../public/css/attendee.css">
</head>
<body>
<div class="auth-page">
    <div class="auth-box">
        <div class="auth-logo">
            <img src="../../public/uploads/Logo.png" alt="Logo">
            <h2>Event Platform</h2>
            <p>Create your attendee account</p>
        </div>
        <?php if (isset($_SESSION["error"])): ?>
            <p class="error-msg"><?php echo $_SESSION["error"]; ?></p>
            <?php unset($_SESSION["error"]); ?>
        <?php endif; ?>
        <form method="post" action="../../controllers/authController.php">
            <input type="hidden" name="action" value="register">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" placeholder="Enter your full name">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Enter your email">
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" placeholder="Enter your phone number">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Create password">
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" placeholder="Confirm password">
            </div>
            <button type="submit" class="auth-btn">Register</button>
            <p class="auth-link">
                Already have an account?
                <a href="login.php">Login here</a>
            </p>
        </form>
    </div>
</div>
</body>
</html>
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

        <form>
            <div class="form-group">
                <label>Email</label>
                <input type="email" placeholder="Enter your email">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" placeholder="Enter your password">
            </div>

            <button type="button" onclick="location.href='dashboard.php'" class="auth-btn">Login</button>

            <p class="auth-link">
                Don't have an account?
                <a href="register.php">Register here</a>
            </p>
        </form>
    </div>
</div>
</body>
</html>
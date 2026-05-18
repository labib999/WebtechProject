<?php
include("session_check.php");
include("../../controllers/profilePageController.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="../../public/css/attendee.css">
</head>
<body>
<?php include("sidebar.php"); ?>
<div class="main-content">
    <div class="topbar">
        <div class="topbar-left">
            <h2>My Profile</h2>
            <p>View and update your profile information</p>
        </div>
        <div class="topbar-right">
            <div class="user-profile">
                <img src="../../public/uploads/user.png" alt="User">
                <div>
                    <h4><?php echo $_SESSION["name"]; ?></h4>
                    <span><?php echo $_SESSION["role"]; ?></span>
                </div>
            </div>
        </div>
    </div>
    <div class="profile-grid">
        <div class="content-card profile-card">
            <img src="../../public/uploads/user.png" alt="User">
            <h2><?php echo $user["name"]; ?></h2>
            <p><?php echo $user["role"]; ?></p>
            <span>Active Account</span>
        </div>
        <div class="content-card">
            <h2>Profile Information</h2>
            <?php if (isset($_SESSION["success"])): ?>
                <p class="success-msg"><?php echo $_SESSION["success"]; ?></p>
                <?php unset($_SESSION["success"]); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION["error"])): ?>
                <p class="error-msg"><?php echo $_SESSION["error"]; ?></p>
                <?php unset($_SESSION["error"]); ?>
            <?php endif; ?>
            <form method="post" action="../../controllers/profileController.php">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" value="<?php echo $user["name"]; ?>">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo $user["email"]; ?>">
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" value="<?php echo $user["phone"]; ?>">
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <input type="text" value="<?php echo $user["role"]; ?>" readonly>
                </div>
                <button type="submit" class="confirm-btn">Update Profile</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
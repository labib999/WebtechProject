<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="../../public/css/attendee.css">
</head>
<body>
<?php include("sidebar.php"); ?>
<div class="main-content">
    <!-- Topbar -->
    <div class="topbar">
        <div class="topbar-left">
            <h2>My Profile</h2>
            <p>View and update your profile information</p>
        </div>
        <div class="topbar-right">
            <div class="search-box">
                <input type="text" placeholder="Search events...">
            </div>
            <div class="user-profile">
                <img src="../../public/uploads/user.png" alt="User">
                <div>
                    <h4>Maruf</h4>
                    <span>Attendee</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile -->
    <div class="profile-grid">
        <div class="content-card profile-card">
            <img src="../../public/uploads/user.png" alt="User">
            <h2>Maruf</h2>
            <p>Attendee</p>
            <span>Active Account</span>
        </div>

        <div class="content-card">
            <h2>Profile Information</h2>
            <form>
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" value="Maruf">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" value="maruf@gmail.com">
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" value="01700000000">
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <input type="text" value="Attendee" readonly>
                </div>
                <button type="button" onclick="showProfileMessage()" class="confirm-btn">Update Profile</button>
                <p id="profileMsg" class="profile-msg"></p>
            </form>
        </div>
    </div>
</div>

<script>
function showProfileMessage(){
    document.getElementById("profileMsg").innerHTML = "Profile updated successfully";
}
</script>

</body>
</html>
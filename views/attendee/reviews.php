<?php include("session_check.php"); ?>

<!DOCTYPE html>
<html>

<head>
    <title>My Reviews</title>
    <link rel="stylesheet" href="../../public/css/attendee.css">
</head>

<body>
    <?php include("sidebar.php"); ?>
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <div class="topbar-left">
                <h2>My Reviews</h2>
                <p>Write and view your event reviews</p>
            </div>
            <div class="topbar-right">
                <div class="search-box">
                    <input type="text" placeholder="Search reviews...">
                </div>
                <div class="user-profile">
                    <img src="../../public/uploads/user.png" alt="User">
                    <div>
                        <h4><?php echo $_SESSION["name"]; ?></h4>
                        <span><?php echo $_SESSION["role"]; ?></span>
                        <span>Attendee</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Review Form -->
        <div class="content-card">
            <h2>Write Review</h2>
            <form>
                <div class="form-group">
                    <label>Select Event</label>
                    <select>
                        <option>Business Expo</option>
                        <option>AI Conference 2026</option>
                        <option>Music Night Dhaka</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Rating</label>
                    <select>
                        <option>5 - Excellent</option>
                        <option>4 - Good</option>
                        <option>3 - Average</option>
                        <option>2 - Poor</option>
                        <option>1 - Bad</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Review</label>
                    <textarea placeholder="Write your review"></textarea>
                </div>
                <button type="button" onclick="showReviewMessage()" class="confirm-btn">Submit Review</button>
                <p id="reviewMsg" class="review-msg"></p>
            </form>
        </div>

        <!-- Previous Reviews -->
        <div class="content-card review-list-card">
            <div class="card-header">
                <h2>Previous Reviews</h2>
            </div>
            <div class="review-box">
                <h4>Business Expo</h4>
                <span>★★★★★</span>
                <p>Very useful event. I learned a lot from the business sessions.</p>
            </div>
            <div class="review-box">
                <h4>Tech Innovation Summit</h4>
                <span>★★★★☆</span>
                <p>The speakers were good and the event was well organized.</p>
            </div>
        </div>
    </div>

    <script>
        function showReviewMessage() {
            document.getElementById("reviewMsg").innerHTML = "Review submitted successfully";
        }
    </script>

</body>

</html>
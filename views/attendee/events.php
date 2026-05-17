<?php include("session_check.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Browse Events</title>
    <link rel="stylesheet" href="../../public/css/attendee.css">
</head>

<body>

<?php include("sidebar.php"); ?>

<div class="main-content">

    <!-- Topbar -->
    <div class="topbar">

        <div class="topbar-left">
            <h2>Browse Events</h2>
            <p>Search and find your favorite upcoming events</p>
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

    <!-- Search and Filter Section -->
    <div class="content-card filter-card">
        <h2>Find Events</h2>
        <div class="filter-row">
            <div class="filter-group">
                <label>Event Name</label>
                <input type="text" placeholder="Search by event name">
            </div>

            <div class="filter-group">
                <label>Category</label>
                <select>
                    <option>All Categories</option>
                    <option>Technology</option>
                    <option>Music</option>
                    <option>Business</option>
                    <option>Sports</option>
                </select>
            </div>

            <div class="filter-group">
                <label>City</label>
                <select>
                    <option>All Cities</option>
                    <option>Dhaka</option>
                    <option>Chattogram</option>
                    <option>Sylhet</option>
                    <option>Rajshahi</option>
                </select>
            </div>

            <div class="filter-group">
                <label>Price</label>
                <select>
                    <option>Any Price</option>
                    <option>Free</option>
                    <option>৳1 - ৳500</option>
                    <option>৳501 - ৳1500</option>
                    <option>৳1500+</option>
                </select>
            </div>
        </div>

        <button class="search-event-btn"> Search Events</button>

    </div>

    <!-- Events Grid -->
    <div class="events-page-grid">

        <!-- Event 1 -->
        <div class="event-list-card">
            <div class="event-card-image"></div>
            <div class="event-card-body">
                <span class="event-tag">Technology</span>
                <h3>AI Conference 2026</h3>
                <p>Learn about artificial intelligence, machine learning, and future technology.</p>

                <div class="event-meta">
                    <span>28 May 2026</span>
                    <span>Dhaka</span>
                </div>

                <div class="event-price">From ৳500</div>
                <div class="event-actions">
                    <a href="event-details.php" class="event-btn">View Details</a>
                    <a href="checkout.php" class="event-btn outline">Book Now</a>
                </div>
            </div>
        </div>

        <!-- Event 2 -->
        <div class="event-list-card">
            <div class="event-card-image music-bg"></div>
            <div class="event-card-body">
                <span class="event-tag">Music</span>
                <h3>Music Night Dhaka</h3>
                <p>Enjoy a wonderful live music night with popular Bangladeshi artists.</p>

                <div class="event-meta">
                    <span>10 June 2026</span>
                    <span>Gulshan</span>
                </div>

                <div class="event-price"> From ৳800</div>

                <div class="event-actions">
                    <a href="event-details.php" class="event-btn">View Details</a>
                    <a href="checkout.php" class="event-btn outline">Book Now</a>
                </div>
            </div>
        </div>

        <!-- Event 3 -->
        <div class="event-list-card">
            <div class="event-card-image business-bg"></div>
            <div class="event-card-body">
                <span class="event-tag">Business</span>
                <h3>Startup Meetup</h3>
                <p>Meet founders, investors, and entrepreneurs from the startup ecosystem. </p>

                <div class="event-meta">
                    <span>02 June 2026</span>
                    <span>Banani</span>
                </div>

                <div class="event-price">From ৳300</div>
                <div class="event-actions">
                    <a href="event-details.php" class="event-btn">View Details</a>
                    <a href="checkout.php" class="event-btn outline">Book Now</a>
                </div>
            </div>
        </div>

        <!-- Event 4 -->
        <div class="event-list-card">
            <div class="event-card-image sports-bg"></div>
            <div class="event-card-body">
                <span class="event-tag">Sports</span>
                <h3>Football Fan Fest</h3>
                <p>Watch live screening, enjoy games, food stalls, and fan activities.</p>

                <div class="event-meta">
                    <span>18 June 2026</span>
                    <span>Mirpur</span>
                </div>

                <div class="event-price">From ৳200</div>

                <div class="event-actions">
                    <a href="event-details.php" class="event-btn">View Details</a>
                    <a href="checkout.php" class="event-btn outline">Book Now</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
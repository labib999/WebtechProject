<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="../../public/css/attendee.css">
</head>
<body>

<?php include("sidebar.php"); ?>

<div class="main-content">

    <div class="dashboard-header">
        <div>
            <h1>Dashboard</h1>
            <p>Welcome back to your attendee panel</p>
        </div>

        <a href="events.php" class="primary-btn">Browse Events</a>
    </div>

    <div class="stats-grid">

        <div class="stat-card">
            <h3>Total Tickets</h3>
            <h2>12</h2>
            <p>Tickets purchased</p>
        </div>

        <div class="stat-card">
            <h3>Upcoming Events</h3>
            <h2>4</h2>
            <p>Events waiting for you</p>
        </div>

        <div class="stat-card">
            <h3>Events Attended</h3>
            <h2>8</h2>
            <p>Completed events</p>
        </div>

        <div class="stat-card">
            <h3>Following</h3>
            <h2>6</h2>
            <p>Organisers followed</p>
        </div>

    </div>

    <div class="dashboard-row">

        <div class="content-card large-card">
            <div class="card-header">
                <h2>Recent Bookings</h2>
                <a href="my-tickets.php">View All</a>
            </div>

            <table class="data-table">
                <tr>
                    <th>Event</th>
                    <th>Date</th>
                    <th>Ticket</th>
                    <th>Status</th>
                </tr>

                <tr>
                    <td>Tech Innovation Summit</td>
                    <td>20 May 2026</td>
                    <td>VIP</td>
                    <td><span class="badge active">Active</span></td>
                </tr>

                <tr>
                    <td>Music Night Dhaka</td>
                    <td>25 May 2026</td>
                    <td>General</td>
                    <td><span class="badge active">Active</span></td>
                </tr>

                <tr>
                    <td>Business Expo</td>
                    <td>10 April 2026</td>
                    <td>Early Bird</td>
                    <td><span class="badge past">Completed</span></td>
                </tr>
            </table>
        </div>

        <div class="content-card small-card">
            <h2>Quick Actions</h2>

            <a href="events.php" class="quick-btn">Browse Events</a>
            <a href="my-tickets.php" class="quick-btn">My Tickets</a>
            <a href="profile.php" class="quick-btn">Update Profile</a>
            <a href="complaint.php" class="quick-btn">Submit Complaint</a>
        </div>

    </div>

    <div class="content-card">
        <div class="card-header">
            <h2>Upcoming Events</h2>
            <a href="events.php">Explore More</a>
        </div>

        <div class="event-grid">

            <div class="event-card">
                <div class="event-image"></div>
                <h3>AI Conference 2026</h3>
                <p>Dhaka Convention Center</p>
                <span>28 May 2026</span>
                <a href="event-details.php" class="event-btn">View Details</a>
            </div>

            <div class="event-card">
                <div class="event-image"></div>
                <h3>Startup Meetup</h3>
                <p>Banani, Dhaka</p>
                <span>02 June 2026</span>
                <a href="event-details.php" class="event-btn">View Details</a>
            </div>

            <div class="event-card">
                <div class="event-image"></div>
                <h3>Music Festival</h3>
                <p>Gulshan Club</p>
                <span>10 June 2026</span>
                <a href="event-details.php" class="event-btn">View Details</a>
            </div>

        </div>
    </div>

</div>

</body>
</html>
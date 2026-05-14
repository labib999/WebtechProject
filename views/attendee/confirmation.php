<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="../../public/css/attendee.css">
</head>

<body>

<?php include("sidebar.php"); ?>

<div class="main-content">

<div class="topbar">

        <div class="topbar-left">
            <h2>Booking Confirmation</h2>
            <p>Your ticket has been booked successfully</p>
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

    <!-- Confirmation Card -->
    <div class="confirmation-card">
        <div class="success-circle">
            ✓
        </div>

        <h1>Booking Successful!</h1>
        <p class="confirm-text"> Your ticket has been confirmed. Please save your ticket code for event entry. </p>

        <div class="ticket-code-box">
            <span>Ticket Code</span>
            <h2>AI2026-4589</h2>
        </div>

        <div class="confirmation-details">
            <div class="detail-row">
                <span>Event Name</span>
                <strong>AI Conference 2026</strong>
            </div>

            <div class="detail-row">
                <span>Date</span>
                <strong>28 May 2026</strong>
            </div>

            <div class="detail-row">
                <span>Venue</span>
                <strong>Dhaka Convention Center</strong>
            </div>

            <div class="detail-row">
                <span>Ticket Tier</span>
                <strong>General</strong>
            </div>

            <div class="detail-row">
                <span>Quantity</span>
                <strong>1</strong>
            </div>

            <div class="detail-row total-row">
                <span>Total Paid</span>
                <strong>৳550</strong>
            </div>

        </div>

        <div class="confirmation-actions">
            <a href="my-tickets.php" class="confirm-action-btn"> View My Tickets </a>
            <a href="ticket-print.php" class="outline-btn">Print Ticket</a>
            <a href="events.php" class="outline-btn">Browse More Events</a>
        </div>
    </div>
</div>

</body>
</html>
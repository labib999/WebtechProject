<?php include("session_check.php"); ?>

<!DOCTYPE html>
<html>

<head>
    <title>Print Ticket</title>
    <link rel="stylesheet" href="../../public/css/attendee.css">
</head>

<body>
    <?php include("sidebar.php"); ?>
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <div class="topbar-left">
                <h2>Print Ticket</h2>
                <p>View your ticket information</p>
            </div>
            <div class="topbar-right">
                <div class="search-box">
                    <input type="text" placeholder="Search tickets...">
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

        <!-- Ticket -->
        <div class="print-ticket-card">
            <div class="ticket-header">
                <h1>Event Platform</h1>
                <p>Official Event Ticket</p>
            </div>

            <div class="ticket-code-print">
                <span>Ticket Code</span>
                <h2>AI2026-4589</h2>
            </div>

            <div class="ticket-info-print">
                <div class="detail-row">
                    <span>Event</span>
                    <strong>AI Conference 2026</strong>
                </div>
                <div class="detail-row">
                    <span>Date</span>
                    <strong>28 May 2026</strong>
                </div>
                <div class="detail-row">
                    <span>Time</span>
                    <strong>10:00 AM</strong>
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
                <div class="detail-row">
                    <span>Total Paid</span>
                    <strong>৳550</strong>
                </div>
            </div>

            <p id="printMsg" class="print-msg"></p>

            <div class="print-actions">
                <button onclick="showPrintMessage()" class="confirm-action-btn">Print Ticket</button>
                <a href="my-tickets.php" class="outline-btn">Back to My Tickets</a>
            </div>
        </div>
    </div>

    <script>
        function showPrintMessage() {
            document.getElementById("printMsg").innerHTML = "Printed successfully";
        }
    </script>

</body>

</html>
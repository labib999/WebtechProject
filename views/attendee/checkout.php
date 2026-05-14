<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="stylesheet" href="../../public/css/attendee.css">
</head>
<body>
<?php include("sidebar.php"); ?>
<div class="main-content">
    <!-- Topbar -->
    <div class="topbar">
        <div class="topbar-left">
            <h2>Checkout</h2>
            <p>Confirm your ticket booking</p>
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

    <!-- Checkout Form -->
    <div class="checkout-grid">
        <div class="content-card">
            <h2>Booking Information</h2>
            <div class="form-group">
                <label>Event</label>
                <input type="text" value="AI Conference 2026" readonly>
            </div>
            <div class="form-group">
                <label>Ticket Tier</label>
                <select>
                    <option>General - ৳500</option>
                    <option>VIP - ৳1500</option>
                    <option>Student - ৳300</option>
                </select>
            </div>
            <div class="form-group">
                <label>Quantity</label>
                <input type="number" value="1" min="1">
            </div>
            <div class="form-group">
                <label>Payment Method</label>
                <select>
                    <option>bKash</option>
                    <option>Nagad</option>
                    <option>Rocket</option>
                    <option>Card Payment</option>
                </select>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="content-card order-summary">
            <h2>Order Summary</h2>
            <div class="summary-item">
                <span>Ticket Price</span>
                <strong>৳500</strong>
            </div>
            <div class="summary-item">
                <span>Quantity</span>
                <strong>1</strong>
            </div>
            <div class="summary-item">
                <span>Service Charge</span>
                <strong>৳50</strong>
            </div>
            <div class="summary-total">
                <span>Total</span>
                <strong>৳550</strong>
            </div>
            <a href="confirmation.php" class="confirm-btn">Confirm Booking</a>
        </div>
    </div>
</div>
</body>
</html>
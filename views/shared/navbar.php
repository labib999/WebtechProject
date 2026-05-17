<?php ?>
<div class="d-flex">

  <nav class="d-flex flex-column p-3" style="width:250px; min-height:100vh; background-color:#1e293b; position:fixed; top:0; left:0; z-index:100;">

    <div class="mb-4 px-2 pt-2">
      <h4 class="text-white fw-bold mb-0">EM<span style="color:#3b82f6">TS</span></h4>
      <small style="color:#94a3b8; font-size:11px; letter-spacing:1px;">VENUE MANAGER</small>
    </div>

    <ul class="nav nav-pills flex-column gap-1 flex-grow-1">

      <li class="nav-item">
        <a href="/webtechproject/WebtechProject/views/venue/dashboard.php"
           class="nav-link <?= ($activePage ?? '') === 'dashboard' ? 'active' : 'text-secondary' ?>">
          <i class="bi bi-house me-2"></i> Dashboard
        </a>
      </li>

      <li class="nav-item">
        <a href="/webtechproject/WebtechProject/views/venue/create_venue.php"
           class="nav-link <?= ($activePage ?? '') === 'create_venue' ? 'active' : 'text-secondary' ?>">
          <i class="bi bi-plus-circle me-2"></i> Create Venue
        </a>
      </li>

      <li class="nav-item">
        <a href="/webtechproject/WebtechProject/views/venue/manage_venues.php"
           class="nav-link <?= ($activePage ?? '') === 'manage_venues' ? 'active' : 'text-secondary' ?>">
          <i class="bi bi-building me-2"></i> My Venues
        </a>
      </li>

      <li class="nav-item">
        <a href="/webtechproject/WebtechProject/views/venue/calendar.php"
           class="nav-link <?= ($activePage ?? '') === 'calendar' ? 'active' : 'text-secondary' ?>">
          <i class="bi bi-calendar3 me-2"></i> Availability Calendar
        </a>
      </li>

      <li class="nav-item">
        <a href="/webtechproject/WebtechProject/views/venue/booking_requests.php"
           class="nav-link <?= ($activePage ?? '') === 'booking_requests' ? 'active' : 'text-secondary' ?>">
          <i class="bi bi-inbox me-2"></i> Booking Requests
        </a>
      </li>

      <li class="nav-item">
        <a href="/webtechproject/WebtechProject/views/venue/pricing.php"
           class="nav-link <?= ($activePage ?? '') === 'pricing' ? 'active' : 'text-secondary' ?>">
          <i class="bi bi-tag me-2"></i> Pricing
        </a>
      </li>

      <li class="nav-item">
        <a href="/webtechproject/WebtechProject/views/venue/upcoming_events.php"
           class="nav-link <?= ($activePage ?? '') === 'upcoming_events' ? 'active' : 'text-secondary' ?>">
          <i class="bi bi-calendar-check me-2"></i> Upcoming Events
        </a>
      </li>

      <li class="nav-item">
        <a href="/webtechproject/WebtechProject/views/venue/occupancy_report.php"
           class="nav-link <?= ($activePage ?? '') === 'occupancy' ? 'active' : 'text-secondary' ?>">
          <i class="bi bi-bar-chart me-2"></i> Occupancy Report
        </a>
      </li>

      <li class="nav-item">
        <a href="/webtechproject/WebtechProject/views/venue/booking_history.php"
           class="nav-link <?= ($activePage ?? '') === 'booking_history' ? 'active' : 'text-secondary' ?>">
          <i class="bi bi-clock-history me-2"></i> Booking History
        </a>
      </li>

      <li class="nav-item">
        <a href="/webtechproject/WebtechProject/views/venue/repeat_organisers.php"
           class="nav-link <?= ($activePage ?? '') === 'repeat_organisers' ? 'active' : 'text-secondary' ?>">
          <i class="bi bi-people me-2"></i> Organiser List
        </a>
      </li>

      <li class="nav-item">
        <a href="/webtechproject/WebtechProject/views/venue/profile.php"
           class="nav-link <?= ($activePage ?? '') === 'profile' ? 'active' : 'text-secondary' ?>">
          <i class="bi bi-person me-2"></i> My Profile
        </a>
      </li>

    </ul>

    <div class="mt-auto pt-3 border-top border-secondary">
      <a href="/webtechproject/WebtechProject/controllers/AuthController.php?action=logout"
         class="nav-link text-danger">
        <i class="bi bi-box-arrow-right me-2"></i> Logout
      </a>
    </div>

  </nav>

  <div class="p-4" style="margin-left:250px; width:calc(100% - 250px); min-height:100vh; background-color:#f8fafc;">
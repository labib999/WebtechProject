# Event Management & Ticketing System

Project 15 — Web Technologies Course

## Roles
- Attendee
- Organiser — Mahinul Islam
- Venue Manager
- Admin

## Setup
1. Import database/schema.sql into phpMyAdmin
2. Run database/seed.sql for demo data
3. Edit app/config/database.php with your DB credentials
4. Open http://localhost/WebtechProject/public/ in browser

# Event Platform — Organiser Role

A full-stack web application for managing events, ticket sales, check-ins, and attendee communications.
Built with PHP 8, MySQL, and Bootstrap 5 as a university Web Technologies project.

---

## Team & Role

| Student | GitHub | Role |
|---|---|---|
| Mahinul Islam | [@mahinul17](https://github.com/mahinul17) | Organiser Role |

---

## Features (Organiser Role)

| Feature | Description |
|---|---|
| Authentication | Sliding login/register with role-based access |
| Dashboard | KPI cards, Chart.js sales & revenue charts, dark mode |
| Event Management | Create, edit, publish, cancel events with banner upload |
| Ticket Tiers | Multiple price tiers per event with seat limits |
| Live Check-in | AJAX-powered scanner with QR code support |
| Bookings | Filter by event, tier, status, check-in with live search |
| Analytics | Sales over time, revenue by tier, check-in by hour charts |
| Discount Codes | Create promo codes with usage tracking and toggle |
| Refund Requests | Approve or reject with organiser notes |
| Reviews | View attendee reviews, reply publicly |
| Announcements | Send messages to all ticket holders of an event |
| Venue Browsing | Search venues by city and capacity, submit booking requests |
| Profile Management | Edit personal info, organisation details, logo upload |

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.2 (MVC, no framework) |
| Database | MySQL 8 via mysqli prepared statements |
| Frontend | Bootstrap 5.3, Chart.js 4, Bootstrap Icons |
| Server | Apache via XAMPP |
| Version Control | Git + GitHub |

---

## Project Structure

WebtechProject/
├── app/
│   ├── config/         # Database configuration
│   ├── core/           # Database, Session, Auth, Router, Controller
│   ├── controllers/    # One controller per feature
│   ├── models/         # (reserved for future models)
│   └── views/          # Blade-style PHP views
│       ├── auth/       # Login + register
│       ├── layouts/    # Header, footer
│       ├── organiser/  # All organiser views
│       └── errors/     # 404 page
├── database/
│   ├── schema.sql      # All 16 tables
│   └── seed.sql        # Demo data
└── public/
├── index.php       # Front controller
├── .htaccess       # URL rewriting
└── assets/         # CSS, JS, uploads


---

## Setup Instructions

### Requirements
- XAMPP (PHP 8.2+, MySQL 8, Apache)
- Git

### Steps

**1. Clone the repository**
```bash
git clone https://github.com/labib999/WebtechProject.git
cd WebtechProject
```

**2. Start XAMPP**
- Start Apache and MySQL from the XAMPP Control Panel

**3. Create the database**
- Open `http://localhost/phpmyadmin`
- Create a new database named `event_platform`
- Import `database/schema.sql`
- Import `database/seed.sql`

**4. Configure database connection**
```bash
cp app/config/database.example.php app/config/database.php
```
Edit `app/config/database.php` with your credentials:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'event_platform');
define('DB_USER', 'root');
define('DB_PASS', '');
```

**5. Open in browser**

http://localhost/WebtechProject/public

---

## Demo Credentials

| Role | Email | Password |
|---|---|---|
| Organiser | mahinul@org.local | Organiser@123 |
| Admin | admin@platform.local | Admin@123 |
| Attendee | attendee1@test.local | Attendee@123 |

---

## Key Design Decisions

- **No framework** — Pure PHP MVC built from scratch to demonstrate understanding
- **Prepared statements** — All queries use mysqli prepared statements (SQL injection safe)
- **AJAX check-in** — Live ticket validation without page reload using Fetch API
- **QR scanning** — html5-qrcode library for camera-based ticket scanning
- **Dark mode** — Persists via localStorage across sessions

---

## Git Workflow

Each feature was developed on its own branch and submitted via Pull Request:

- `mahinul/scaffold` → Project structure
- `mahinul/core-setup` → Database, Auth, Router
- `mahinul/auth` → Login and registration
- `mahinul/organiser-dashboard` → Dashboard with charts
- `mahinul/events-crud` → Event management
- `mahinul/ticket-tiers` → Ticket tier management
- `mahinul/live-checkin` → AJAX check-in + QR scanner
- `mahinul/bookings-analytics` → Bookings and analytics
- `mahinul/management-features` → Discounts, refunds, reviews, announcements
- `mahinul/profile-venue` → Profile and venue browsing
- `mahinul/improvements` → Search, 404 page, documentation
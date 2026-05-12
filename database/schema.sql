-- ============================================================
-- Event Management & Ticketing System
-- Database Schema — Project 15, Web Technologies Course
--
-- SHARED FILE: All 4 group members run this exact file.
-- Run this in phpMyAdmin: Import tab → choose this file → Go
-- ============================================================

CREATE DATABASE IF NOT EXISTS event_platform
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE event_platform;

-- ============================================================
-- 1. users — every person on the platform
-- ============================================================
CREATE TABLE IF NOT EXISTS users (
    id           INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(100)    NOT NULL,
    email        VARCHAR(150)    NOT NULL UNIQUE,
    password_hash VARCHAR(255)   NOT NULL,
    phone        VARCHAR(20)     DEFAULT NULL,
    role         ENUM('attendee','organiser','venue_manager','admin') NOT NULL,
    profile_pic  VARCHAR(255)    DEFAULT NULL,
    is_active    TINYINT(1)      DEFAULT 1,
    created_at   TIMESTAMP       DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 2. organiser_profiles — extended info for organisers
-- ============================================================
CREATE TABLE IF NOT EXISTS organiser_profiles (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id          INT UNSIGNED NOT NULL UNIQUE,
    org_name         VARCHAR(150) NOT NULL,
    org_description  TEXT         DEFAULT NULL,
    org_logo_path    VARCHAR(255) DEFAULT NULL,
    website          VARCHAR(255) DEFAULT NULL,
    status           ENUM('pending','approved','suspended') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3. venues — venue profiles managed by venue managers
-- ============================================================
CREATE TABLE IF NOT EXISTS venues (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    manager_id  INT UNSIGNED NOT NULL,
    name        VARCHAR(150) NOT NULL,
    description TEXT         DEFAULT NULL,
    address     VARCHAR(255) NOT NULL,
    city        VARCHAR(100) NOT NULL,
    capacity    INT UNSIGNED NOT NULL,
    facilities  JSON         DEFAULT NULL,
    photos      JSON         DEFAULT NULL,
    is_active   TINYINT(1)   DEFAULT 1,
    created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (manager_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 4. venue_pricing — pricing rules per day type
-- ============================================================
CREATE TABLE IF NOT EXISTS venue_pricing (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    venue_id     INT UNSIGNED NOT NULL,
    day_type     ENUM('weekday','weekend','holiday') NOT NULL,
    price_per_day DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (venue_id) REFERENCES venues(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 5. venue_availability — calendar of availability per venue
-- ============================================================
CREATE TABLE IF NOT EXISTS venue_availability (
    id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    venue_id INT UNSIGNED NOT NULL,
    date     DATE         NOT NULL,
    status   ENUM('available','booked','blocked') DEFAULT 'available',
    event_id INT UNSIGNED DEFAULT NULL,
    note     VARCHAR(255) DEFAULT NULL,
    FOREIGN KEY (venue_id) REFERENCES venues(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 6. venue_booking_requests — organiser requests to book a venue
-- ============================================================
CREATE TABLE IF NOT EXISTS venue_booking_requests (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    venue_id            INT UNSIGNED NOT NULL,
    organiser_id        INT UNSIGNED NOT NULL,
    event_title_preview VARCHAR(200) DEFAULT NULL,
    requested_dates     JSON         NOT NULL,
    message             TEXT         DEFAULT NULL,
    status              ENUM('pending','approved','rejected') DEFAULT 'pending',
    manager_note        TEXT         DEFAULT NULL,
    submitted_at        TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (venue_id)      REFERENCES venues(id) ON DELETE CASCADE,
    FOREIGN KEY (organiser_id)  REFERENCES users(id)  ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 7. categories — event classification
-- ============================================================
CREATE TABLE IF NOT EXISTS categories (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL UNIQUE,
    description TEXT         DEFAULT NULL,
    icon        VARCHAR(50)  DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 8. events — all events created by organisers
-- ============================================================
CREATE TABLE IF NOT EXISTS events (
    id                   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    organiser_id         INT UNSIGNED NOT NULL,
    venue_id             INT UNSIGNED DEFAULT NULL,
    category_id          INT UNSIGNED DEFAULT NULL,
    title                VARCHAR(200) NOT NULL,
    description          TEXT         DEFAULT NULL,
    venue_name_override  VARCHAR(200) DEFAULT NULL,
    event_datetime       DATETIME     NOT NULL,
    end_datetime         DATETIME     NOT NULL,
    banner_image_path    VARCHAR(255) DEFAULT NULL,
    status               ENUM('draft','published','cancelled','completed') DEFAULT 'draft',
    is_featured          TINYINT(1)   DEFAULT 0,
    created_at           TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (organiser_id) REFERENCES users(id)       ON DELETE CASCADE,
    FOREIGN KEY (venue_id)     REFERENCES venues(id)      ON DELETE SET NULL,
    FOREIGN KEY (category_id)  REFERENCES categories(id)  ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 9. ticket_tiers — ticket types per event
-- ============================================================
CREATE TABLE IF NOT EXISTS ticket_tiers (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    event_id    INT UNSIGNED   NOT NULL,
    name        VARCHAR(100)   NOT NULL,
    description TEXT           DEFAULT NULL,
    price       DECIMAL(10,2)  NOT NULL,
    total_seats INT UNSIGNED   NOT NULL,
    sales_start DATETIME       DEFAULT NULL,
    sales_end   DATETIME       DEFAULT NULL,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 10. bookings — attendee ticket purchases
-- ============================================================
CREATE TABLE IF NOT EXISTS bookings (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    attendee_id    INT UNSIGNED  NOT NULL,
    event_id       INT UNSIGNED  NOT NULL,
    tier_id        INT UNSIGNED  NOT NULL,
    quantity       INT UNSIGNED  NOT NULL DEFAULT 1,
    total_price    DECIMAL(10,2) NOT NULL,
    ticket_code    VARCHAR(50)   NOT NULL UNIQUE,
    checked_in     TINYINT(1)    DEFAULT 0,
    checked_in_at  DATETIME      DEFAULT NULL,
    status         ENUM('active','cancelled','refunded') DEFAULT 'active',
    created_at     TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (attendee_id) REFERENCES users(id)         ON DELETE CASCADE,
    FOREIGN KEY (event_id)    REFERENCES events(id)        ON DELETE CASCADE,
    FOREIGN KEY (tier_id)     REFERENCES ticket_tiers(id)  ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 11. discount_codes — promo codes created by organisers
-- ============================================================
CREATE TABLE IF NOT EXISTS discount_codes (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    event_id     INT UNSIGNED   NOT NULL,
    organiser_id INT UNSIGNED   NOT NULL,
    code         VARCHAR(50)    NOT NULL UNIQUE,
    discount_pct DECIMAL(5,2)   NOT NULL,
    max_uses     INT UNSIGNED   DEFAULT NULL,
    uses_count   INT UNSIGNED   DEFAULT 0,
    valid_until  DATETIME       DEFAULT NULL,
    is_active    TINYINT(1)     DEFAULT 1,
    FOREIGN KEY (event_id)     REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (organiser_id) REFERENCES users(id)  ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 12. refund_requests — attendee refund requests
-- ============================================================
CREATE TABLE IF NOT EXISTS refund_requests (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id     INT UNSIGNED NOT NULL,
    attendee_id    INT UNSIGNED NOT NULL,
    reason         TEXT         NOT NULL,
    status         ENUM('pending','approved','rejected') DEFAULT 'pending',
    organiser_note TEXT         DEFAULT NULL,
    created_at     TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id)  REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (attendee_id) REFERENCES users(id)    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 13. event_reviews — attendee ratings and reviews
-- ============================================================
CREATE TABLE IF NOT EXISTS event_reviews (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    event_id        INT UNSIGNED NOT NULL,
    booking_id      INT UNSIGNED NOT NULL UNIQUE,
    attendee_id     INT UNSIGNED NOT NULL,
    rating          TINYINT UNSIGNED NOT NULL CHECK (rating BETWEEN 1 AND 5),
    review_text     TEXT         DEFAULT NULL,
    organiser_reply TEXT         DEFAULT NULL,
    created_at      TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id)    REFERENCES events(id)   ON DELETE CASCADE,
    FOREIGN KEY (booking_id)  REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (attendee_id) REFERENCES users(id)    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 14. follows — attendees following organisers
-- ============================================================
CREATE TABLE IF NOT EXISTS follows (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    attendee_id  INT UNSIGNED NOT NULL,
    organiser_id INT UNSIGNED NOT NULL,
    created_at   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_follow (attendee_id, organiser_id),
    FOREIGN KEY (attendee_id)  REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (organiser_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 15. announcements — event announcements to ticket holders
-- ============================================================
CREATE TABLE IF NOT EXISTS announcements (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    event_id     INT UNSIGNED NOT NULL,
    organiser_id INT UNSIGNED NOT NULL,
    title        VARCHAR(200) NOT NULL,
    body         TEXT         NOT NULL,
    sent_at      TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id)     REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (organiser_id) REFERENCES users(id)  ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 16. complaints — disputes submitted to admin
-- ============================================================
CREATE TABLE IF NOT EXISTS complaints (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    submitter_id INT UNSIGNED NOT NULL,
    against_id   INT UNSIGNED NOT NULL,
    event_id     INT UNSIGNED DEFAULT NULL,
    description  TEXT         NOT NULL,
    status       ENUM('open','resolved') DEFAULT 'open',
    admin_note   TEXT         DEFAULT NULL,
    created_at   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (submitter_id) REFERENCES users(id)   ON DELETE CASCADE,
    FOREIGN KEY (against_id)   REFERENCES users(id)   ON DELETE CASCADE,
    FOREIGN KEY (event_id)     REFERENCES events(id)  ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- All 16 tables created successfully.
-- Share this file with all 4 group members.
-- Each member imports it once in their own phpMyAdmin.
-- ============================================================

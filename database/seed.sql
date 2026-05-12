-- ============================================================
-- Event Management & Ticketing System
-- Seed Data — Organiser Role (Mahinul Islam)
-- Run AFTER schema.sql in phpMyAdmin
-- ============================================================

USE event_platform;

-- ============================================================
-- USERS (8 accounts)
-- ============================================================
INSERT INTO users (id, name, email, password_hash, phone, role, is_active) VALUES
(1, 'Platform Admin',   'admin@platform.local',    '$2b$10$ZedFQnzMO7B6EGh7jakuQ.2V0Ml6stRz5Wnp2/5r2OfmMQ0k7v67i', '01700000001', 'admin',          1),
(2, 'Venue Manager',    'vm@venue.local',           '$2b$10$EPSc.7h/UitSeYX3f408/erKsVAYOR1TtOlz/Uz.DyuEQ4U6i/1WO', '01700000002', 'venue_manager',  1),
(3, 'Mahinul Islam',    'mahinul@org.local',        '$2b$10$RA.4kDkAU6.2vDkxDfUHyejVnr.CxrQz8yC0o1kSi9m6TG3sk12b.', '01711223344', 'organiser',      1),
(4, 'Sara Ahmed',       'attendee1@test.local',     '$2b$10$DpxL8Svz0wdoX7SyKpJZueYL7pG7p5lriLNxxqGxOyEpOFKHEaBQO', '01722334455', 'attendee',       1),
(5, 'Rafiq Khan',       'attendee2@test.local',     '$2b$10$DpxL8Svz0wdoX7SyKpJZueYL7pG7p5lriLNxxqGxOyEpOFKHEaBQO', '01733445566', 'attendee',       1),
(6, 'Nusrat Jahan',     'attendee3@test.local',     '$2b$10$DpxL8Svz0wdoX7SyKpJZueYL7pG7p5lriLNxxqGxOyEpOFKHEaBQO', '01744556677', 'attendee',       1),
(7, 'Tanvir Hasan',     'attendee4@test.local',     '$2b$10$DpxL8Svz0wdoX7SyKpJZueYL7pG7p5lriLNxxqGxOyEpOFKHEaBQO', '01755667788', 'attendee',       1),
(8, 'Farhana Akter',    'attendee5@test.local',     '$2b$10$DpxL8Svz0wdoX7SyKpJZueYL7pG7p5lriLNxxqGxOyEpOFKHEaBQO', '01766778899', 'attendee',       1);

-- ============================================================
-- ORGANISER PROFILE (Mahinul — pre-approved)
-- ============================================================
INSERT INTO organiser_profiles (user_id, org_name, org_description, website, status) VALUES
(3, 'TechEvents BD',
   'Bangladesh''s premier technology event organiser. We create memorable experiences for developers, entrepreneurs and innovators.',
   'https://techeventsbd.com',
   'approved');

-- ============================================================
-- VENUE
-- ============================================================
INSERT INTO venues (id, manager_id, name, description, address, city, capacity, facilities, is_active) VALUES
(1, 2,
   'Chittagong Convention Center',
   'A premier event venue in the heart of Chittagong with state-of-the-art facilities.',
   '10 CDA Avenue, Agrabad', 'Chittagong', 600,
   '["AV Equipment","Parking","Catering","WiFi","Stage","Air Conditioning"]',
   1);

INSERT INTO venue_pricing (venue_id, day_type, price_per_day) VALUES
(1, 'weekday', 25000.00),
(1, 'weekend', 35000.00),
(1, 'holiday', 45000.00);

-- ============================================================
-- CATEGORIES
-- ============================================================
INSERT INTO categories (id, name, description, icon) VALUES
(1, 'Technology', 'Tech conferences, hackathons and developer meetups', 'bi-cpu'),
(2, 'Music',      'Concerts, live performances and music festivals',    'bi-music-note'),
(3, 'Business',   'Business summits, networking and entrepreneurship',  'bi-briefcase');

-- ============================================================
-- VENUE BOOKING REQUEST (approved — links organiser to venue)
-- ============================================================
INSERT INTO venue_booking_requests (id, venue_id, organiser_id, event_title_preview, requested_dates, message, status, manager_note) VALUES
(1, 1, 3,
   'Tech Innovators Summit 2026',
   '["2026-11-15"]',
   'We would like to host our annual technology summit at your venue. Expected attendance: 300+.',
   'approved',
   'Approved. Looking forward to hosting your event.');

-- ============================================================
-- EVENTS
-- ============================================================
INSERT INTO events (id, organiser_id, venue_id, category_id, title, description,
                    event_datetime, end_datetime, status, is_featured) VALUES
(1, 3, 1, 1,
   'Tech Innovators Summit 2026',
   'The biggest technology summit in Chittagong. Join us for inspiring keynotes, workshops, and networking with industry leaders. This year''s theme: AI and the Future of Work.',
   '2026-11-15 09:00:00', '2026-11-15 18:00:00',
   'published', 1),

(2, 3, NULL, 3,
   'Startup Networking Night',
   'An evening of networking for entrepreneurs and investors in Chittagong.',
   '2026-12-10 18:00:00', '2026-12-10 22:00:00',
   'draft', 0);

-- ============================================================
-- TICKET TIERS
-- ============================================================
INSERT INTO ticket_tiers (id, event_id, name, description, price, total_seats, sales_start, sales_end) VALUES
(1, 1, 'General',    'Full day access to all sessions and networking lunch', 50.00,  150, '2026-09-01 00:00:00', '2026-11-14 23:59:59'),
(2, 1, 'VIP',        'Priority seating, VIP lounge, speaker dinner',         150.00,  50, '2026-09-01 00:00:00', '2026-11-14 23:59:59'),
(3, 1, 'Early Bird', 'Limited early access tickets at special price',         35.00, 100, '2026-09-01 00:00:00', '2026-10-01 23:59:59'),
(4, 2, 'General',    'General admission',                                     30.00, 200, '2026-11-01 00:00:00', '2026-12-09 23:59:59');

-- ============================================================
-- BOOKINGS (20 bookings on Event 1 — mix of tiers and check-in status)
-- ============================================================
INSERT INTO bookings (id, attendee_id, event_id, tier_id, quantity, total_price, ticket_code, checked_in, checked_in_at, status) VALUES
( 1, 4, 1, 1, 1,  50.00, 'TIK-2026-A001', 1, '2026-11-15 09:12:00', 'active'),
( 2, 5, 1, 2, 1, 150.00, 'TIK-2026-A002', 1, '2026-11-15 09:18:00', 'active'),
( 3, 6, 1, 3, 1,  35.00, 'TIK-2026-A003', 1, '2026-11-15 09:25:00', 'active'),
( 4, 7, 1, 1, 2, 100.00, 'TIK-2026-A004', 1, '2026-11-15 09:31:00', 'active'),
( 5, 8, 1, 2, 1, 150.00, 'TIK-2026-A005', 1, '2026-11-15 09:44:00', 'active'),
( 6, 4, 1, 3, 1,  35.00, 'TIK-2026-A006', 1, '2026-11-15 10:02:00', 'active'),
( 7, 5, 1, 1, 1,  50.00, 'TIK-2026-A007', 1, '2026-11-15 10:15:00', 'active'),
( 8, 6, 1, 2, 1, 150.00, 'TIK-2026-A008', 1, '2026-11-15 10:28:00', 'active'),
( 9, 7, 1, 3, 1,  35.00, 'TIK-2026-A009', 1, '2026-11-15 10:45:00', 'active'),
(10, 8, 1, 1, 1,  50.00, 'TIK-2026-A010', 1, '2026-11-15 11:00:00', 'active'),
(11, 4, 1, 1, 1,  50.00, 'TIK-2026-A011', 0, NULL, 'active'),
(12, 5, 1, 3, 2,  70.00, 'TIK-2026-A012', 0, NULL, 'active'),
(13, 6, 1, 1, 1,  50.00, 'TIK-2026-A013', 0, NULL, 'active'),
(14, 7, 1, 2, 1, 150.00, 'TIK-2026-A014', 0, NULL, 'active'),
(15, 8, 1, 3, 1,  35.00, 'TIK-2026-A015', 0, NULL, 'active'),
(16, 4, 1, 1, 2, 100.00, 'TIK-2026-A016', 0, NULL, 'active'),
(17, 5, 1, 2, 1, 150.00, 'TIK-2026-A017', 0, NULL, 'active'),
(18, 6, 1, 1, 1,  50.00, 'TIK-2026-A018', 0, NULL, 'active'),
(19, 7, 1, 3, 1,  35.00, 'TIK-2026-A019', 0, NULL, 'refunded'),
(20, 8, 1, 1, 1,  50.00, 'TIK-2026-A020', 0, NULL, 'active');

-- ============================================================
-- DISCOUNT CODES
-- ============================================================
INSERT INTO discount_codes (event_id, organiser_id, code, discount_pct, max_uses, uses_count, valid_until, is_active) VALUES
(1, 3, 'EARLY30',  30.00, 100, 87, '2026-10-31 23:59:59', 1),
(1, 3, 'CSE2026',  15.00, NULL, 42, '2026-11-14 23:59:59', 1),
(1, 3, 'VIP10',    10.00, 50,   8, '2026-11-10 23:59:59', 0);

-- ============================================================
-- REFUND REQUESTS (3 pending — for the refund queue demo)
-- ============================================================
INSERT INTO refund_requests (booking_id, attendee_id, reason, status) VALUES
(14, 7, 'Schedule conflict — cannot attend on the event date.', 'pending'),
(16, 4, 'Travel plans cancelled due to emergency.', 'pending'),
(18, 6, 'Medical reasons — doctor advised rest.', 'pending');

-- ============================================================
-- EVENT REVIEWS (5 reviews for demo)
-- ============================================================
INSERT INTO event_reviews (event_id, booking_id, attendee_id, rating, review_text, organiser_reply) VALUES
(1,  1, 4, 5, 'Absolutely amazing event! The sessions were incredibly insightful and well organised. Will definitely attend again next year.', 'Thank you so much Sara! We are thrilled you enjoyed the summit.'),
(1,  2, 5, 4, 'Great event overall. The VIP lounge was a nice touch. Speaker lineup was impressive. Would have liked more networking time.', NULL),
(1,  3, 6, 5, 'Best tech event I have attended in Chittagong. Excellent organisation and world-class speakers.', NULL),
(1,  4, 7, 4, 'Very well organised. The venue was perfect and the food was excellent. Looking forward to the next one.', NULL),
(1,  5, 8, 3, 'Good event but felt a bit rushed. Some sessions ran over time which pushed others back. Still worth attending.', 'Thank you for the honest feedback. We will improve the scheduling next year.');

-- ============================================================
-- ANNOUNCEMENTS
-- ============================================================
INSERT INTO announcements (event_id, organiser_id, title, body) VALUES
(1, 3,
   'Important: Venue Parking Update',
   'Dear ticket holders, please note that additional parking has been arranged at the adjacent CDA parking lot. Doors open at 8:30 AM. Please bring a printed or digital copy of your ticket code. See you all on November 15!');

-- ============================================================
-- VENUE AVAILABILITY
-- ============================================================
INSERT INTO venue_availability (venue_id, date, status, event_id) VALUES
(1, '2026-11-15', 'booked', 1);

-- ============================================================
-- FOLLOWS (some attendees follow Mahinul's organiser account)
-- ============================================================
INSERT INTO follows (attendee_id, organiser_id) VALUES
(4, 3), (5, 3), (6, 3), (7, 3);

-- ============================================================
-- Seed complete.
--
-- LOGIN CREDENTIALS:
-- Admin:        admin@platform.local    /  Admin@123
-- Organiser:    mahinul@org.local       /  Organiser@123
-- Venue Mgr:    vm@venue.local          /  Venue@123
-- Attendees:    attendee1@test.local    /  Attendee@123
--               (same password for attendee2-5)
-- ============================================================

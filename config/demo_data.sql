SET FOREIGN_KEY_CHECKS = 0;

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `phone`, `role`, `is_active`) VALUES
(10, 'Rohit Venue Manager', 'rohit@emts.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uSc/V3ZOO', '01711000010', 'venue_manager', 1),
(11, 'Karim Organiser', 'karim@emts.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uSc/V3ZOO', '01711000011', 'organiser', 1);

INSERT INTO `venues` (`id`, `manager_id`, `name`, `description`, `address`, `city`, `capacity`, `facilities`, `photos`, `is_active`) VALUES
(1, 10, 'Bashundhara International Convention City', 'Largest convention center in Bangladesh. Perfect for mega conferences and exhibitions.', 'Bashundhara, Dhaka 1229', 'Dhaka', 5000, '["AV Equipment","Parking","Catering","WiFi","Air Conditioning","Stage","Security","Lighting Rig"]', '["venue1.jpg"]', 1),
(2, 10, 'Bangladesh National Museum Auditorium', 'Historic auditorium in the heart of Dhaka. Ideal for cultural events and seminars.', 'Shahbag, Dhaka 1000', 'Dhaka', 800, '["AV Equipment","Air Conditioning","Stage","Projector","Security"]', '["venue2.jpg"]', 1),
(3, 10, 'Chittagong City Corporation Convention Hall', 'Modern convention hall in Chittagong city center. Great for corporate events.', 'Anderkilla, Chittagong 4000', 'Chittagong', 1200, '["AV Equipment","Parking","WiFi","Air Conditioning","Catering","Security"]', '["venue3.jpg"]', 1),
(4, 10, 'Radisson Blu Chattogram Rooftop', 'Stunning rooftop venue with panoramic city views. Perfect for evening events.', 'Agrabad, Chittagong 4100', 'Chittagong', 300, '["WiFi","Stage","Lighting Rig","Security","Catering"]', '["venue4.jpg"]', 1),
(5, 10, 'Comilla Victoria College Auditorium', 'Historic college auditorium with classic architecture. Ideal for academic events.', 'Victoria College Road, Cumilla 3500', 'Cumilla', 600, '["AV Equipment","Stage","Projector","Air Conditioning"]', '["venue5.jpg"]', 1),
(6, 10, 'Comilla Garden Resort Event Space', 'Beautiful outdoor garden venue. Perfect for weddings and open-air concerts.', 'Kotbari, Cumilla 3503', 'Cumilla', 400, '["Parking","Catering","Lighting Rig","Security","Stage"]', '["venue6.jpg"]', 1),
(7, 10, 'Ocean Paradise Beach Convention Hall', 'Beachside convention hall with stunning sea views. Unique venue for special events.', 'Kolatoli, Cox\'s Bazar 4700', 'Cox\'s Bazar', 500, '["AV Equipment","Parking","Catering","WiFi","Air Conditioning","Security"]', '["venue7.jpg"]', 1),
(8, 10, 'Sayeman Heritage Ballroom', 'Elegant hotel ballroom with premium facilities. Perfect for galas and banquets.', 'Sayeman Beach Road, Cox\'s Bazar 4701', 'Cox\'s Bazar', 350, '["AV Equipment","Catering","WiFi","Air Conditioning","Stage","Lighting Rig","Security"]', '["venue8.jpg"]', 1);

INSERT INTO `venue_pricing` (`venue_id`, `day_type`, `price_per_day`) VALUES
(1, 'weekday', 150000), (1, 'weekend', 200000), (1, 'holiday', 250000),
(2, 'weekday', 30000),  (2, 'weekend', 45000),  (2, 'holiday', 55000),
(3, 'weekday', 50000),  (3, 'weekend', 70000),  (3, 'holiday', 85000),
(4, 'weekday', 40000),  (4, 'weekend', 60000),  (4, 'holiday', 75000),
(5, 'weekday', 20000),  (5, 'weekend', 30000),  (5, 'holiday', 38000),
(6, 'weekday', 25000),  (6, 'weekend', 35000),  (6, 'holiday', 45000),
(7, 'weekday', 60000),  (7, 'weekend', 80000),  (7, 'holiday', 100000),
(8, 'weekday', 55000),  (8, 'weekend', 75000),  (8, 'holiday', 90000);

INSERT INTO `venue_availability` (`venue_id`, `date`, `status`, `note`) VALUES
(1, '2025-06-05', 'booked',   'Tech Summit 2025'),
(1, '2025-06-10', 'booked',   'BD Startup Conference'),
(1, '2025-06-20', 'blocked',  'Maintenance'),
(2, '2025-06-08', 'booked',   'Cultural Night'),
(2, '2025-06-15', 'blocked',  'Private Event'),
(3, '2025-06-12', 'booked',   'Corporate Gala'),
(3, '2025-06-18', 'booked',   'Annual Meeting'),
(4, '2025-06-14', 'booked',   'Music Festival'),
(5, '2025-06-07', 'booked',   'Academic Seminar'),
(6, '2025-06-22', 'booked',   'Wedding Ceremony'),
(7, '2025-06-25', 'booked',   'Beach Concert'),
(8, '2025-06-28', 'booked',   'Gala Dinner');

INSERT INTO `venue_booking_requests` (`venue_id`, `organiser_id`, `event_title_preview`, `requested_dates`, `message`, `status`, `manager_note`) VALUES
(1, 11, 'Tech Summit 2025',         '["2025-06-05"]',              'We need the full convention center for a large tech conference.', 'approved', NULL),
(2, 11, 'Photography Exhibition',   '["2025-07-10","2025-07-11"]', 'Two-day photography exhibition for 200 attendees.', 'pending', NULL),
(3, 11, 'Corporate Annual Meeting', '["2025-06-12"]',              'Annual corporate meeting for 300 employees.', 'approved', NULL),
(4, 11, 'Music Night',              '["2025-06-14"]',              'Evening music event with live band performance.', 'approved', NULL),
(7, 11, 'Beach Wedding',            '["2025-07-20","2025-07-21"]', 'Two-day beach wedding celebration.', 'pending', NULL),
(5, 11, 'Art Workshop',             '["2025-06-30"]',              'Full day art workshop for students.', 'rejected', 'Venue already booked for that date.');

INSERT INTO `events` (`organiser_id`, `venue_id`, `title`, `description`, `event_datetime`, `end_datetime`, `status`) VALUES
(11, 1, 'Tech Summit 2025',         'Annual technology summit.',      '2025-06-05 09:00:00', '2025-06-05 18:00:00', 'published'),
(11, 3, 'Corporate Annual Meeting', 'Company annual general meeting.', '2025-06-12 10:00:00', '2025-06-12 17:00:00', 'published'),
(11, 4, 'Music Night',              'Live music evening event.',       '2025-06-14 19:00:00', '2025-06-14 23:00:00', 'published'),
(11, 2, 'Cultural Night',           'Traditional cultural showcase.',  '2025-06-08 18:00:00', '2025-06-08 22:00:00', 'published'),
(11, 6, 'Wedding Ceremony',         'Grand wedding celebration.',      '2025-06-22 16:00:00', '2025-06-22 23:00:00', 'published'),
(11, 7, 'Beach Concert',            'Open air beach concert.',         '2025-06-25 17:00:00', '2025-06-25 22:00:00', 'published');

SET FOREIGN_KEY_CHECKS = 1; 

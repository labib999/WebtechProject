use event_platform;

insert ignore into categories (name, description, icon) values
('education', 'seminars, workshops and learning events', 'education'),
('food', 'food festivals and food related events', 'food'),
('health', 'health, fitness and wellness events', 'health'),
('gaming', 'gaming tournaments and esports events', 'gaming'),
('art', 'art exhibitions and cultural events', 'art');

insert into events (organiser_id, venue_id, category_id, title, description, venue_name_override, event_datetime, end_datetime, status, is_featured) values
(3, 1, (select id from categories where name = 'technology'), 'ai conference dhaka 2026', 'a technology event about ai, machine learning and future innovation.', 'dhaka convention center', '2026-06-20 10:00:00', '2026-06-20 18:00:00', 'published', 1),
(3, 1, (select id from categories where name = 'music'), 'music night dhaka', 'a live music event with popular bangladeshi artists.', 'gulshan club', '2026-06-25 18:00:00', '2026-06-25 22:00:00', 'published', 0),
(3, 1, (select id from categories where name = 'business'), 'business expo 2026', 'a business networking event for students, founders and investors.', 'banani, dhaka', '2026-07-05 10:00:00', '2026-07-05 17:00:00', 'published', 0),
(3, 1, (select id from categories where name = 'education'), 'career development workshop', 'a workshop for students to improve cv writing, interview skills and career planning.', 'uttara community hall', '2026-07-12 09:00:00', '2026-07-12 15:00:00', 'published', 0),
(3, 1, (select id from categories where name = 'food'), 'dhaka food carnival', 'a food festival with local restaurants, street food and live entertainment.', 'bashundhara convention city', '2026-07-18 12:00:00', '2026-07-18 22:00:00', 'published', 0),
(3, 1, (select id from categories where name = 'health'), 'fitness and wellness fair', 'a health event with fitness sessions, wellness talks and medical checkup booths.', 'dhanmondi sports complex', '2026-08-02 08:00:00', '2026-08-02 16:00:00', 'published', 0),
(3, 1, (select id from categories where name = 'gaming'), 'esports battle 2026', 'a gaming tournament for students and esports teams.', 'mirpur indoor stadium', '2026-08-10 11:00:00', '2026-08-10 20:00:00', 'published', 0),
(3, 1, (select id from categories where name = 'art'), 'art and culture festival', 'an exhibition of painting, photography, music and cultural performances.', 'bangladesh shilpakala academy', '2026-08-20 10:00:00', '2026-08-20 19:00:00', 'published', 0);

insert into ticket_tiers (event_id, name, description, price, total_seats, sales_start, sales_end) values
((select id from events where title = 'ai conference dhaka 2026'), 'general', 'general entry ticket', 500.00, 150, '2026-05-01 00:00:00', '2026-06-19 23:59:59'),
((select id from events where title = 'ai conference dhaka 2026'), 'vip', 'vip entry ticket', 1500.00, 50, '2026-05-01 00:00:00', '2026-06-19 23:59:59'),
((select id from events where title = 'music night dhaka'), 'general', 'general entry ticket', 800.00, 200, '2026-05-01 00:00:00', '2026-06-24 23:59:59'),
((select id from events where title = 'music night dhaka'), 'vip', 'vip entry ticket', 2000.00, 60, '2026-05-01 00:00:00', '2026-06-24 23:59:59'),
((select id from events where title = 'business expo 2026'), 'early bird', 'discounted early ticket', 300.00, 100, '2026-05-01 00:00:00', '2026-07-04 23:59:59'),
((select id from events where title = 'business expo 2026'), 'general', 'general entry ticket', 600.00, 180, '2026-05-01 00:00:00', '2026-07-04 23:59:59'),
((select id from events where title = 'career development workshop'), 'student', 'student ticket', 250.00, 80, '2026-05-01 00:00:00', '2026-07-11 23:59:59'),
((select id from events where title = 'career development workshop'), 'general', 'general ticket', 400.00, 100, '2026-05-01 00:00:00', '2026-07-11 23:59:59'),
((select id from events where title = 'dhaka food carnival'), 'general', 'general entry ticket', 200.00, 300, '2026-05-01 00:00:00', '2026-07-17 23:59:59'),
((select id from events where title = 'dhaka food carnival'), 'premium', 'premium food coupon ticket', 700.00, 100, '2026-05-01 00:00:00', '2026-07-17 23:59:59'),
((select id from events where title = 'fitness and wellness fair'), 'general', 'general entry ticket', 350.00, 150, '2026-05-01 00:00:00', '2026-08-01 23:59:59'),
((select id from events where title = 'fitness and wellness fair'), 'premium', 'premium health checkup ticket', 900.00, 60, '2026-05-01 00:00:00', '2026-08-01 23:59:59'),
((select id from events where title = 'esports battle 2026'), 'audience', 'audience entry ticket', 300.00, 250, '2026-05-01 00:00:00', '2026-08-09 23:59:59'),
((select id from events where title = 'esports battle 2026'), 'player pass', 'player registration ticket', 1000.00, 80, '2026-05-01 00:00:00', '2026-08-09 23:59:59'),
((select id from events where title = 'art and culture festival'), 'general', 'general entry ticket', 250.00, 180, '2026-05-01 00:00:00', '2026-08-19 23:59:59'),
((select id from events where title = 'art and culture festival'), 'vip', 'vip cultural evening ticket', 1200.00, 40, '2026-05-01 00:00:00', '2026-08-19 23:59:59');

insert into bookings (attendee_id, event_id, tier_id, quantity, total_price, ticket_code, checked_in, checked_in_at, status) values
(4, (select id from events where title = 'ai conference dhaka 2026'), (select id from ticket_tiers where event_id = (select id from events where title = 'ai conference dhaka 2026') and name = 'general' limit 1), 1, 500.00, 'ATT-AI-001', 0, null, 'active'),
(5, (select id from events where title = 'music night dhaka'), (select id from ticket_tiers where event_id = (select id from events where title = 'music night dhaka') and name = 'vip' limit 1), 1, 2000.00, 'ATT-MUS-001', 0, null, 'active'),
(6, (select id from events where title = 'business expo 2026'), (select id from ticket_tiers where event_id = (select id from events where title = 'business expo 2026') and name = 'general' limit 1), 2, 1200.00, 'ATT-BUS-001', 0, null, 'active'),
(7, (select id from events where title = 'career development workshop'), (select id from ticket_tiers where event_id = (select id from events where title = 'career development workshop') and name = 'student' limit 1), 1, 250.00, 'ATT-CAR-001', 0, null, 'active'),
(8, (select id from events where title = 'dhaka food carnival'), (select id from ticket_tiers where event_id = (select id from events where title = 'dhaka food carnival') and name = 'premium' limit 1), 1, 700.00, 'ATT-FOOD-001', 0, null, 'active'),
(9, (select id from events where title = 'fitness and wellness fair'), (select id from ticket_tiers where event_id = (select id from events where title = 'fitness and wellness fair') and name = 'general' limit 1), 1, 350.00, 'ATT-HEALTH-001', 0, null, 'active'),
(10, (select id from events where title = 'esports battle 2026'), (select id from ticket_tiers where event_id = (select id from events where title = 'esports battle 2026') and name = 'audience' limit 1), 1, 300.00, 'ATT-GAME-001', 0, null, 'active'),
(4, (select id from events where title = 'art and culture festival'), (select id from ticket_tiers where event_id = (select id from events where title = 'art and culture festival') and name = 'general' limit 1), 1, 250.00, 'ATT-ART-001', 1, '2026-08-20 10:15:00', 'active');

insert into event_reviews (event_id, booking_id, attendee_id, rating, review_text, organiser_reply) values
((select id from events where title = 'ai conference dhaka 2026'), (select id from bookings where ticket_code = 'ATT-AI-001'), 4, 5, 'very informative event and the speakers were excellent.', null),
((select id from events where title = 'music night dhaka'), (select id from bookings where ticket_code = 'ATT-MUS-001'), 5, 4, 'the music performance was enjoyable and well organised.', null),
((select id from events where title = 'business expo 2026'), (select id from bookings where ticket_code = 'ATT-BUS-001'), 6, 5, 'great networking opportunity for young entrepreneurs.', null),
((select id from events where title = 'career development workshop'), (select id from bookings where ticket_code = 'ATT-CAR-001'), 7, 4, 'helpful workshop for career preparation.', null),
((select id from events where title = 'art and culture festival'), (select id from bookings where ticket_code = 'ATT-ART-001'), 4, 5, 'beautiful cultural program and art exhibition.', null);

insert into discount_codes (event_id, organiser_id, code, discount_pct, max_uses, uses_count, valid_until, is_active) values
((select id from events where title = 'ai conference dhaka 2026'), 3, 'AI20', 20.00, 100, 5, '2026-06-19 23:59:59', 1),
((select id from events where title = 'music night dhaka'), 3, 'MUSIC10', 10.00, 50, 8, '2026-06-24 23:59:59', 1),
((select id from events where title = 'business expo 2026'), 3, 'BIZ15', 15.00, 60, 10, '2026-07-04 23:59:59', 1),
((select id from events where title = 'dhaka food carnival'), 3, 'FOOD5', 5.00, 100, 12, '2026-07-17 23:59:59', 1),
((select id from events where title = 'esports battle 2026'), 3, 'GAME25', 25.00, 40, 4, '2026-08-09 23:59:59', 1);

insert ignore into follows (attendee_id, organiser_id) values
(4, 3),
(5, 3),
(6, 3),
(7, 3),
(8, 3),
(9, 3),
(10, 3);

insert into announcements (event_id, organiser_id, title, body) values
((select id from events where title = 'ai conference dhaka 2026'), 3, 'ai conference registration open', 'registration is now open for ai conference dhaka 2026.'),
((select id from events where title = 'music night dhaka'), 3, 'music night gate opening time', 'gate will open at 5:00 pm for all ticket holders.'),
((select id from events where title = 'business expo 2026'), 3, 'business expo speaker update', 'new speakers have been added to the business expo lineup.'),
((select id from events where title = 'dhaka food carnival'), 3, 'food carnival coupon update', 'premium ticket holders will receive special food coupons.'),
((select id from events where title = 'esports battle 2026'), 3, 'esports battle registration update', 'player registration will close one day before the event.');
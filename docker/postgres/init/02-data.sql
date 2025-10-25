-- Seed data for TICO application
TRUNCATE TABLE sessions, cases, schedule_exceptions, news, products, teams, users RESTART IDENTITY CASCADE;

INSERT INTO users (id, username, password, full_name, email, team, role, status, is_available, location, lunch_start, lunch_end, created_at, updated_at)
OVERRIDING SYSTEM VALUE VALUES
    (1, 'admin', '$argon2id$v=19$m=65536,t=4,p=3$MmpzVWgyODZ1WkxVVzUwQQ$JcPIxILgqUttaYgOKXXRcxdGEsYA3VkkfP4adYiExmk', 'Administrator', 'admin@tico.local', 'IT', 'admin', 'active', TRUE, 'Office', '12:00:00', '13:00:00', '2025-10-24 00:51:40', '2025-10-24 00:51:40'),
    (2, 'engineer1', '$argon2id$v=19$m=65536,t=4,p=3$TVE1TjRSYmp0OUtram5RNQ$EtCPDiN9HmAHmkNdQhAyGzEXUYpsLpR3VSVVXtmGo78', 'John Engineer', 'john@tico.local', 'Support', 'engineer', 'active', TRUE, 'Office', '12:00:00', '13:00:00', '2025-10-24 00:51:40', '2025-10-24 00:51:40'),
    (3, 'user1', '$argon2id$v=19$m=65536,t=4,p=3$Wm42Q1hENEpvQ3hXRWp4Vw$+roXtta4QntT2ZruiWYguQ23ztTbpcORXrnZjxorYWg', 'Jane User', 'jane@tico.local', 'Support', 'user', 'active', TRUE, 'Office', '12:00:00', '13:00:00', '2025-10-24 00:51:40', '2025-10-24 00:51:40'),
    (4, 'sarah_tech', '$argon2id$v=19$m=65536,t=4,p=3$S0ZZMGUzMmRxUzNMclU0aQ$+S3g9RCZRO6wOnEC791hRzK63gTOnClZzMrktvUiSHM', 'Sarah Johnson', 'sarah.johnson@tico.local', 'Support', 'engineer', 'active', TRUE, 'Remote', '13:00:00', '14:00:00', '2025-10-24 04:17:16', '2025-10-24 04:17:16'),
    (5, 'mike_senior', '$argon2id$v=19$m=65536,t=4,p=3$ckliUXVLQ0FULy56T3VGRg$FPyvgNixawsCVhl9EjgRJA/3Pw3T6EhEW3G45ANHieo', 'Michael Chen', 'mike.chen@tico.local', 'Engineering', 'senior_engineer', 'active', TRUE, 'Office', '12:30:00', '13:30:00', '2025-10-24 04:17:16', '2025-10-24 04:17:16'),
    (6, 'lisa_qa', '$argon2id$v=19$m=65536,t=4,p=3$a2VjR0sub2RUeXRkWmdCZQ$BUhx8IHPgHLnhooHORJUHgj/VjUaQRC5499ERn3bHF4', 'Lisa Rodriguez', 'lisa.rodriguez@tico.local', 'QA', 'qa_engineer', 'active', TRUE, 'Office', '12:00:00', '13:00:00', '2025-10-24 04:17:16', '2025-10-24 04:17:16'),
    (7, 'david_mgr', '$argon2id$v=19$m=65536,t=4,p=3$U0x5TVJSbHhYeUlKWGwyMg$LN99jIwrW+aWATDdaOJNwayuY5RoDJphGara+6HAVqI', 'David Smith', 'david.smith@tico.local', 'Management', 'manager', 'active', TRUE, 'Office', '12:00:00', '13:00:00', '2025-10-24 04:17:16', '2025-10-24 04:17:16'),
    (8, 'anna_it', '$argon2id$v=19$m=65536,t=4,p=3$Y1h0bUoyRTNLd1JoaWRFeQ$zqrRo8hM+KXoIgWTq0BuCqeCtqL5zzhGsx6CUFLljDA', 'Anna Wilson', 'anna.wilson@tico.local', 'IT', 'engineer', 'active', TRUE, 'Office', '12:00:00', '13:00:00', '2025-10-24 04:17:16', '2025-10-24 04:17:16');

SELECT setval(pg_get_serial_sequence('users', 'id'), COALESCE((SELECT MAX(id) FROM users), 1));

INSERT INTO teams (id, name, description, active, created_at)
OVERRIDING SYSTEM VALUE VALUES
    (1, 'Support', 'Technical Support Team', TRUE, '2025-10-24 04:17:16'),
    (2, 'IT', 'Information Technology Team', TRUE, '2025-10-24 04:17:16'),
    (3, 'Engineering', 'Product Engineering Team', TRUE, '2025-10-24 04:17:16'),
    (4, 'QA', 'Quality Assurance Team', TRUE, '2025-10-24 04:17:16'),
    (5, 'Management', 'Management Team', TRUE, '2025-10-24 04:17:16'),
    (6, 'Support', 'Technical Support Team', TRUE, '2025-10-24 04:17:35'),
    (7, 'IT', 'Information Technology Team', TRUE, '2025-10-24 04:17:35'),
    (8, 'Engineering', 'Product Engineering Team', TRUE, '2025-10-24 04:17:35'),
    (9, 'QA', 'Quality Assurance Team', TRUE, '2025-10-24 04:17:35'),
    (10, 'Management', 'Management Team', TRUE, '2025-10-24 04:17:35');

SELECT setval(pg_get_serial_sequence('teams', 'id'), COALESCE((SELECT MAX(id) FROM teams), 1));

INSERT INTO products (id, name, description, active, created_at)
OVERRIDING SYSTEM VALUE VALUES
    (1, 'HP LaserJet Pro', 'Professional laser printing solutions', TRUE, '2025-10-24 04:17:16'),
    (2, 'HP DeskJet Plus', 'All-in-one inkjet printers for home and office', TRUE, '2025-10-24 04:17:16'),
    (3, 'HP PageWide Pro', 'High-speed business inkjet printers', TRUE, '2025-10-24 04:17:16'),
    (4, 'HP OfficeJet Pro', 'Professional color inkjet printers', TRUE, '2025-10-24 04:17:16'),
    (5, 'HP Envy Photo', 'Photo printing and creative projects', TRUE, '2025-10-24 04:17:16'),
    (6, 'HP Smart Tank', 'Refillable ink tank printers', TRUE, '2025-10-24 04:17:16'),
    (7, 'HP Color LaserJet', 'Professional color laser printers', TRUE, '2025-10-24 04:17:16'),
    (8, 'HP DesignJet', 'Large format printing solutions', TRUE, '2025-10-24 04:17:16'),
    (9, 'HP LaserJet Pro', 'Professional laser printing solutions', TRUE, '2025-10-24 04:17:35'),
    (10, 'HP DeskJet Plus', 'All-in-one inkjet printers for home and office', TRUE, '2025-10-24 04:17:35'),
    (11, 'HP PageWide Pro', 'High-speed business inkjet printers', TRUE, '2025-10-24 04:17:35'),
    (12, 'HP OfficeJet Pro', 'Professional color inkjet printers', TRUE, '2025-10-24 04:17:35'),
    (13, 'HP Envy Photo', 'Photo printing and creative projects', TRUE, '2025-10-24 04:17:35'),
    (14, 'HP Smart Tank', 'Refillable ink tank printers', TRUE, '2025-10-24 04:17:35'),
    (15, 'HP Color LaserJet', 'Professional color laser printers', TRUE, '2025-10-24 04:17:35'),
    (16, 'HP DesignJet', 'Large format printing solutions', TRUE, '2025-10-24 04:17:35');

SELECT setval(pg_get_serial_sequence('products', 'id'), COALESCE((SELECT MAX(id) FROM products), 1));

INSERT INTO news (id, title, content, news_type, priority, author_id, published, expires_at, created_at)
OVERRIDING SYSTEM VALUE VALUES
    (1, 'New HP LaserJet Pro Series Released', 'We are excited to announce the launch of the new HP LaserJet Pro 400 series. Enhanced security features and improved print speeds make this our best professional printer yet. Training sessions will be scheduled for all support staff.', 'announcement', 'High', 1, TRUE, '2025-11-24 03:17:16', '2025-10-23 03:17:16'),
    (2, 'System Maintenance Window - This Weekend', 'Please note that our internal systems will undergo maintenance this Saturday from 2 AM to 6 AM EST. During this time, case management system may be unavailable. Plan your work accordingly.', 'alert', 'Critical', 1, TRUE, '2025-10-26 02:17:16', '2025-10-23 02:17:16'),
    (3, 'Q4 Performance Review Cycle Begins', 'The Q4 performance review cycle has officially started. Please ensure all case documentation is up to date and schedule meetings with your team leads by the end of next week.', 'general', 'Normal', 4, TRUE, NULL, '2025-10-18 13:17:16'),
    (4, 'New Troubleshooting Guide Available', 'A comprehensive troubleshooting guide for WiFi connectivity issues has been published in our knowledge base. This covers the most common scenarios we encounter with HP wireless printers.', 'general', 'Normal', 2, TRUE, NULL, '2025-10-18 05:17:16'),
    (5, 'Customer Satisfaction Survey Results', 'Great news! Our customer satisfaction scores have improved by 15% this quarter. Special thanks to the support team for their dedication to excellent customer service.', 'general', 'Normal', 4, TRUE, NULL, '2025-10-19 08:17:16');

SELECT setval(pg_get_serial_sequence('news', 'id'), COALESCE((SELECT MAX(id) FROM news), 1));

INSERT INTO cases (id, case_number, title, description, severity, priority, status, assigned_to, product_id, customer_name, created_by, created_at, updated_at)
OVERRIDING SYSTEM VALUE VALUES
    (1, 'HP-2024-001', 'Printer not responding to print jobs', 'Customer reports that HP LaserJet Pro 400 is not processing print jobs from Windows 11. Jobs appear in queue but never print.', 'High', 'High', 'Open', 2, 4, 'John Customer Corp', 3, '2025-10-04 04:17:16', '2025-10-23 19:17:16'),
    (2, 'HP-2024-002', 'Paper jam error persists after clearing', 'HP DeskJet Plus 4155 shows paper jam error even after clearing all paper. Error code 13.20.00 displayed.', 'Critical', 'Critical', 'In Progress', 7, 4, 'Tech Solutions LLC', 6, '2025-10-16 04:17:16', '2025-10-23 06:17:16'),
    (3, 'HP-2024-003', 'Print quality issues - streaking', 'HP Color LaserJet Pro M479 producing prints with vertical streaks. Toner levels appear normal.', 'Normal', 'Medium', 'Open', 1, 3, 'ABC Marketing', 6, '2025-10-10 04:17:16', '2025-10-22 12:17:16'),
    (4, 'HP-2024-004', 'WiFi connection drops frequently', 'HP OfficeJet Pro 9015 loses WiFi connection every few hours. Requires manual reconnection.', 'High', 'High', 'Escalated', 1, 2, 'Small Business Inc', 6, '2025-10-13 04:17:16', '2025-10-23 17:17:16'),
    (5, 'HP-2024-005', 'Scanner not detected by software', 'HP Envy Photo 7855 scanner function not recognized by HP Smart app on macOS Sonoma.', 'Normal', 'Medium', 'Resolved', 2, 7, 'Home User', 6, '2025-10-08 04:17:16', '2025-10-21 08:17:16'),
    (6, 'HP-2024-006', 'Ink system failure after refill', 'HP Smart Tank 7005 showing ink system failure after customer refilled tanks.', 'Critical', 'Critical', 'Open', 6, 8, 'Educational Services', 7, '2025-10-04 04:17:16', '2025-10-23 02:17:16'),
    (7, 'HP-2024-007', 'Large format print alignment issues', 'HP DesignJet T650 producing misaligned prints on A1 paper size. Calibration attempted.', 'High', 'High', 'In Progress', 4, 2, 'Architecture Firm', 2, '2025-09-29 04:17:16', '2025-10-23 16:17:16'),
    (8, 'HP-2024-008', 'Duplex printing mechanism jammed', 'HP PageWide Pro 577 duplex unit stuck. Manual attempts to clear unsuccessful.', 'High', 'High', 'Open', 7, 3, 'Legal Services', 6, '2025-10-12 04:17:16', '2025-10-22 04:17:16'),
    (9, 'HP-2024-009', 'Complete system failure - production printer offline', 'Main production LaserJet Pro 9015 completely unresponsive. Error code 49.4C02 displayed. Business operations severely impacted.', 'Critical', 'Critical', 'Open', 2, 8, 'Acme Manufacturing Corp', NULL, '2025-10-23 08:43:17', '2025-10-23 20:43:17'),
    (10, 'HP-2024-010', 'Security breach - printer accessible from external network', 'OfficeJet Pro X Series detected sending data to unknown external IP. Potential security vulnerability discovered.', 'Critical', 'Critical', 'Escalated', 2, 1, 'SecureBank Financial', NULL, '2025-10-22 10:43:17', '2025-10-23 12:43:17'),
    (11, 'HP-2024-011', 'Network printer queue backing up', 'LaserJet Enterprise MFP M528 processing jobs extremely slowly. Print queue has 200+ jobs backed up.', 'High', 'High', 'In Progress', 8, 3, 'GlobalTech Solutions', NULL, '2025-10-21 15:43:17', '2025-10-23 16:43:17'),
    (12, 'HP-2024-012', 'Color calibration severely off', 'HP DesignJet T1700 producing colors way off specification. Marketing materials unusable.', 'High', 'High', 'Open', 2, 6, 'Creative Design Studio', NULL, '2025-10-23 14:43:17', '2025-10-23 17:43:17'),
    (13, 'HP-2024-013', 'Frequent paper jams in tray 2', 'LaserJet Pro 4301dw constantly jamming in tray 2 with standard 20lb paper.', 'High', 'High', 'Open', 4, 3, 'MidSize Business Corp', NULL, '2025-10-20 15:43:17', '2025-10-23 19:43:17'),
    (14, 'HP-2024-014', 'Toner replacement procedure needed', 'Customer needs guidance on replacing toner cartridge in LaserJet Pro M404dn.', 'Normal', 'Low', 'Open', 8, 3, 'Small Office Solutions', NULL, '2025-10-22 12:43:17', '2025-10-23 10:43:17'),
    (15, 'HP-2024-015', 'Setup wireless printing for new laptops', 'Need to configure 5 new Dell laptops to print to existing HP OfficeJet Pro 9010.', 'Normal', 'Medium', 'In Progress', 8, 2, 'Growing Startup Inc', NULL, '2025-10-19 11:43:17', '2025-10-23 15:43:17');

SELECT setval(pg_get_serial_sequence('cases', 'id'), COALESCE((SELECT MAX(id) FROM cases), 1));

INSERT INTO schedule_exceptions (id, user_id, exception_type, date_from, date_to, all_day, start_time, end_time, reason, created_at)
OVERRIDING SYSTEM VALUE VALUES
    (1, 7, 'Training', '2025-09-03', '2025-09-07', FALSE, '15:33:00', '17:50:00', 'Scheduled Training - automated entry', '2025-10-24 04:17:16'),
    (2, 5, 'Meeting', '2025-09-30', '2025-10-04', TRUE, NULL, NULL, 'Scheduled Meeting - automated entry', '2025-10-24 04:17:16'),
    (3, 7, 'Sick Leave', '2025-09-14', '2025-09-18', TRUE, NULL, NULL, 'Scheduled Sick Leave - automated entry', '2025-10-24 04:17:16'),
    (4, 5, 'On-call', '2025-09-10', '2025-09-11', FALSE, '09:20:00', '19:51:00', 'Scheduled On-call - automated entry', '2025-10-24 04:17:16'),
    (5, 3, 'Training', '2025-09-01', '2025-09-02', FALSE, '14:07:00', '20:50:00', 'Scheduled Training - automated entry', '2025-10-24 04:17:16'),
    (6, 2, 'Personal', '2025-09-24', '2025-09-28', FALSE, '14:02:00', '20:19:00', 'Scheduled Personal - automated entry', '2025-10-24 04:17:16'),
    (7, 2, 'Personal', '2025-09-25', '2025-09-29', FALSE, '16:14:00', '19:11:00', 'Scheduled Personal - automated entry', '2025-10-24 04:17:16'),
    (8, 2, 'Vacation', '2025-10-18', '2025-10-23', TRUE, NULL, NULL, 'Scheduled Vacation - automated entry', '2025-10-24 04:17:16'),
    (9, 1, 'Sick Leave', '2025-09-20', '2025-09-21', FALSE, '14:07:00', '20:42:00', 'Scheduled Sick Leave - automated entry', '2025-10-24 04:17:16'),
    (10, 3, 'On-call', '2025-10-01', '2025-10-03', FALSE, '12:08:00', '20:40:00', 'Scheduled On-call - automated entry', '2025-10-24 04:17:16'),
    (11, 3, 'Sick Leave', '2025-09-24', '2025-09-25', TRUE, NULL, NULL, 'Scheduled Sick Leave - automated entry', '2025-10-24 04:17:16'),
    (12, 8, 'Sick Leave', '2025-08-27', '2025-08-31', FALSE, '11:53:00', '20:15:00', 'Scheduled Sick Leave - automated entry', '2025-10-24 04:17:16'),
    (13, 6, 'Vacation', '2025-09-16', '2025-09-21', TRUE, NULL, NULL, 'Scheduled Vacation - automated entry', '2025-10-24 04:17:16'),
    (14, 6, 'Meeting', '2025-09-18', '2025-09-23', TRUE, NULL, NULL, 'Scheduled Meeting - automated entry', '2025-10-24 04:17:16'),
    (15, 1, 'Meeting', '2025-09-16', '2025-09-20', FALSE, '12:13:00', '20:46:00', 'Scheduled Meeting - automated entry', '2025-10-24 04:17:16'),
    (16, 1, 'Sick Leave', '2025-09-12', '2025-09-17', FALSE, '10:50:00', '17:00:00', 'Scheduled Sick Leave - automated entry', '2025-10-24 04:17:35'),
    (17, 4, 'Meeting', '2025-10-08', '2025-10-09', FALSE, '15:50:00', '20:54:00', 'Scheduled Meeting - automated entry', '2025-10-24 04:17:35'),
    (18, 8, 'Sick Leave', '2025-10-15', '2025-10-16', FALSE, '13:54:00', '20:58:00', 'Scheduled Sick Leave - automated entry', '2025-10-24 04:17:35'),
    (19, 6, 'Personal', '2025-10-15', '2025-10-20', FALSE, '15:26:00', '18:59:00', 'Scheduled Personal - automated entry', '2025-10-24 04:17:35'),
    (20, 3, 'On-call', '2025-09-13', '2025-09-16', TRUE, NULL, NULL, 'Scheduled On-call - automated entry', '2025-10-24 04:17:35'),
    (21, 5, 'Training', '2025-09-30', '2025-10-05', TRUE, NULL, NULL, 'Scheduled Training - automated entry', '2025-10-24 04:17:35'),
    (22, 7, 'Sick Leave', '2025-09-05', '2025-09-06', FALSE, '10:22:00', '18:26:00', 'Scheduled Sick Leave - automated entry', '2025-10-24 04:17:35'),
    (23, 2, 'Vacation', '2025-09-14', '2025-09-16', TRUE, NULL, NULL, 'Scheduled Vacation - automated entry', '2025-10-24 04:17:35'),
    (24, 7, 'Personal', '2025-09-05', '2025-09-09', TRUE, NULL, NULL, 'Scheduled Personal - automated entry', '2025-10-24 04:17:35'),
    (25, 4, 'Meeting', '2025-09-10', '2025-09-12', FALSE, '13:22:00', '19:48:00', 'Scheduled Meeting - automated entry', '2025-10-24 04:17:35'),
    (26, 3, 'Meeting', '2025-09-04', '2025-09-06', TRUE, NULL, NULL, 'Scheduled Meeting - automated entry', '2025-10-24 04:17:35'),
    (27, 6, 'On-call', '2025-10-08', '2025-10-10', TRUE, NULL, NULL, 'Scheduled On-call - automated entry', '2025-10-24 04:17:35'),
    (28, 7, 'Sick Leave', '2025-09-07', '2025-09-08', FALSE, '14:04:00', '17:36:00', 'Scheduled Sick Leave - automated entry', '2025-10-24 04:17:35'),
    (29, 3, 'Vacation', '2025-09-02', '2025-09-04', FALSE, '13:02:00', '19:27:00', 'Scheduled Vacation - automated entry', '2025-10-24 04:17:35'),
    (30, 3, 'Sick Leave', '2025-08-25', '2025-08-26', TRUE, NULL, NULL, 'Scheduled Sick Leave - automated entry', '2025-10-24 04:17:35');

SELECT setval(pg_get_serial_sequence('schedule_exceptions', 'id'), COALESCE((SELECT MAX(id) FROM schedule_exceptions), 1));

-- Sessions table intentionally left empty at seed time


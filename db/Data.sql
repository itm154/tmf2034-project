-- Person
INSERT INTO Person (person_id, person_name, person_contact, person_dob, person_gender) VALUES
(1, 'Jordan', 'jwalter@gmail.com', '2005-01-01', 'Male'),
(2, 'Ashrul', 'ashrul@gmail.com', '2005-02-02', 'Male'),
(3, 'Ariel', 'ariel@gmail.com', '2005-03-03', 'Male'),
(4, 'Jiani', 'jiani@gmail.com', '2005-04-04', 'Female'),
(5, 'Angelina', 'angelina@gmail.com', '2005-05-05', 'Female'),
(6, 'John Doe', 'john@gmail.com', '2000-01-01', 'Male'),
(7, 'Jane Doe', 'jane@gmail.com', '2000-02-02', 'Female'),
(8, 'Ahmad', 'ahmad@gmail.com', '2000-02-02', 'Male'),
(9, 'Ali', 'ali@gmail.com', '2000-04-04', 'Male'),
(10, 'Amanda', 'aman@gmail.com', '2000-05-05', 'Female');

-- NOTE: This is already inserted in the schema file
-- Membership_Type
-- INSERT INTO Membership_Type (membership_type_id, type_name, monthly_fee) VALUES
-- (1, 'Basic', 50.00),
-- (2, 'Premium', 80.00),
-- (3, 'Gold', 120.00);

-- Program_Category
INSERT INTO Program_Category (category_id, category_name) VALUES
(4, 'Bodybuilding'),
(2, 'Cardio'),
(3, 'Crossfit'),
(1, 'Rhythmic Excercise');

-- Notification
INSERT INTO Notification (notification_id, notification_content, notification_datetime) VALUES
(1, 'test notification 1', '2026-01-02 06:15:48'),
(2, 'test notification 2', '2026-01-02 06:15:53'),
(3, 'The gym will undergo maintenance on 4th January 2026', '2026-01-02 06:16:20'),
(4, 'Members with high class attendance will be given a reward!', '2026-01-02 06:16:48');

-- Member
INSERT INTO Member (person_id, membership_type_id, membership_status, membership_start_date) VALUES
(1, 3, 'Active', '2026-01-02'),
(2, 2, 'Active', '2026-01-02'),
(3, 3, 'Active', '2026-01-02'),
(4, 1, 'Active', '2026-01-02'),
(5, 3, 'Active', '2026-01-02');

-- Trainer
INSERT INTO Trainer (person_id, trainer_specialization, trainer_cert_lvl) VALUES
(6, 'Yoga', 'Certified Yoga Instructor'),
(7, 'Cardio', 'Level 2'),
(8, 'Weightlifting', 'National Level Weightlifter'),
(9, 'Pilates', 'Certified Pilater Trainer'),
(10, 'Crossfit', 'Level 3');

-- Program
INSERT INTO Program (program_id, program_name, program_duration_weeks, program_fee, category_id) VALUES
(1, 'Beginner Weightlifting', 1, 50.00, 4),
(2, 'Quick Everyday Cardio', 2, 100.00, 2),
(3, 'Zumba', 3, 30.00, 1);

-- Trainer_Program_History
INSERT INTO Trainer_Program_History (history_id, trainer_person_id, program_id, start_date, end_date) VALUES
(1, 8, 1, '2026-01-02', NULL),
(2, 7, 2, '2026-01-02', NULL),
(3, 10, 2, '2026-01-02', NULL),
(4, 6, 3, '2026-01-02', NULL);

-- Enrolment
INSERT INTO Enrolment (enrolment_id, enrolment_date, program_id, member_person_id) VALUES
(1, '2026-01-02', 1, 3),
(2, '2026-01-02', 1, 2),
(3, '2026-01-02', 3, 1),
(4, '2026-01-02', 2, 5),
(5, '2026-01-02', 2, 4),
(7, '2026-01-02', 2, 2),
(8, '2026-01-02', 1, 1);

-- Invoice
INSERT INTO Invoice (invoice_id, invoice_date, invoice_amount, invoice_payment_method, enrolment_id) VALUES
(1, '2026-01-02', 50.00, 'Card', 1),
(2, '2026-01-02', 50.00, 'Cash', 2),
(3, '2026-01-02', 30.00, 'Duitnow', 3),
(4, '2026-01-02', 100.00, 'Duitnow', 4),
(5, '2026-01-02', 100.00, 'Card', 5),
(7, '2026-01-02', 100.00, 'Card', 7),
(8, '2026-01-02', 50.00, 'Card', 8);

-- Class
INSERT INTO Class (class_id, class_datetime, class_status, history_id) VALUES
(1, '2026-01-04 12:00:00', 'Active', 1),
(2, '2026-01-05 12:00:00', 'Active', 1),
(3, '2026-01-06 12:00:00', 'Active', 1),
(4, '2026-01-04 08:00:00', 'Active', 2),
(5, '2026-01-05 08:00:00', 'Active', 2),
(6, '2026-01-07 08:00:00', 'Active', 3),
(7, '2026-01-12 10:00:00', 'Active', 4),
(8, '2026-01-13 10:30:00', 'Active', 4),
(9, '2026-01-07 12:00:00', 'Cancelled', 1);

-- Attendance
INSERT INTO Attendance (attendance_id, person_id, class_id, attendance_status) VALUES
(1, 1, 8, 'Attended'),
(2, 1, 7, 'Attended'),
(3, 5, 6, 'Attended'),
(4, 4, 6, 'Attended'),
(5, 3, 3, 'Attended'),
(7, 2, 3, 'Absent'),
(8, 2, 6, 'Attended'),
(9, 3, 2, 'Absent'),
(10, 2, 2, 'Absent'),
(11, 1, 2, 'Absent'),
(12, 3, 1, 'Absent'),
(13, 2, 1, 'Absent'),
(14, 1, 1, 'Absent'),
(15, 5, 4, 'Absent'),
(16, 4, 4, 'Attended'),
(17, 2, 4, 'Absent'),
(18, 1, 3, 'Absent'),
(19, 3, 9, 'Absent'),
(20, 2, 9, 'Absent'),
(21, 1, 9, 'Absent');

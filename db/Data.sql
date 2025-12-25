-- DUMMY DATA SCRIPT

-- Person (15 records)
INSERT INTO Person (person_id, person_name, person_contact, person_dob, person_gender) VALUES
(1, 'John Doe', 'john.doe@example.com', '1990-05-15', 'Male'),
(2, 'Jane Smith', 'jane.smith@example.com', '1988-09-20', 'Female'),
(3, 'Peter Jones', 'peter.jones@example.com', '1995-02-10', 'Male'),
(4, 'Mary Williams', 'mary.w@example.com', '2000-11-30', 'Female'),
(5, 'David Brown', 'd.brown@example.com', '1985-07-22', 'Male'),
(6, 'Patricia Garcia', 'patty.g@example.com', '1992-04-18', 'Female'),
(7, 'Michael Miller', 'mike.m@example.com', '1998-01-05', 'Male'),
(8, 'Linda Davis', 'l.davis@example.com', '1989-08-12', 'Female'),
(9, 'Robert Wilson', 'robert.w@example.com', '1993-06-25', 'Male'),
(10, 'Jennifer Moore', 'jen.moore@example.com', '1996-03-03', 'Female'),
(11, 'Charles White', 'charles.w@example.com', '1991-12-01', 'Male'),
(12, 'Susan Harris', 'susan.h@example.com', '1994-10-11', 'Female'),
(13, 'Joseph Clark', 'joseph.c@example.com', '1987-04-02', 'Male'),
(14, 'Karen Lewis', 'karen.l@example.com', '1999-08-28', 'Female'),
(15, 'Daniel Walker', 'dan.w@example.com', '1997-05-19', 'Male');

-- Membership_Type
INSERT INTO Membership_Type (type_name, monthly_fee) VALUES
('Basic', 50.00),
('Premium', 80.00),
('Gold', 120.00);

-- Member (8 members)
INSERT INTO Member (person_id, membership_type_id, membership_status, membership_start_date) VALUES
(1, 1, 'Active', '2023-01-15'),
(2, 2, 'Active', '2022-11-20'),
(3, 1, 'Inactive', '2023-05-10'),
(4, 3, 'Suspended', '2023-02-01'),
(8, 2, 'Active', '2023-04-01'),
(9, 1, 'Active', '2023-08-11'),
(11, 3, 'Active', '2023-09-01'),
(12, 2, 'Inactive', '2023-07-20');

-- Trainer (5 trainers)
INSERT INTO Trainer (person_id, trainer_specialization, trainer_cert_lvl) VALUES
(5, 'Weightlifting', 'Certified Master Trainer'),
(6, 'Yoga', 'RYT 500'),
(7, 'Cardio & HIIT', 'ACE Certified'),
(10, 'Pilates', 'Stott Pilates Certified'),
(13, 'CrossFit', 'CrossFit Level 2');

-- Program_Category
INSERT INTO Program_Category (category_name) VALUES
('Strength Training'),
('Cardiovascular'),
('Mind & Body'),
('Functional Fitness');

-- Program (6 programs)
INSERT INTO Program (program_name, program_duration_weeks, program_fee, category_id) VALUES
('Beginner Strength', 8, 200.00, 1),
('Advanced Weightlifting', 12, 350.00, 1),
('Yoga Flow', 6, 150.00, 3),
('HIIT Blast', 4, 100.00, 2),
('CrossFit Intro', 10, 300.00, 4),
('Mat Pilates', 6, 180.00, 3);


-- Trainer_Program_History
INSERT INTO Trainer_Program_History (trainer_person_id, program_id, start_date, end_date) VALUES
(5, 2, '2023-01-01', NULL),        -- David teaches Advanced Weightlifting
(7, 4, '2023-02-01', '2023-08-01'),-- Michael teaches HIIT
(6, 3, '2023-03-01', NULL),        -- Patricia teaches Yoga
(5, 1, '2023-09-01', NULL),        -- David also teaches Beginner Strength
(13, 5, '2023-05-01', NULL),       -- Joseph teaches CrossFit
(10, 6, '2023-06-01', NULL),       -- Jennifer teaches Pilates
(7, 4, '2023-10-01', NULL);       -- Michael teaches HIIT again

-- Enrolment (6 enrolments)
INSERT INTO Enrolment (enrolment_date, program_id, member_person_id) VALUES
('2023-09-05', 1, 1), -- John -> Beginner Strength
('2023-03-10', 3, 2), -- Jane -> Yoga Flow
('2023-05-15', 5, 8), -- Linda -> CrossFit Intro
('2023-06-20', 6, 9), -- Robert -> Mat Pilates
('2023-10-05', 4, 11),-- Charles -> HIIT Blast
('2023-01-10', 2, 1); -- John -> Advanced Weightlifting

-- Invoice
INSERT INTO Invoice (invoice_date, invoice_amount, invoice_payment_type, enrolment_id) VALUES
('2023-09-05', 200.00, 'Card', 1),
('2023-03-10', 150.00, 'DuitNow', 2),
('2023-05-15', 300.00, 'Cash', 3),
('2023-06-20', 180.00, 'Card', 4),
('2023-10-05', 100.00, 'DuitNow', 5),
('2023-01-10', 350.00, 'Card', 6);

-- Class
INSERT INTO Class (class_datetime, history_id) VALUES
-- John's Beginner Strength (History ID 4)
('2023-09-12 18:00:00', 4),
('2023-09-19 18:00:00', 4),
-- Jane's Yoga (History ID 3)
('2023-03-15 19:30:00', 3),
('2023-03-22 19:30:00', 3),
-- Linda's CrossFit (History ID 5)
('2023-05-20 09:00:00', 5),
('2023-05-27 09:00:00', 5),
-- Robert's Pilates (History ID 6)
('2023-06-25 17:00:00', 6),
('2023-07-02 17:00:00', 6),
-- Charles' HIIT (History ID 7)
('2023-10-10 18:30:00', 7),
('2023-10-17 18:30:00', 7),
-- John's Advanced Weightlifting (History ID 1)
('2023-01-15 20:00:00', 1),
('2023-01-22 20:00:00', 1);

-- Attendance
INSERT INTO Attendance (person_id, class_id, attendance_status) VALUES
-- John's attendance
(1, 1, 'Attended'),
(1, 2, 'Absent'),
(1, 11, 'Attended'),
(1, 12, 'Attended'),
-- Jane's attendance
(2, 3, 'Attended'),
(2, 4, 'Attended'),
-- Linda's attendance
(8, 5, 'Attended'),
(8, 6, 'Attended'),
-- Robert's attendance
(9, 7, 'Absent'),
(9, 8, 'Attended'),
-- Charles's attendance
(11, 9, 'Attended'),
(11, 10, 'Attended');

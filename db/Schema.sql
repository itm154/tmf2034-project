CREATE TABLE Person (
	person_id INTEGER AUTO_INCREMENT,
	person_name VARCHAR(255) NOT NULL,
	person_contact VARCHAR(255) NOT NULL,
	person_dob DATE NOT NULL,
	person_gender VARCHAR(10) NOT NULL,
	PRIMARY KEY(person_id),
	CONSTRAINT chk_person_gender
		CHECK (person_gender IN ('Male', 'Female'))
);

CREATE TABLE Membership_Type (
	membership_type_id INTEGER AUTO_INCREMENT,
	type_name VARCHAR(50) NOT NULL UNIQUE,
	monthly_fee DECIMAL(10,2) NOT NULL,
	PRIMARY KEY(membership_type_id)
);


CREATE TABLE Member (
	person_id INTEGER,
	membership_type_id INTEGER NOT NULL,
	membership_status VARCHAR(20) NOT NULL,
	membership_start_date DATE NOT NULL,
	PRIMARY KEY(person_id),
	CONSTRAINT chk_membership_status
		CHECK (membership_status IN ('Active', 'Inactive', 'Suspended'))
);


CREATE TABLE Trainer (
	person_id INTEGER,
	trainer_specialization VARCHAR(255) NOT NULL,
	trainer_cert_lvl VARCHAR(100) NOT NULL,
	PRIMARY KEY(person_id)
);


CREATE TABLE Program_Category (
	category_id INTEGER AUTO_INCREMENT,
	category_name VARCHAR(255) NOT NULL UNIQUE,
	PRIMARY KEY(category_id)
);


CREATE TABLE Program (
	program_id INTEGER AUTO_INCREMENT,
	program_name VARCHAR(255) NOT NULL,
	program_duration_weeks INTEGER NOT NULL,
	program_fee DECIMAL(10,2) NOT NULL,
	category_id INTEGER NOT NULL,
	PRIMARY KEY(program_id)
);


CREATE TABLE Trainer_Program_History (
	history_id INTEGER AUTO_INCREMENT,
	trainer_person_id INTEGER NOT NULL,
	program_id INTEGER NOT NULL,
	start_date DATE NOT NULL,
	end_date DATE,
	PRIMARY KEY(history_id)
);


CREATE TABLE Enrolment (
	enrolment_id INTEGER AUTO_INCREMENT,
	enrolment_date DATE NOT NULL,
	program_id INTEGER NOT NULL,
	member_person_id INTEGER NOT NULL,
	PRIMARY KEY(enrolment_id)
);


CREATE INDEX idx_enrolment_member
ON Enrolment (member_person_id);
CREATE INDEX idx_enrolment_program
ON Enrolment (program_id);

CREATE TABLE Invoice (
	invoice_id INTEGER AUTO_INCREMENT,
	invoice_date DATE NOT NULL,
	invoice_amount DECIMAL(10,2) NOT NULL,
	invoice_payment_method VARCHAR(20) NOT NULL,
	enrolment_id INTEGER NOT NULL UNIQUE,
	PRIMARY KEY(invoice_id),
	CONSTRAINT chk_payment_type
		CHECK (invoice_payment_type IN ('Cash', 'Card', 'DuitNow'))
);


CREATE TABLE Class (
	class_id INTEGER AUTO_INCREMENT,
	class_datetime DATETIME NOT NULL,
	class_status VARCHAR(20) NOT NULL DEFAULT 'Active',
	history_id INTEGER NOT NULL,
	PRIMARY KEY(class_id)
	CONSTRAINT chk_class_status 
		CHECK (class_status IN ('Active', 'Completed', 'Cancelled'))
);

CREATE TABLE Attendance (
	attendance_id INTEGER AUTO_INCREMENT,
	person_id INTEGER NOT NULL,
	class_id INTEGER NOT NULL,
	attendance_status VARCHAR(10) NOT NULL,
	PRIMARY KEY(attendance_id),
	CONSTRAINT chk_attendance_status
		CHECK (attendance_status IN ('Attended', 'Absent')),
	CONSTRAINT uq_member_class
		UNIQUE (person_id, class_id)
);


CREATE INDEX idx_class_datetime
ON Class (class_datetime);

ALTER TABLE Member
ADD FOREIGN KEY(person_id) REFERENCES Person(person_id)
ON UPDATE NO ACTION ON DELETE CASCADE;

ALTER TABLE Member
ADD FOREIGN KEY(membership_type_id) REFERENCES Membership_Type(membership_type_id)
ON UPDATE NO ACTION ON DELETE NO ACTION;

ALTER TABLE Trainer
ADD FOREIGN KEY(person_id) REFERENCES Person(person_id)
ON UPDATE NO ACTION ON DELETE CASCADE;

ALTER TABLE Program
ADD FOREIGN KEY(category_id) REFERENCES Program_Category(category_id)
ON UPDATE NO ACTION ON DELETE RESTRICT;

ALTER TABLE Trainer_Program_History
ADD FOREIGN KEY(trainer_person_id) REFERENCES Trainer(person_id)
ON UPDATE NO ACTION ON DELETE CASCADE;

ALTER TABLE Trainer_Program_History
ADD FOREIGN KEY(program_id) REFERENCES Program(program_id)
ON UPDATE NO ACTION ON DELETE CASCADE;

ALTER TABLE Enrolment
ADD FOREIGN KEY(program_id) REFERENCES Program(program_id)
ON UPDATE NO ACTION ON DELETE CASCADE;

ALTER TABLE Enrolment
ADD FOREIGN KEY(member_person_id) REFERENCES Member(person_id)
ON UPDATE NO ACTION ON DELETE CASCADE;

ALTER TABLE Invoice
ADD FOREIGN KEY(enrolment_id) REFERENCES Enrolment(enrolment_id)
ON UPDATE NO ACTION ON DELETE CASCADE;

ALTER TABLE Class
ADD FOREIGN KEY(history_id) REFERENCES Trainer_Program_History(history_id)
ON UPDATE NO ACTION ON DELETE CASCADE;

ALTER TABLE Attendance
ADD FOREIGN KEY (person_id) REFERENCES Member(person_id)
ON UPDATE NO ACTION ON  DELETE CASCADE;

ALTER TABLE Attendance
ADD FOREIGN KEY (class_id) REFERENCES Class(class_id)
ON UPDATE NO ACTION ON  DELETE CASCADE;
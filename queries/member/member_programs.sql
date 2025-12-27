SELECT p.program_name, pc.category_name, e.enrolment_date
FROM Enrolment e
JOIN Program p ON e.program_id = p.program_id
JOIN Program_Category pc ON p.category_id = pc.category_id
WHERE e.member_person_id = ?

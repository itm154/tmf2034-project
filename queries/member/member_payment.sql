SELECT p.program_name, i.invoice_date, i.invoice_amount, i.invoice_payment_method
FROM Invoice i
JOIN Enrolment e ON i.enrolment_id = e.enrolment_id
JOIN Program p ON e.program_id = p.program_id
WHERE e.member_person_id = ?

SELECT
    p.program_name,
    pc.catagory_name,
    COUNT(e.enrolment_id) AS total_enrolled,
    per.person_name AS trainer_name
FROM Enrolment e
JOIN Program p
    ON e.program_id = p.program_id
JOIN Program_Category pc
    ON p.category_id = pc.category_id
JOIN Trainer_Program_Histpry tph
    ON tph.program_id = p.program_i
    AND tph.end_date IS NULL
JOIN Trainer t
    ON tph.trainer_person_id = t.person_id
JOIN Person per
    ON t.person_id = per.person_id
GROUP BY
    p.program_id,
    pc.category_name,
    per.person_name
ORDER BY total_enrolled DESC
LIMIT 5;
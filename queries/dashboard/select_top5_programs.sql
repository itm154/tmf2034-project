SELECT
    p.program_name,
    pc.category_name,
    COUNT(e.enrolment_id) AS total_enrolled
FROM Enrolment e
JOIN Program p
    ON e.program_id = p.program_id
JOIN Program_Category pc
    ON p.category_id = pc.category_id
JOIN Trainer_Program_History tph
    ON tph.program_id = p.program_id
    AND tph.end_date IS NULL
GROUP BY
    p.program_id,
    pc.category_name
ORDER BY total_enrolled DESC
LIMIT 5;

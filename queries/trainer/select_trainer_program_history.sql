SELECT
    h.history_id,
    p.program_name,
    pc.category_name,
    h.start_date,
    h.end_date
FROM
    Trainer_Program_History h
JOIN
    Program p ON h.program_id = p.program_id
JOIN
    Program_Category pc ON p.category_id = pc.category_id
WHERE
    h.trainer_person_id = ?
ORDER BY
    h.start_date DESC;

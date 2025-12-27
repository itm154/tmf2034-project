SELECT c.class_id, pr.program_name, p.person_name, c.class_status, c.class_datetime
FROM Class c
JOIN Trainer_Program_History tph
    ON c.history_id = tph.history_id
JOIN Person p
    ON tph.trainer_person_id = p.person_id
JOIN Program pr
		ON tph.program_id = pr.program_id
ORDER BY c.class_datetime DESC;

SELECT c.class_id, c.class_datetime, c.class_status, p.person_name AS trainer_name
FROM Class c
JOIN Trainer_Program_History thp
    ON c.history_id = thp.history_id
JOIN Person p
    ON thp.trainer_person_id = p.person_id
ORDER BY c.class_datetime DESC;

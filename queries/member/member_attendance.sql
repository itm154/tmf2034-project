SELECT p.program_name, c.class_datetime, a.attendance_status
FROM Attendance a
JOIN Class c ON a.class_id = c.class_id
JOIN Trainer_Program_History tph ON c.history_id = tph.history_id
JOIN Program p ON tph.program_id = p.program_id
WHERE a.person_id = ?
ORDER BY c.class_datetime DESC

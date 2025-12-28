SELECT
  p.person_name AS trainer_name,
  COUNT(c.class_id) AS total_classes_taught,
  SUM(
    CASE
      WHEN a.attendance_status = 'Absent' THEN 1
      ELSE 0
    END
  ) AS total_missed_classes
FROM
  Trainer t
  JOIN Person p ON t.person_id = p.person_id
  JOIN Trainer_Program_History tph ON t.person_id = tph.trainer_person_id
  JOIN Class c ON tph.history_id = c.history_id
  LEFT JOIN Attendance a ON c.class_id = a.class_id
GROUP BY
  p.person_id

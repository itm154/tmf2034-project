SELECT
  tph.history_id,
  p.person_name,
  pr.program_name
FROM
  Trainer_Program_History tph
  JOIN Person p ON tph.trainer_person_id = p.person_id
  JOIN Program pr ON tph.program_id = pr.program_id
WHERE
  tph.end_date IS NULL
ORDER BY
  p.person_name,
  pr.program_name

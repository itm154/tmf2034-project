SELECT
  history_id
FROM
  Trainer_Program_History
WHERE
  trainer_person_id = ?
  AND program_id = ?
  AND (
    end_date IS NULL
    OR end_date >= CURDATE()
  )

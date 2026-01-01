UPDATE Program
SET
  program_name = ?,
  category_id = ?,
  program_duration_weeks = ?,
  program_fee = ?
WHERE
  program_id = ?;

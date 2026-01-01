SELECT
  p.*,
  pc.category_name
FROM
  Program p
  JOIN Program_Category pc ON p.category_id = pc.category_id
ORDER BY
  p.program_name ASC;

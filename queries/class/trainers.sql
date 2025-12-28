SELECT
  T.person_id,
  P.person_name
FROM
  Trainer T
  JOIN Person P ON T.person_id = P.person_id
ORDER BY
  P.person_name

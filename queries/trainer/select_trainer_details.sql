SELECT
  p.*,
  t.trainer_specialization,
  t.trainer_cert_lvl
FROM
  Person p
  JOIN Trainer t ON p.person_id = t.person_id
WHERE
  p.person_id = ?

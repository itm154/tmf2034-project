SELECT
  p.person_name,
  p.person_contact,
  p.person_dob,
  p.person_gender,
  m.membership_type_id,
  m.membership_status
FROM
  Person p
  JOIN Member m ON p.person_id = m.person_id
WHERE
  m.person_id = ?

SELECT
    p.person_name,
    m.person_id,
    mt.type_name,
    m.membership_status,
    COUNT(e.program_id) AS program_count
FROM
    Member m
JOIN
    Membership_Type mt ON m.membership_type_id = mt.membership_type_id
JOIN
    Person p ON m.person_id = p.person_id
LEFT JOIN
    Enrolment e ON m.person_id = e.member_person_id
GROUP BY
    m.person_id,
    p.person_name,
    mt.type_name,
    m.membership_status;

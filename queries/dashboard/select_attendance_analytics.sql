SELECT
    p.person_name AS member_name,
    COUNT(a.attendance_id) AS total_classes,
    SUM(CASE
        WHEN a.attendance_status = 'Attended'
        THEN 1 
        ELSE 0
    END) AS attended_classes,
    SUM(CASE
        WHEN a.attendance_status = 'Absent'
        THEN 1 
        ELSE 0
    END) AS absent_classes,
    ROUND(
        SUM(CASE
            WHEN a.attendance_status = 'Attended'
            THEN 1
            ELSE 0
        END) / COUNT(a.attendance_id) * 100,
        2
    ) AS attendance_percentage
FROM Attendance a
JOIN Member m
    ON a.person_id = m.person_id
JOIN Person p
    ON m.person_id = p.person_id
GROUP BY p.person_name
ORDER BY attendance_percentage DESC;
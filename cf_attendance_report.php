<?php
include "db_connect.php";
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Attendance Analytics</title>
</head>
<body>

<h2>Attendance Analytics</h2>

<?php
$q4 = $conn->query("
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
");
?>

<table border="1">
    <tr>
        <th>Member</th>
        <th>Total</th>
        <th>Attended</th>
        <th>Attendance %</th>
</tr>

<?php while ($row = $q4->fetch_assoc()): ?>
<tr>
    <td><?= $row['member_name'] ?></td>
    <td><?= $row['total_classes'] ?></td>
    <td><?= $row['attended_classes'] ?></td>
    <td><?= $row['absent_classes'] ?></td>
    <td><?= $row['attendance_percentage'] ?>%</td>
</tr>
<?php endwhile; ?>
</table>

</body>
</html>
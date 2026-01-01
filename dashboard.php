<?php
include "db_connect.php";
include 'navbar.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Dashboard</title>
</head>
<body>

<h2>--Quarterly Membership Fees--</h2>
<?php
$q1 = $conn->query("
SELECT
    YEAR(m.membership_start_date) AS year,
    QUARTER(m.membership_start_date) AS quarter,
    SUM(mt.monthly_fee * 3) AS total_quarterly_fee
FROM Member m
JOIN Membership_Type mt
    ON m.membership_type_id = mt.membership_type_id
WHERE m.membership_status = 'Active'
GROUP BY Year(m.membership_start_date), QUARTER(m.membership_start_date)
ORDER BY year, quarter;
");
?>

<table border="1">
    <tr>
        <th>Year</th>
        <th>Quarter</th>
        <th>Total Fee (RM)</th>
</tr>
<?php while ($row = $q1->fetch_assoc()): ?>
    <tr>
        <td><?= $row['year'] ?></td>
        <td><?= $row['quarter'] ?></td>
        <td><?= number_format($row ['total_quarterly_fee'], 2) ?></td>
</tr>
<?php endwhile; ?>
</table>

<hr>

<h2>Annual Membership Fees</h2>
<?php
$q2 = $conn->query("
SELECT
    YEAR(m.membership_start_date) AS year,
    SUM(mt.monthly_fee * 12) AS total_annual_fee
FROM Member m
JOIN Membership_Type mt
    ON m.membership_type_id = mt.membership_type_id
WHERE m.membership_status = 'Active'
GROUP BY YEAR(m.membership_start_date)
ORDER BY year;
");
?>

<table  border="1">
    <tr>
        <th>Year</th>
        <th>Total Annual Fee (RM)</th>
</tr>
<?php while ($row = $q2->fetch_assoc()): ?>
    <tr>
        <td><?= $row['year'] ?></td>
        <td><?= number_format($row['total_annual_fee'], 2) ?></td>
</tr>
<?php endwhile; ?>
</table>

<hr>

<h2>Top 5 Most Popular Programs</h2>
<?php
$q3 = $conn->query("
SELECT
    p.program_name,
    pc.category_name,
    COUNT(e.enrolment_id) AS total_enrolled,
    per.person_name AS trainer_name
FROM Enrolment e
JOIN Program p
    ON e.program_id = p.program_id
JOIN Program_Category pc
    ON p.category_id = pc.category_id
JOIN Trainer_Program_History tph
    ON tph.program_id = p.program_id
    AND tph.end_date IS NULL
JOIN Trainer t
    ON tph.trainer_person_id = t.person_id
JOIN Person per
    ON t.person_id = per.person_id
GROUP BY
    p.program_id,
    pc.category_name,
    per.person_name
ORDER BY total_enrolled DESC
LIMIT 5;
");
?>

<table  border="1">
    <tr>
        <th>Program</th>
        <th>Category</th>
        <th>Total Enrolled</th>
        <th>Trainer</th>
</tr>
<?php while ($row = $q3->fetch_assoc()): ?>
    <tr>
        <td><?= $row['program_name'] ?></td>
        <td><?= $row['category_name'] ?></td>
        <td><?= $row['total_enrolled'] ?></td>
        <td><?= $row['trainer_name'] ?></td>
</tr>
<?php endwhile; ?>
</table>

</body>
</html>
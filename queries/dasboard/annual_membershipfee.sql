SELECT
    YEAR(m.membership_start_date) AS year,
    SUM(mt.monthly_fee) * 12 AS total_annual_fee
FROM Member m
JOIN Membership_Type mt
    ON m.membership_type_id = mt.membership_type_id
WHERE m.membership_status = 'Active'
GROUP BY YEAR(m.membership_start_date)
ORDER BY year;
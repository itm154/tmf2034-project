SELECT
    YEAR(m.membership_start_date) AS year,
    QUARTER(m.membership_start_date) AS quarter,
    SUM(mt.monthly_fee) * 3 AS total_quarterly_fee
FROM Member m
JOIN Membership_Type mt
    ON m.membership_type_id = mt.membership_type_id
WHERE m.membership_status = 'Active'
GROUP BY Year(m.membership_start_date), QUARTER(m.membership_start_date)
ORDER BY year, quarter;
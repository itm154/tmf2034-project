<?php
include 'db_connect.php';
include 'navbar.php';

?>

<div class="container mt-4">
	<h1 class="display-4 mb-4">Attendance Analytics</h1>

    <?php
	$attendance_analytics_query = file_get_contents('queries/dashboard/select_attendance_analytics.sql');
	$attendance_analytics = $conn->query($attendance_analytics_query);
	?>

	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered">
			<thead>
				<tr>
					<th>Member</th>
        			<th>Total Classes</th>
        			<th>Attended Classes</th>
					<th>Absent Classes</th>
        			<th>Attendance %</th>
				</tr>
			</thead>
			<tbody>
				<?php while ($row = $attendance_analytics->fetch_assoc()): ?>
					<tr>
    					<td><?= $row['member_name'] ?></td>
    					<td><?= $row['total_classes'] ?></td>
    					<td><?= $row['attended_classes'] ?></td>
    					<td><?= $row['absent_classes'] ?></td>
    					<td><?= $row['attendance_percentage'] ?>%</td>
					</tr>
				<?php endwhile; ?>
			</tbody>
		</table>
	</div>
</div>

<?php $conn->close(); ?>
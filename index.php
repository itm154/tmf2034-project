<?php
include 'navbar.php';
include 'db_connect.php';
?>

<?php
$quarterly_fee_query = file_get_contents('queries/dashboard/select_quarterly_fee.sql');
$quarterly_fees = $conn->query($quarterly_fee_query);
?>

<div class="container mt-4">
	<h1 class="display-4 mb-4">Dashboard</h1>

	<?php
	$attendance_analytics_query = file_get_contents('queries/dashboard/select_attendance_analytics.sql');
	$attendance_analytics = $conn->query($attendance_analytics_query);
	?>

	<h2 class="mb-3">Attendance Analytics</h2>
	<div class="table-responsive">
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th>Member</th>
					<th>Attended Class/Total Class</th>
					<th>Attendance %</th>
				</tr>
			</thead>
			<tbody>
				<?php while ($row = $attendance_analytics->fetch_assoc()): ?>
					<tr>
						<td><?php echo $row['member_name'] ?></td>
						<td><?php echo $row['attended_classes'] . "/" . $row['total_classes'] ?></td>
						<td>
							<div class="progress" role="progressbar">
								<div class="progress-bar progress-bar-striped progress-bar-animated" style="width: <?php echo $row['attendance_percentage'] ?>%"><?php echo $row['attendance_percentage'] ?>%</div>
							</div>
						</td>
					</tr>
				<?php endwhile; ?>
			</tbody>
		</table>
	</div>

	<?php
	$top_programs_query = file_get_contents('queries/dashboard/select_top5_programs.sql');
	$top_programs = $conn->query($top_programs_query);
	?>

	<h2 class="mb-3">Top 5 Most Popular Programs</h2>
	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered">
			<thead>
				<tr>
					<th>Program</th>
					<th>Category</th>
					<th>Total Enrolled</th>
				</tr>
			</thead>
			<?php while ($row = $top_programs->fetch_assoc()): ?>
				<tr>
					<td><?= $row['program_name'] ?></td>
					<td><?= $row['category_name'] ?></td>
					<td><?= $row['total_enrolled'] ?></td>
				</tr>
			<?php endwhile; ?>
		</table>
	</div>

	<h2 class="mb-3">Quarterly Fees</h2>
	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered">
			<thead>
				<tr>
					<th>Year</th>
					<th>Quarter</th>
					<th>Total Fee (RM)</th>
				</tr>
			</thead>
			<?php while ($row = $quarterly_fees->fetch_assoc()): ?>
				<tr>
					<td><?= $row['year'] ?></td>
					<td><?= $row['quarter'] ?></td>
					<td><?= number_format($row['total_quarterly_fee'], 2) ?></td>
				</tr>
			<?php endwhile; ?>
		</table>
	</div>


	<?php
	$annual_fee_query = file_get_contents('queries/dashboard/select_annual_fee.sql');
	$annual_fees = $conn->query($annual_fee_query);
	?>

	<h2 class="mb-3">Annual Fees</h2>
	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered">
			<thead>
				<tr>
					<th>Year</th>
					<th>Total Annual Fee (RM)</th>
				</tr>
			</thead>
			<?php while ($row = $annual_fees->fetch_assoc()): ?>
				<tr>
					<td><?= $row['year'] ?></td>
					<td><?= number_format($row['total_annual_fee'], 2) ?></td>
				</tr>
			<?php endwhile; ?>
		</table>
	</div>


</div>

<?php $conn->close(); ?>

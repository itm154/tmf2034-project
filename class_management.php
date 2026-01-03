<?php
include 'db_connect.php';
include 'navbar.php';
?>

<div class="container mt-4">
	<h1 class="display-4 mb-4">Class management</h1>

	<h2 class="mb-3">Add Class</h2>
	<form action="add_class.php" method="post">
		<div class="mb-3">
			<label for="history_id" class="form-label">Trainer & Program:</label>
			<select id="history_id" name="history_id" class="form-select" required>
				<?php
				$active_assignments_query = file_get_contents('queries/class/select_active_trainer_program_history.sql');
				$active_assignments_result = $conn->query($active_assignments_query);
				while ($assignment = $active_assignments_result->fetch_assoc()):
				?>
					<option value="<?php echo $assignment['history_id']; ?>">
						<?php echo $assignment['person_name'] . ' - ' . $assignment['program_name']; ?>
					</option>
				<?php endwhile; ?>
			</select>
		</div>

		<div class="mb-3">
			<label for="datetime" class="form-label">Date and Time:</label>
			<input type="datetime-local" id="datetime" name="class_datetime" class="form-control" required>
		</div>

		<button type="submit" name="submit" class="btn btn-primary">Add Class</button>
	</form>

	<h2 class="mb-3">Class Schedule</h2>
	<div class="table-responsive">
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th>ID</th>
					<th>Trainer</th>
					<th>Program</th>
					<th>Class Status</th>
					<th>Date and Time</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php

				$query = file_get_contents('queries/class/select_classes.sql');
				$result = $conn->query($query);
				while ($row = mysqli_fetch_assoc($result)):
				?>
					<tr>
						<td>
							<?php echo $row['class_id'] ?>
						</td>
						<td>
							<?php echo $row['person_name'] ?>
						</td>
						<td>
							<?php echo $row['program_name'] ?>
						</td>
						<td>
							<?php echo $row['class_status'] ?>
						</td>
						<td>
							<?php echo $row['class_datetime'] ?>
						</td>
						<td>
							<div class="btn-group" role="group">
								<a href="view_class.php?class_id=<?php echo $row['class_id'] ?>" class="btn btn-info btn-sm">View</a>
								<a href="delete_class.php?class_id=<?php echo $row['class_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this class?');">Delete</a>
							</div>
						</td>
					</tr>
				<?php endwhile; ?>
			</tbody>
		</table>
	</div>
</div>

<?php $conn->close(); ?>

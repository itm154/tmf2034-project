<?php
include 'db_connect.php';
include 'navbar.php';



// Fetch Trainers for the list
$query_trainers = file_get_contents('queries/trainer/select_trainers_list.sql');
$trainers_stmt = $conn->prepare($query_trainers);
$trainers_stmt->execute();
$trainers = $trainers_stmt->get_result();
$trainers_stmt->close();

// Fetch Trainer Performance Report
$query_performance = file_get_contents('queries/trainer/select_trainers_performance.sql');
$performance_stmt = $conn->prepare($query_performance);
$performance_stmt->execute();
$performance = $performance_stmt->get_result();
$performance_stmt->close();
?>

<div class="container mt-4">
	<h1 class="display-4 mb-4">Trainer Directory</h1>

	<h2 class="mb-3">Register New Trainer</h2>
	<form action="add_trainer.php" method="POST">
		<div class="mb-3">
			<label for="name" class="form-label">Name:</label>
			<input type="text" id="name" name="name" class="form-control" required>
		</div>

		<div class="mb-3">
			<label for="contact" class="form-label">Contact:</label>
			<input type="text" id="contact" name="contact" class="form-control" required>
		</div>

		<div class="mb-3">
			<label for="dob" class="form-label">Date of Birth:</label>
			<input type="date" id="dob" name="dob" class="form-control" required>
		</div>

		<div class="mb-3">
			<label for="gender" class="form-label">Gender:</label>
			<select id="gender" name="gender" class="form-select" required>
				<option value="Male">Male</option>
				<option value="Female">Female</option>
			</select>
		</div>

		<div class="mb-3">
			<label for="specialization" class="form-label">Specialization:</label>
			<input type="text" id="specialization" name="specialization" class="form-control" required>
		</div>

		<div class="mb-3">
			<label for="cert" class="form-label">Certification Level:</label>
			<input type="text" id="cert" name="cert" class="form-control" required>
		</div>

		<button type="submit" name="submit" class="btn btn-primary">Add Trainer</button>
	</form>

	<h2 class="mt-5 mb-3">Trainer List</h2>
	<div class="table-responsive">
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Contact</th>
					<th>Specialization</th>
					<th>Cert</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php while ($trainer = $trainers->fetch_assoc()): ?>
					<tr>
						<td><?php echo $trainer['person_id']; ?></td>
						<td><?php echo $trainer['person_name']; ?></td>
						<td><?php echo $trainer['person_contact']; ?></td>
						<td><?php echo $trainer['trainer_specialization']; ?></td>
						<td><?php echo $trainer['trainer_cert_lvl']; ?></td>
						<td>
							<div class="btn-group" role="group">
								<a href="view_trainer.php?trainer_id=<?php echo $trainer['person_id']; ?>" class="btn btn-info btn-sm">View</a>
								<a href="delete_trainer.php?trainer_id=<?php echo $trainer['person_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this trainer?')">Delete</a>
							</div>
						</td>
					</tr>
				<?php endwhile; ?>
			</tbody>
		</table>
	</div>

	<h2 class="mt-5 mb-3">Trainer Performance Report</h2>
	<div class="table-responsive">
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th>Trainer Name</th>
					<th>Total Classes Taught</th>
					<th>Total Missed Classes</th>
				</tr>
			</thead>
			<tbody>
				<?php while ($performance_report = $performance->fetch_assoc()): ?>
					<tr>
						<td><?php echo $performance_report['trainer_name']; ?></td>
						<td><?php echo $performance_report['total_classes_taught']; ?></td>
						<td><?php echo $performance_report['total_missed_classes'] ?? 0; ?></td>
					</tr>
				<?php endwhile; ?>
			</tbody>
		</table>
	</div>
</div>

<?php $conn->close(); ?>

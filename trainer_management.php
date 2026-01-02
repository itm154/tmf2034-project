<?php
include 'db_connect.php';
include 'navbar.php';
?>

<?php
// ================= ADD TRAINER =================
if (isset($_POST['add_trainer'])) {
	$person_name = $_POST['name'];
	$person_contact = $_POST['contact'];
	$person_dob = $_POST['dob'];
	$person_gender = $_POST['gender'];
	$trainer_specialization = $_POST['specialization'];
	$trainer_cert_lvl = $_POST['cert'];

	// Insert into Person
	$person_stmt = $conn->prepare("INSERT INTO Person (person_name, person_contact, person_dob, person_gender) VALUES (?, ?, ?, ?)");
	$person_stmt->bind_param("ssss", $person_name, $person_contact, $person_dob, $person_gender);
	$person_stmt->execute();
	$person_id = $conn->insert_id;
	$person_stmt->close();

	// Insert into Trainer
	$trainer_stmt = $conn->prepare("INSERT INTO Trainer (person_id, trainer_specialization, trainer_cert_lvl) VALUES (?, ?, ?)");
	$trainer_stmt->bind_param("iss", $person_id, $trainer_specialization, $trainer_cert_lvl);
	$trainer_stmt->execute();
	$trainer_stmt->close();
}

// ================= DELETE TRAINER =================
if (isset($_GET['delete'])) {
	$delete_id = $_GET['delete'];
	$delete_stmt = $conn->prepare("DELETE FROM Person WHERE person_id = ?");
	$delete_stmt->bind_param("i", $delete_id);
	$delete_stmt->execute();
	$delete_stmt->close();
}

// ================= EDIT TRAINER =================
$edit_id = "";
$edit_data = null;

if (isset($_GET['edit'])) {
	$edit_id = $_GET['edit'];
	$query = file_get_contents('queries/trainer/select_trainer_details.sql');
	$edit_stmt = $conn->prepare($query);
	$edit_stmt->bind_param("i", $edit_id);
	$edit_stmt->execute();
	$edit_result = $edit_stmt->get_result();
	$edit_data = $edit_result->fetch_assoc();
	$edit_stmt->close();
}

// ================= UPDATE TRAINER =================
if (isset($_POST['update_trainer'])) {
	$update_id = $_POST['id'];

	$query = file_get_contents('queries/person/update_person_details.sql');
	$update_person_stmt = $conn->prepare($query);
	$update_person_stmt->bind_param("ssssi", $_POST['name'], $_POST['contact'], $_POST['dob'], $_POST['gender'], $update_id);
	$update_person_stmt->execute();
	$update_person_stmt->close();

	$update_trainer_stmt = $conn->prepare("UPDATE Trainer SET trainer_specialization=?, trainer_cert_lvl=? WHERE person_id=?");
	$update_trainer_stmt->bind_param("ssi", $_POST['specialization'], $_POST['cert'], $update_id);
	$update_trainer_stmt->execute();
	$update_trainer_stmt->close();
}

// ================= FETCH TRAINERS =================
$query = file_get_contents('queries/trainer/select_trainers_list.sql');
$trainers_stmt = $conn->prepare($query);
$trainers_stmt->execute();
$trainers = $trainers_stmt->get_result();
$trainers_stmt->close();

// ================= TRAINER PERFORMANCE =================
$query = file_get_contents('queries/trainer/select_trainers_performance.sql');
$performance_stmt = $conn->prepare($query);
$performance_stmt->execute();
$performance = $performance_stmt->get_result();
$performance_stmt->close();
?>

<div class="container mt-4">
	<h1 class="display-4 mb-4">Trainer management</h1>

	<!-- ================= ADD / UPDATE FORM ================= -->
	<h2 class="mb-3">Add / Update Trainer</h2>

	<form method="POST">
		<input type="hidden" name="id" value="<?php echo $edit_id; ?>">

		<div class="mb-3">
			<label for="name" class="form-label">Name:</label>
			<input type="text" id="name" name="name" class="form-control" required
				value="<?php echo $edit_data['person_name'] ?? ''; ?>">
		</div>

		<div class="mb-3">
			<label for="contact" class="form-label">Contact:</label>
			<input type="text" id="contact" name="contact" class="form-control" required
				value="<?php echo $edit_data['person_contact'] ?? ''; ?>">
		</div>

		<div class="mb-3">
			<label for="dob" class="form-label">Date of Birth:</label>
			<input type="date" id="dob" name="dob" class="form-control" required
				value="<?php echo $edit_data['person_dob'] ?? ''; ?>">
		</div>

		<div class="mb-3">
			<label for="gender" class="form-label">Gender:</label>
			<select id="gender" name="gender" class="form-select" required>
				<option value="Male" <?php if (($edit_data['person_gender'] ?? '') == 'Male'): echo 'selected';
															endif; ?>>Male</option>
				<option value="Female" <?php if (($edit_data['person_gender'] ?? '') == 'Female'): echo 'selected';
																endif; ?>>Female</option>
			</select>
		</div>

		<div class="mb-3">
			<label for="specialization" class="form-label">Specialization:</label>
			<input type="text" id="specialization" name="specialization" class="form-control" required
				value="<?php echo $edit_data['trainer_specialization'] ?? ''; ?>">
		</div>

		<div class="mb-3">
			<label for="cert" class="form-label">Certification Level:</label>
			<input type="text" id="cert" name="cert" class="form-control" required
				value="<?php echo $edit_data['trainer_cert_lvl'] ?? ''; ?>">
		</div>

		<div class="btn-group" role="group">
			<?php if ($edit_id): ?>
				<button type="submit" name="update_trainer" class="btn btn-primary">Update Trainer</button>
				<a href="trainer_management.php" class="btn btn-secondary">Cancel</a>
			<?php else: ?>
				<button type="submit" name="add_trainer" class="btn btn-primary">Add Trainer</button>
			<?php endif; ?>
		</div>
	</form>

	<!-- ================= TRAINER LIST ================= -->
	<h2 class="mb-3">Trainer List</h2>
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
								<a href="?edit=<?php echo $trainer['person_id']; ?>" class="btn btn-info btn-sm">Edit</a>
								<a href="?delete=<?php echo $trainer['person_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete trainer?')">Delete</a>
							</div>
						</td>
					</tr>
				<?php endwhile; ?>
			</tbody>
		</table>
	</div>

	<!-- ================= PERFORMANCE REPORT ================= -->
	<h2 class="mb-3">Trainer Performance Report</h2>
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

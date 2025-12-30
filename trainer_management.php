<?php
include 'db_connect.php';
include 'navbar.php';
?>

<?php
// ================= ADD TRAINER =================
if (isset($_POST['add_trainer'])) {
	$name = $_POST['name'];
	$contact = $_POST['contact'];
	$dob = $_POST['dob'];
	$gender = $_POST['gender'];
	$specialization = $_POST['specialization'];
	$cert = $_POST['cert'];

	// Insert into Person
	$stmt = $conn->prepare("INSERT INTO Person (person_name, person_contact, person_dob, person_gender) VALUES (?, ?, ?, ?)");
	$stmt->bind_param("ssss", $name, $contact, $dob, $gender);
	$stmt->execute();
	$person_id = $conn->insert_id;
	$stmt->close();

	// Insert into Trainer
	$stmt = $conn->prepare("INSERT INTO Trainer (person_id, trainer_specialization, trainer_cert_lvl) VALUES (?, ?, ?)");
	$stmt->bind_param("iss", $person_id, $specialization, $cert);
	$stmt->execute();
	$stmt->close();
}

// ================= DELETE TRAINER =================
if (isset($_GET['delete'])) {
	$id = $_GET['delete'];
	$stmt = $conn->prepare("DELETE FROM Person WHERE person_id = ?");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$stmt->close();
}

// ================= EDIT TRAINER =================
$edit_id = "";
$edit = [];

if (isset($_GET['edit'])) {
	$edit_id = $_GET['edit'];
	$query = file_get_contents('queries/trainer/edit_trainer_info.sql');
	$stmt = $conn->prepare($query);
	$stmt->bind_param("i", $edit_id);
	$stmt->execute();
	$res = $stmt->get_result();
	$edit = $res->fetch_assoc();
	$stmt->close();
}

// ================= UPDATE TRAINER =================
if (isset($_POST['update_trainer'])) {
	$id = $_POST['id'];

	$query = file_get_contents('queries/person/update_person.sql');
	$stmt = $conn->prepare($query);
	$stmt->bind_param("ssssi", $_POST['name'], $_POST['contact'], $_POST['dob'], $_POST['gender'], $id);
	$stmt->execute();
	$stmt->close();

	$stmt = $conn->prepare("UPDATE Trainer SET trainer_specialization=?, trainer_cert_lvl=? WHERE person_id=?");
	$stmt->bind_param("ssi", $_POST['specialization'], $_POST['cert'], $id);
	$stmt->execute();
	$stmt->close();
}

// ================= FETCH TRAINERS =================
$query = file_get_contents('queries/trainer/trainers_list.sql');
$stmt = $conn->prepare($query);
$stmt->execute();
$trainers = $stmt->get_result();
$stmt->close();

// ================= TRAINER PERFORMANCE =================
$query = file_get_contents('queries/trainer/trainer_performance.sql');
$stmt = $conn->prepare($query);
$stmt->execute();
$performance = $stmt->get_result();
$stmt->close();
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
				value="<?php echo $edit['person_name'] ?? ''; ?>">
		</div>

		<div class="mb-3">
			<label for="contact" class="form-label">Contact:</label>
			<input type="text" id="contact" name="contact" class="form-control" required
				value="<?php echo $edit['person_contact'] ?? ''; ?>">
		</div>

		<div class="mb-3">
			<label for="dob" class="form-label">Date of Birth:</label>
			<input type="date" id="dob" name="dob" class="form-control" required
				value="<?php echo $edit['person_dob'] ?? ''; ?>">
		</div>

		<div class="mb-3">
			<label for="gender" class="form-label">Gender:</label>
			<select id="gender" name="gender" class="form-select" required>
				<option value="Male" <?php if (($edit['person_gender'] ?? '') == 'Male'): echo 'selected';
															endif; ?>>Male</option>
				<option value="Female" <?php if (($edit['person_gender'] ?? '') == 'Female'): echo 'selected';
																endif; ?>>Female</option>
			</select>
		</div>

		<div class="mb-3">
			<label for="specialization" class="form-label">Specialization:</label>
			<input type="text" id="specialization" name="specialization" class="form-control" required
				value="<?php echo $edit['trainer_specialization'] ?? ''; ?>">
		</div>

		<div class="mb-3">
			<label for="cert" class="form-label">Certification Level:</label>
			<input type="text" id="cert" name="cert" class="form-control" required
				value="<?php echo $edit['trainer_cert_lvl'] ?? ''; ?>">
		</div>

		<div class="d-flex gap-2">
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
				<?php while ($t = $trainers->fetch_assoc()): ?>
					<tr>
						<td><?php echo $t['person_id']; ?></td>
						<td><?php echo $t['person_name']; ?></td>
						<td><?php echo $t['person_contact']; ?></td>
						<td><?php echo $t['trainer_specialization']; ?></td>
						<td><?php echo $t['trainer_cert_lvl']; ?></td>
						<td>
							<div class="d-flex gap-2">
								<a href="?edit=<?php echo $t['person_id']; ?>" class="btn btn-info btn-sm">Edit</a>
								<a href="?delete=<?php echo $t['person_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete trainer?')">Delete</a>
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
				<?php while ($p = $performance->fetch_assoc()): ?>
					<tr>
						<td><?php echo $p['trainer_name']; ?></td>
						<td><?php echo $p['total_classes_taught']; ?></td>
						<td><?php echo $p['total_missed_classes'] ?? 0; ?></td>
					</tr>
				<?php endwhile; ?>
			</tbody>
		</table>
	</div>
</div>

<?php $conn->close(); ?>

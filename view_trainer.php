<?php
include 'db_connect.php';

$trainer_id = null;
if (isset($_REQUEST['trainer_id'])) {
	$trainer_id = $_REQUEST['trainer_id'];
}

// Handle End Program History
if (isset($_GET['history_id'])) {
	$history_id = $_GET['history_id'];
	$end_date = date('Y-m-d');

	$end_stmt = $conn->prepare("UPDATE Trainer_Program_History SET end_date = ? WHERE history_id = ?");
	$end_stmt->bind_param("si", $end_date, $history_id);
	if ($end_stmt->execute()) {
		header("Location: view_trainer.php?trainer_id=" . $trainer_id);
		exit();
	} else {
		$error_message = "Error ending program assignment: " . $conn->error;
	}
	$end_stmt->close();
}

// Handle Add Program History
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_program_history'])) {
	if (isset($_POST['program_id'])) {
		$program_id = $_POST['program_id'];
		$start_date = date('Y-m-d');

		// Check if the trainer is already assigned to the program and is still active
		$check_stmt = $conn->prepare("SELECT * FROM Trainer_Program_History WHERE trainer_person_id = ? AND program_id = ? AND end_date IS NULL");
		$check_stmt->bind_param("ii", $trainer_id, $program_id);
		$check_stmt->execute();
		$check_result = $check_stmt->get_result();

		if ($check_result->num_rows > 0) {
			$error_message = "This trainer is already actively assigned to this program.";
		} else {
			$add_stmt = $conn->prepare("INSERT INTO Trainer_Program_History (trainer_person_id, program_id, start_date) VALUES (?, ?, ?)");
			$add_stmt->bind_param("iis", $trainer_id, $program_id, $start_date);
			if ($add_stmt->execute()) {
				header("Location: view_trainer.php?trainer_id=" . $trainer_id);
				exit();
			} else {
				$error_message = "Error assigning program: " . $conn->error;
			}
			$add_stmt->close();
		}
		$check_stmt->close();
	}
}

// Handle self form submission for updates
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

	if (isset($_POST['trainer_id'], $_POST['name'], $_POST['contact'], $_POST['dob'], $_POST['gender'], $_POST['specialization'], $_POST['cert'])) {
		$trainer_id = $_POST['trainer_id'];
		$person_name = $_POST['name'];
		$person_contact = $_POST['contact'];
		$person_dob = $_POST['dob'];
		$person_gender = $_POST['gender'];
		$trainer_specialization = $_POST['specialization'];
		$trainer_cert_lvl = $_POST['cert'];

		$conn->begin_transaction();

		$query_person = file_get_contents('queries/person/update_person_details.sql');
		$stmt_person = $conn->prepare($query_person);
		$stmt_person->bind_param("ssssi", $person_name, $person_contact, $person_dob, $person_gender, $trainer_id);

		$query_trainer = "UPDATE Trainer SET trainer_specialization = ?, trainer_cert_lvl = ? WHERE person_id = ?";
		$stmt_trainer = $conn->prepare($query_trainer);
		$stmt_trainer->bind_param("ssi", $trainer_specialization, $trainer_cert_lvl, $trainer_id);

		if ($stmt_person->execute() && $stmt_trainer->execute()) {
			$conn->commit();
			// Redirect back to the trainer's detail page
			header("Location: view_trainer.php?trainer_id=" . $trainer_id);
			exit();
		} else {
			$conn->rollback();
			$error_message = "Error updating trainer: " . $conn->error;
		}

		$stmt_person->close();
		$stmt_trainer->close();
	} else {
		$error_message = "Please fill in all fields!";
	}
}

// Fetch initial trainer data
$trainer_info = null;
if ($trainer_id) {
	// Fetch trainer's info
	$trainer_query = file_get_contents('queries/trainer/select_trainer_details.sql');
	$trainer_stmt = $conn->prepare($trainer_query);
	$trainer_stmt->bind_param("i", $trainer_id);
	$trainer_stmt->execute();
	$trainer_result = $trainer_stmt->get_result();
	$trainer_info = $trainer_result->fetch_assoc();
	$trainer_stmt->close();
}

include 'navbar.php';
?>

<div class="container mt-4">

	<h1 class="display-4 mb-4">Trainer Information</h1>

	<?php if (isset($error_message)) : ?>
		<div class="alert alert-danger" role="alert">
			<?php echo $error_message; ?>
		</div>
	<?php endif; ?>


	<?php if ($trainer_info): ?>
		<form action="<?php $_SERVER["PHP_SELF"] ?>?trainer_id=<?php echo $trainer_id; ?>" method="post">
			<input type="hidden" name="trainer_id" value="<?php echo $trainer_id; ?>">

			<div class="mb-3">
				<label for="name" class="form-label">Name:</label>
				<input type="text" id="name" name="name" class="form-control" value="<?php echo $trainer_info['person_name']; ?>" required>
			</div>

			<div class="mb-3">
				<label for="contact" class="form-label">Contact:</label>
				<input type="text" id="contact" name="contact" class="form-control" value="<?php echo $trainer_info['person_contact']; ?>" required>
			</div>

			<div class="mb-3">
				<label for="dob" class="form-label">Date of Birth:</label>
				<input type="date" id="dob" name="dob" class="form-control" value="<?php echo $trainer_info['person_dob']; ?>" required>
			</div>

			<div class="mb-3">
				<label for="gender" class="form-label">Gender:</label>
				<select id="gender" name="gender" class="form-select" required>
					<option value="Male" <?php if ($trainer_info['person_gender'] == 'Male') echo 'selected="selected"'; ?>>Male</option>
					<option value="Female" <?php if ($trainer_info['person_gender'] == 'Female') echo 'selected="selected"'; ?>>Female</option>
				</select>
			</div>

			<div class="mb-3">
				<label for="specialization" class="form-label">Specialization:</label>
				<input type="text" id="specialization" name="specialization" class="form-control" value="<?php echo $trainer_info['trainer_specialization']; ?>" required>
			</div>

			<div class="mb-3">
				<label for="cert" class="form-label">Certification Level:</label>
				<input type="text" id="cert" name="cert" class="form-control" value="<?php echo $trainer_info['trainer_cert_lvl']; ?>" required>
			</div>

			<div class="mb-3">
				<button type="submit" name="submit" class="btn btn-primary">Save Changes</button>
			</div>
		</form>

		<?php
		// Fetch all programs for the dropdown
		$all_programs_query = "SELECT program_id, program_name FROM Program ORDER BY program_name";
		$all_programs_result = $conn->query($all_programs_query);

		// Fetch trainer's program history
		$history_query = file_get_contents('queries/trainer/select_trainer_program_history.sql');
		$history_stmt = $conn->prepare($history_query);
		$history_stmt->bind_param("i", $trainer_id);
		$history_stmt->execute();
		$history_result = $history_stmt->get_result();
		?>

		<h2 class="mt-5 mb-3">Program History</h2>

		<form action="<?php $_SERVER["PHP_SELF"]; ?>?trainer_id=<?php echo $trainer_id; ?>" method="post" class="mb-4">
			<div class="row g-3 align-items-end">
				<div class="col-md-6">
					<label for="program_id" class="form-label">Assign New Program:</label>
					<select id="program_id" name="program_id" class="form-select" required>
						<?php while ($program = $all_programs_result->fetch_assoc()): ?>
							<option value="<?php echo $program['program_id']; ?>"><?php echo $program['program_name']; ?></option>
						<?php endwhile; ?>
					</select>
				</div>
				<div class="col-md-6">
					<button type="submit" name="add_program_history" class="btn btn-primary">Assign Program</button>
				</div>
			</div>
		</form>

		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered">
				<thead>
					<tr>
						<th>Program Name</th>
						<th>Category</th>
						<th>Start Date</th>
						<th>End Date</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					while ($history_row = $history_result->fetch_assoc()):
					?>
						<tr>
							<td><?php echo $history_row['program_name']; ?></td>
							<td><?php echo $history_row['category_name']; ?></td>
							<td><?php echo $history_row['start_date']; ?></td>
							<td><?php echo $history_row['end_date'] ?? '-'; ?></td>
							<td>
								<?php if (is_null($history_row['end_date'])): ?>
									<a href="view_trainer.php?trainer_id=<?php echo $trainer_id; ?>&history_id=<?php echo $history_row['history_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to end this program assignment for the trainer?')">End Assignment</a>
								<?php endif; ?>
							</td>
						</tr>
					<?php
					endwhile;
					$history_stmt->close();
					?>
				</tbody>
			</table>
		</div>


		<?php else:
		if (isset($_REQUEST['trainer_id'])):  ?>
			<div class="alert alert-danger">
				Trainer not found
			</div>
		<?php else:  ?>
			<div class="alert alert-danger">
				Trainer ID not specified
			</div>
	<?php endif;
	endif; ?>

</div>

<?php $conn->close(); ?>

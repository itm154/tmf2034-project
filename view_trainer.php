<?php
include 'db_connect.php';

$trainer_id = null;
if (isset($_REQUEST['trainer_id'])) {
	$trainer_id = $_REQUEST['trainer_id'];
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
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?trainer_id=<?php echo $trainer_id; ?>" method="post">
			<input type="hidden" name="trainer_id" value="<?php echo $trainer_id; ?>">

			<div class="mb-3">
				<label for="name" class="form-label">Name:</label>
				<input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($trainer_info['person_name']); ?>" required>
			</div>

			<div class="mb-3">
				<label for="contact" class="form-label">Contact:</label>
				<input type="text" id="contact" name="contact" class="form-control" value="<?php echo htmlspecialchars($trainer_info['person_contact']); ?>" required>
			</div>

			<div class="mb-3">
				<label for="dob" class="form-label">Date of Birth:</label>
				<input type="date" id="dob" name="dob" class="form-control" value="<?php echo htmlspecialchars($trainer_info['person_dob']); ?>" required>
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
				<input type="text" id="specialization" name="specialization" class="form-control" value="<?php echo htmlspecialchars($trainer_info['trainer_specialization']); ?>" required>
			</div>

			<div class="mb-3">
				<label for="cert" class="form-label">Certification Level:</label>
				<input type="text" id="cert" name="cert" class="form-control" value="<?php echo htmlspecialchars($trainer_info['trainer_cert_lvl']); ?>" required>
			</div>

			<div class="mb-3">
				<button type="submit" name="submit" class="btn btn-primary">Save Changes</button>
			</div>
		</form>

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

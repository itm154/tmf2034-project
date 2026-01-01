<?php
include 'db_connect.php';

$program_id = $_REQUEST['id'];

// Handle self form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

	$program_name = $_POST['program_name'];
	$program_category = $_POST['program_category'];
	$program_duration = $_POST['program_duration'];
	$program_fee = $_POST['program_fee'];

	$stmt = $conn->prepare(file_get_contents('queries/program/update_program.sql'));
	$stmt->bind_param("siidi", $program_name, $program_category, $program_duration, $program_fee, $program_id);

	if ($stmt->execute()) {
		header("Location: programs_management.php");
		exit();
	} else {
		$error_message = "Error updating program: " . $conn->error;
	}

	$stmt->close();
}

// Fetch initial program data
$program = null;
if ($program_id) {
	$stmt = $conn->prepare("SELECT * FROM Program WHERE program_id = ?");
	$stmt->bind_param("i", $program_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$program = $result->fetch_assoc();
	$stmt->close();
}

include 'navbar.php';
?>

<div class="container mt-4">
	<h1 class="display-4 mb-4">Edit Program</h1>

	<?php if (isset($error_message)) : ?>
		<div class="alert alert-danger" role="alert">
			<?php echo $error_message; ?>
		</div>
	<?php endif; ?>

	<?php if ($program) : ?>
		<form action="<?php echo $_SERVER["PHP_SELF"] . "?id=" . $program_id; ?>" method="post">
			<div class="mb-3">
				<label for="program_name" class="form-label">Program Name:</label>
				<input type="text" id="program_name" name="program_name" class="form-control" value="<?php echo $program['program_name']; ?>" required>
			</div>

			<div class="mb-3">
				<label for="program_category" class="form-label">Program Category:</label>
				<select id="program_category" name="program_category" class="form-select" required>
					<?php
					$cat_query = "SELECT * from Program_Category ORDER BY category_name ASC";
					$cat_result = $conn->query($cat_query);
					while ($row = mysqli_fetch_assoc($cat_result)):
						$selected = ($program['category_id'] == $row['category_id']) ? "selected" : "";
					?>
						<option value="<?php echo $row['category_id'] ?>" <?php echo $selected ?>><?php echo $row['category_name'] ?></option>
					<?php endwhile; ?>
				</select>
			</div>

			<div class="mb-3">
				<label for="program_duration" class="form-label">Program Duration (Weeks):</label>
				<input type="number" id="program_duration" name="program_duration" class="form-control" value="<?php echo $program['program_duration_weeks']; ?>" required>
			</div>

			<div class="mb-3">
				<label for="program_fee" class="form-label">Program Fee (RM):</label>
				<input type="number" id="program_fee" name="program_fee" step=".01" class="form-control" value="<?php echo $program['program_fee']; ?>" required>
			</div>

			<button type="submit" name="submit" class="btn btn-primary">Update Program</button>
		</form>
	<?php else: ?>
		<div class="alert alert-warning" role="alert">
			Could not retrieve program details.
		</div>
	<?php endif; ?>
</div>

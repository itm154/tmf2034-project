<?php
include 'db_connect.php';

$class_id = null;
if (isset($_REQUEST['class_id'])) {
	$class_id = $_REQUEST['class_id'];
} else {
	header("Location: class_management.php");
	exit();
}

// Handle self form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

	if (isset($_POST['class_id'], $_POST['history_id'], $_POST['class_datetime'], $_POST['class_status'])) {
		$class_id = $_POST['class_id'];
		$history_id = $_POST['history_id'];
		$class_datetime = $_POST['class_datetime'];
		$class_status = $_POST['class_status'];

		$conn->begin_transaction();

		$query_class  = "UPDATE Class SET history_id = ?, class_datetime = ?, class_status = ? WHERE class_id = ?";
		$stmt_class = $conn->prepare($query_class);
		$stmt_class->bind_param("issi", $history_id, $class_datetime, $class_status, $class_id);

		if ($stmt_class->execute()) {
			$conn->commit();
			header("Location: class_management.php");
			exit();
		} else {
			$conn->rollback();
			$error_message = "Error updating class: " . $stmt_class->error;
		}

		$stmt_class->close();
	} else {
		$error_message = "Please fill in all fields!";
	}
}

// Fetch initial class information
$class_info = null;
$query = "SELECT C.class_id, C.history_id, C.class_datetime, C.class_status FROM Class C WHERE C.class_id = ?";
$stmt = $conn->prepare($query);
if ($stmt) {
	$stmt->bind_param("i", $class_id);
	$stmt->execute();
	$result = $stmt->get_result();

	if ($result->num_rows > 0) {
		$class_info = $result->fetch_assoc();
	} else {
		$error_message = "Class not found.";
	}

	$stmt->close();
} else {
	$error_message = "Error preparing statement: " . $conn->error;
}

include 'navbar.php';
?>

<div class="container mt-4">
	<h1 class="display-4 mb-4">Edit Class</h1>

	<?php if (isset($error_message)) : ?>
		<div class="alert alert-danger" role="alert">
			<?php echo $error_message; ?>
		</div>
	<?php endif; ?>

	<?php if ($class_info) : ?>
		<form action="<?php echo $_SERVER["PHP_SELF"] . "?class_id=" . $class_id; ?>" method="post">
			<input type="hidden" name="class_id" value="<?php echo $class_info['class_id']; ?>">

			<div class="mb-3">
				<label for="history" class="form-label">Trainer & Program:</label>
				<select id="history" name="history_id" class="form-select" required>
					<?php
					$combo_query_sql = file_get_contents('queries/class/trainer_program_history.sql');
					$combo_result = $conn->query($combo_query_sql);
					if ($combo_result->num_rows > 0) {
						while ($combo = $combo_result->fetch_assoc()) {
							$selected = ($combo['history_id'] == $class_info['history_id']) ? 'selected' : '';
							echo '<option value="' . $combo['history_id'] . '" ' . $selected . '>' . $combo['person_name'] . ' - ' . $combo['program_name'] . '</option>';
						}
					} else {
						echo '<option value="">No history available</option>';
					}
					?>
				</select>
			</div>

			<div class="mb-3">
				<label for="datetime" class="form-label">Date and Time:</label>
				<input type="datetime-local" id="datetime" name="class_datetime" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($class_info['class_datetime'])); ?>" required>
			</div>

			<div class="mb-3">
				<label for="status" class="form-label">Class Status:</label>
				<select id="status" name="class_status" class="form-select" required>
					<option value="Active" <?php if ($class_info['class_status'] == 'Active') echo 'selected'; ?>>Scheduled</option>
					<option value="Completed" <?php if ($class_info['class_status'] == 'Completed') echo 'selected'; ?>>Completed</option>
					<option value="Cancelled" <?php if ($class_info['class_status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
				</select>
			</div>

			<button type="submit" name="submit" class="btn btn-primary">Update Class</button>
		</form>
	<?php else: ?>
		<div class="alert alert-warning" role="alert">
			Could not retrieve class details.
		</div>
	<?php endif; ?>
</div>

<?php $conn->close(); ?>

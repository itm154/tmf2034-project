<?php
include 'db_connect.php';

$class_id = null;
$class_info = null;
$error_message = '';

if (isset($_GET['class_id'])) {
	$class_id = $_GET['class_id'];
} else {
	header("Location: class_management.php");
	exit();
}

// Fetch initial class information
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

include 'navbar.php'; ?>

<?php if (isset($error_message)) : ?>
	<p style="color: red;"><?php echo $error_message; ?></p>
<?php endif; ?>

<h1>Edit Class</h1>

<form action="update_class.php" method="post">
	<input type="hidden" name="class_id" value="<?php echo $class_info['class_id']; ?>">

	<p>
		<label for="history">Trainer & Program:</label>
		<select id="history" name="history_id" required>
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
	</p>

	<p>
		<label for="datetime">Date and Time:</label>
		<input type="datetime-local" id="datetime" name="class_datetime" value="<?php echo date('Y-m-d\TH:i', strtotime($class_info['class_datetime'])); ?>" required>
	</p>

	<p>
		<label for="status">Class Status:</label>
		<select id="status" name="class_status" required>
			<option value="Active" <?php if ($class_info['class_status'] == 'Active') echo 'selected'; ?>>Active</option>
			<option value="Completed" <?php if ($class_info['class_status'] == 'Completed') echo 'selected'; ?>>Completed</option>
			<option value="Cancelled" <?php if ($class_info['class_status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
		</select>
	</p>

	<p>
		<input type="submit" name="submit" value="Update Class">
	</p>
</form>

<?php $conn->close(); ?>

<?php
include 'db_connect.php';

$program_id = $_REQUEST['id'];

// Handle self form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

	$program_name = $_POST['program_name'];
	$program_category = $_POST['program_category'];
	$program_duration = $_POST['program_duration'];
	$program_fee = $_POST['program_fee'];

	$stmt = $conn->prepare("UPDATE Program SET program_name = ?, category_id = ?, program_duration_weeks = ?, program_fee = ? WHERE program_id = ?");
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

<h1>Edit Program</h1>

<form action="<?php echo $_SERVER["PHP_SELF"] . "?id=" . $program_id; ?>" method="post">
	<p>
		<label for="program_name">Program Name:</label>
		<input type="text" id="program_name" name="program_name" value="<?php echo $program['program_name']; ?>" required>
	</p>

	<p>
		<label for="program_category">Program Category:</label>
		<select id="program_category" name="program_category" required>
			<?php
			$cat_query = "SELECT * from Program_Category ORDER BY category_name ASC";
			$cat_result = $conn->query($cat_query);
			while ($row = mysqli_fetch_assoc($cat_result)) {
				$selected = ($program['category_id'] == $row['category_id']) ? "selected" : "";
			?>
				<option value="<?php echo $row['category_id'] ?>" <?php echo $selected ?>><?php echo $row['category_name'] ?></option>
			<?php } ?>
		</select>
	</p>

	<p>
		<label for="program_duration">Program Duration (Weeks):</label>
		<input type="number" id="program_duration" name="program_duration" value="<?php echo $program['program_duration_weeks']; ?>" required>
	</p>

	<p>
		<label for="program_fee">Program Fee (RM):</label>
		<input type="number" id="program_fee" name="program_fee" step=".01" value="<?php echo $program['program_fee']; ?>" required>
	</p>

	<p>
		<input type="submit" name="submit" value="Update Program">
	</p>
</form>

<?php
include("db_connect.php");

if (isset($_POST['submit'])) {
	$trainer_id = $_POST['trainer_id'];
	$program_id = $_POST['program_id'];
	$class_datetime = $_POST['class_datetime'];

	// Find or create a new trainer-program history entry
	$history_query = $conn->prepare("SELECT history_id FROM Trainer_Program_History WHERE trainer_person_id = ? AND program_id = ? AND (end_date IS NULL OR end_date >= CURDATE())");
	$history_query->bind_param("ii", $trainer_id, $program_id);
	$history_query->execute();
	$result = $history_query->get_result();

	$history_id = null;
	if ($result->num_rows > 0) {
		$history_id = $result->fetch_assoc()['history_id'];
	} else {
		$start_date = date('Y-m-d');
		$history_insert_query = $conn->prepare("INSERT INTO Trainer_Program_History (trainer_person_id, program_id, start_date) VALUES (?, ?, ?)");
		$history_insert_query->bind_param("iis", $trainer_id, $program_id, $start_date);

		if ($history_insert_query->execute()) {
			$history_id = $conn->insert_id;
		} else {
			// Using JS alert and history back to show error and not lose form data
			echo "<script>alert('Error creating trainer-program history: " . $conn->error . "');</script>";
			exit();
		}
	}

	// After trainer_program_history is set/found then add the class
	if ($history_id) {
		$class_insert_query = $conn->prepare("INSERT INTO Class (class_datetime, history_id) VALUES (?, ?)");
		$class_insert_query->bind_param("si", $class_datetime, $history_id);

		if ($class_insert_query->execute()) {
			header("Location: class_management.php");
			exit();
		} else {
			echo "<script>alert('Error adding class: " . $conn->error . "');</script>";
		}
	} else {
		echo "<script>alert('Could not find or create a trainer program history link.');</script>";
	}
}

$conn->close();

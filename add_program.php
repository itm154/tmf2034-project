<?php
include("db_connect.php");

if (isset($_POST['submit'])) {
	$program_name = $_POST['program_name'];
	$program_category = $_POST['program_category'];
	$program_duration = $_POST['program_duration'];
	$program_fee = $_POST['program_fee'];

	$program_insert_query = $conn->prepare("INSERT INTO Program (program_name, program_duration_weeks, program_fee, category_id) VALUES (?, ?, ?, ?)");
	$program_insert_query->bind_param("sidi", $program_name, $program_duration, $program_fee, $program_category);

	if ($program_insert_query->execute()) {
		header("Location: programs_management.php");
		exit;
	} else {
		echo "<script>alert('Error adding program: " . $conn->error . "'); window.history.back();</script>";
	}
}

$conn->close();

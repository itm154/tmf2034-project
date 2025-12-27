<?php
include 'db_connect.php';


if (isset($_GET['class_id'])) {
	$class_id = $_GET['class_id'];

	$sql = "DELETE FROM Class WHERE class_id = ?";

	if ($stmt = $conn->prepare($sql)) {
		$stmt->bind_param("i", $param_id);
		$param_id = $class_id;

		if ($stmt->execute()) {
			// Return to class_management page
			header("location: class_management.php");
			exit();
		} else {
			echo "<script>alert('Error deleting class: " . $conn->error . "');</script>";
		}
	}

	$stmt->close();
}

$conn->close();

<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
	$program_id = $_GET['id'];

	$stmt = $conn->prepare("DELETE FROM Program WHERE program_id = ?");
	$stmt->bind_param("i", $program_id);

	if ($stmt->execute()) {
		header("location: programs_management.php");
	} else {
		echo "<script> alert('Error deleting program'); window.location.href = 'programs_management.php'; </script>";
	}
	$stmt->close();
}

$conn->close();

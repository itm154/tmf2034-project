<?php
include 'db_connect.php';

if (isset($_GET['trainer_id'])) {
	$trainer_id = $_GET['trainer_id'];

	$stmt = $conn->prepare("DELETE FROM Person WHERE person_id = ?");
	$stmt->bind_param("i", $trainer_id);

	if ($stmt->execute()) {
		$stmt->close();
		header("Location: trainer_management.php");
		exit();
	} else {
		echo "<script> alert('Error deleting trainer'); window.location.href = 'trainer_management.php'; </script>";
	}
}
$conn->close();

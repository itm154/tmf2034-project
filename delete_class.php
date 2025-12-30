<?php
include 'db_connect.php';

if (isset($_GET['class_id'])) {
	$class_id = $_GET['class_id'];

	$stmt = $conn->prepare("DELETE FROM Class WHERE class_id = ?");
	$stmt->bind_param("i", $class_id);

	if ($stmt->execute()) {
		header("location: class_management.php");
	} else {
		echo "<script> alert('Error deleting class'); window.location.href = 'class_management.php'; </script>";
	}
	$stmt->close();
}
$conn->close();

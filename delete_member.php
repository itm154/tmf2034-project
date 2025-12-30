<?php
include 'db_connect.php';

if (isset($_GET['member_id'])) {
	$member_id = $_GET['member_id'];

	$stmt = $conn->prepare("DELETE FROM Person WHERE person_id = ?");
	$stmt->bind_param("i", $member_id);

	if ($stmt->execute()) {
		header("location: member_directory.php");
	} else {
		echo "<script> alert('Error deleting member'); window.location.href = 'member_directory.php'; </script>";
	}
	$stmt->close();
}
$conn->close();

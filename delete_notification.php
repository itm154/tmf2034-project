<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
	$notification_id = $_GET['id'];

	$stmt = $conn->prepare("DELETE FROM Notification WHERE notification_id = ?");
	$stmt->bind_param("i", $notification_id);

	if ($stmt->execute()) {
		header("location: notification_management.php");
	} else {
		echo "<script> alert('Error deleting notification'); window.location.href = 'notification_management.php'; </script>";
	}
	$stmt->close();
}

$conn->close();

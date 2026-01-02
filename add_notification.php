<?php
include("db_connect.php");

if (isset($_POST['submit'])) {
	$notification_content = $_POST['notification_content'];
	$stmt = $conn->prepare("INSERT INTO Notification (notification_content, notification_datetime) VALUES (?, NOW())");
	$stmt->bind_param("s", $notification_content);

	if ($stmt->execute()) {
		header("Location: notification_management.php");
		exit;
	} else {
		echo "<script>alert('Error adding notification: " . $conn->error . "'); window.history.back();</script>";
	}
	$stmt->close();
}

$conn->close();

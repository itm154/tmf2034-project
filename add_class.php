<?php
// this is hella simplified from the previous one lmao

include("db_connect.php");

if (isset($_POST['submit'])) {
	$history_id = $_POST['history_id'];
	$class_datetime = $_POST['class_datetime'];

	$class_insert_query = $conn->prepare("INSERT INTO Class (class_datetime, history_id) VALUES (?, ?)");
	$class_insert_query->bind_param("si", $class_datetime, $history_id);

	if ($class_insert_query->execute()) {
		header("Location: class_management.php");
		exit();
	} else {
		echo "<script>alert('Error adding class: " . $conn->error . "'); window.history.back();</script>";
	}
	$class_insert_query->close();
}

$conn->close();

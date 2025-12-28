<?php
include("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST['class_id'], $_POST['history_id'], $_POST['class_datetime'], $_POST['class_status'])) {
		$class_id = $_POST['class_id'];
		$history_id = $_POST['history_id'];
		$class_datetime = $_POST['class_datetime'];
		$class_status = $_POST['class_status'];

		$conn->begin_transaction();

		$query_class  = "UPDATE Class SET history_id = ?, class_datetime = ?, class_status = ? WHERE class_id = ?";
		$stmt_class = $conn->prepare($query_class);
		$stmt_class->bind_param("issi", $history_id, $class_datetime, $class_status, $class_id);

		if ($stmt_class->execute()) {
			$conn->commit();
			header("Location: class_management.php");
			exit();
		} else {
			$conn->rollback();
			echo "<script>alert('Error updating class: " . $stmt_class->error . "'); window.history.back();</script>";
		}

		$stmt_class->close();
	} else {
		echo "<script>alert('Please fill in all fields!'); window.history.back();</script>";
	}
} else {
	header("Location: class_management.php");
	exit();
}

$conn->close();

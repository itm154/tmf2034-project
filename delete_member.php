<?php
include 'db_connect.php';

/* Reference: https://www.w3schools.com/php/php_mysql_prepared_statements.asp */
/* better than cramming $_GET or mixing "" '' quotes in the statement */
if (isset($_GET['member_id'])) {
	$member_id = $_GET['member_id'];

	$sql = "DELETE FROM Person WHERE person_id = ?";

	if ($stmt = $conn->prepare($sql)) {
		$stmt->bind_param("i", $param_id);
		$param_id = $member_id;

		if ($stmt->execute()) {
			// Return back to member directory page
			header("location: member_directory.php");
			exit();
		} else {
			echo "<script>alert('Error deleting member: " . $conn->error . "');</script>";
		}
	}

	$stmt->close();
}

$conn->close();

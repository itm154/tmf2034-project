<?php
include 'db_connect.php';

if (isset($_POST['submit'])) {
	$person_name = $_POST['name'];
	$person_contact = $_POST['contact'];
	$person_dob = $_POST['dob'];
	$person_gender = $_POST['gender'];
	$trainer_specialization = $_POST['specialization'];
	$trainer_cert_lvl = $_POST['cert'];

	$person_stmt = $conn->prepare("INSERT INTO Person (person_name, person_contact, person_dob, person_gender) VALUES (?, ?, ?, ?)");
	$person_stmt->bind_param("ssss", $person_name, $person_contact, $person_dob, $person_gender);

	if ($person_stmt->execute()) {
		$person_id = $conn->insert_id;
		$person_stmt->close();

		$trainer_stmt = $conn->prepare("INSERT INTO Trainer (person_id, trainer_specialization, trainer_cert_lvl) VALUES (?, ?, ?)");
		$trainer_stmt->bind_param("iss", $person_id, $trainer_specialization, $trainer_cert_lvl);

		if ($trainer_stmt->execute()) {
			$trainer_stmt->close();
			header("Location: trainer_management.php");
			exit();
		} else {
			echo "<script>alert('Error adding trainer: " . $conn->error . "'); window.history.back();</script>";
		}
	} else {
		echo "<script>alert('Error adding person: " . $conn->error . "'); window.history.back();</script>";
	}
}
$conn->close();

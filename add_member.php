<?php
include("db_connect.php");

if (isset($_POST['submit'])) {
	$name = $_POST['name'];
	$contact = $_POST['contact'];
	$dob = $_POST['dob'];
	$gender = $_POST['gender'];
	$membership_type = $_POST['membership_type'];

	$person_insert_query = $conn->prepare("INSERT INTO Person (person_name, person_contact, person_dob, person_gender) VALUES (?, ?, ?, ?)");
	$person_insert_query->bind_param("ssss", $name, $contact, $dob, $gender);

	if ($person_insert_query->execute()) {
		$person_id = $conn->insert_id;
		$start_date = date('Y-m-d');
		$status = 'Active';

		$member_insert_query = $conn->prepare("INSERT INTO Member (person_id, membership_type_id, membership_status, membership_start_date) VALUES (?, ?, ?, ?)");
		$member_insert_query->bind_param("isss", $person_id, $membership_type, $status, $start_date);

		if ($member_insert_query->execute()) {
			header("Location: member_directory.php");
			exit();
		} else {
			echo "<script>alert('Error adding member: " . $conn->error . "');</script>";
		}
	} else {
		echo "<script>alert('Error adding person: " . $conn->error . "');</script>";
	}
}

$conn->close();

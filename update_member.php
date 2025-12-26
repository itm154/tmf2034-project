<?php
include("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST['member_id'], $_POST['name'], $_POST['contact'], $_POST['dob'], $_POST['gender'])) {
		$member_id = $_POST['member_id'];
		$name = $_POST['name'];
		$contact = $_POST['contact'];
		$dob = $_POST['dob'];
		$gender = $_POST['gender'];

		$query = "UPDATE Person SET person_name = ?, person_contact = ?, person_dob = ?, person_gender = ? WHERE person_id = ?";
		$stmt = $conn->prepare($query);
		$stmt->bind_param("ssssi", $name, $contact, $dob, $gender, $member_id);

		if ($stmt->execute()) {
			// Redirect back to the member's detail page
			header("Location: view_member.php?member_id=" . $member_id);
			exit();
		} else {
			echo "Error updating record: " . $conn->error;
		}

		$stmt->close();
	} else {
		echo "All fields are required.";
	}
} else {
	// If not a POST request, redirect to member directory or show an error
	header("Location: member_directory.php");
	exit();
}

$conn->close();

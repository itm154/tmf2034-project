<?php
include("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST['member_id'], $_POST['name'], $_POST['contact'], $_POST['dob'], $_POST['gender'], $_POST['membership_type'], $_POST['membership_status'])) {
		$member_id = $_POST['member_id'];
		$name = $_POST['name'];
		$contact = $_POST['contact'];
		$dob = $_POST['dob'];
		$gender = $_POST['gender'];
		$membership_type = $_POST['membership_type'];
		$membership_status = $_POST['membership_status'];

		$conn->begin_transaction();

		$query_person = "UPDATE Person SET person_name = ?, person_contact = ?, person_dob = ?, person_gender = ? WHERE person_id = ?";
		$stmt_person = $conn->prepare($query_person);
		$stmt_person->bind_param("ssssi", $name, $contact, $dob, $gender, $member_id);

		$query_member = "UPDATE Member SET membership_type_id = ?, membership_status = ? WHERE person_id = ?";
		$stmt_member = $conn->prepare($query_member);
		$stmt_member->bind_param("isi", $membership_type, $membership_status, $member_id);

		if ($stmt_person->execute() && $stmt_member->execute()) {
			$conn->commit();
			// Redirect back to the member's detail page
			header("Location: view_member.php?member_id=" . $member_id);
			exit();
		} else {
			$conn->rollback();
			echo "<script>alert('Error updating member: " . $conn->error . "');</script>";
		}

		$stmt_person->close();
		$stmt_member->close();
	} else {
		echo "<script>alert('Please fill in all fields!');</script>";
	}
} else {
	// Go back if not a post request
	header("Location: member_directory.php");
	exit();
}

$conn->close();

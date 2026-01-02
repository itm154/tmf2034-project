<?php
include 'db_connect.php';

if (isset($_POST['submit'])) {

	$member_person_id = intval($_POST['member_person_id']);
	$program_id = $_POST['program_id'];
	$payment_method = $_POST['payment_method'];

	if ($member_person_id <= 0 || $program_id <= 0 || $payment_method === "") {
		echo "<script> alert('Please select a Member, Program, and Payment Method.'); window.history.back(); </script>";
		exit();
	}

	// Check if the member is already enrolled in the program
	$stmt_check = $conn->prepare("SELECT enrolment_id FROM Enrolment WHERE member_person_id = ? AND program_id = ?");
	$stmt_check->bind_param("ii", $member_person_id, $program_id);
	$stmt_check->execute();
	$stmt_check->store_result();

	if ($stmt_check->num_rows > 0) {
		$stmt_check->close();
		echo "<script> alert('This member is already enrolled in this program.'); window.history.back(); </script>";
		exit();
	}
	$stmt_check->close();

	$stmt_fee = $conn->prepare("SELECT program_fee FROM Program WHERE program_id = ?");
	$stmt_fee->bind_param("i", $program_id);
	$stmt_fee->execute();
	$fee_result = $stmt_fee->get_result();
	$fee_row = $fee_result->fetch_assoc();
	$stmt_fee->close();

	if (!$fee_row) {
		echo "<script> alert('Program not found.'); window.history.back(); </script>";
		exit();
	}

	$fee = (float)$fee_row['program_fee'];

	$stmt_enrol = $conn->prepare("INSERT INTO Enrolment (enrolment_date, program_id, member_person_id) VALUES (CURDATE(), ?, ?)");
	$stmt_enrol->bind_param("ii", $program_id, $member_person_id);

	if ($stmt_enrol->execute()) {
		$enrolment_id = $conn->insert_id;
		$stmt_enrol->close();

		$stmt_inv = $conn->prepare("INSERT INTO Invoice (invoice_date, invoice_amount, invoice_payment_method, enrolment_id) VALUES (CURDATE(), ?, ?, ?)");
		$stmt_inv->bind_param("dsi", $fee, $payment_method, $enrolment_id);

		if ($stmt_inv->execute()) {
			$stmt_inv->close();
			echo "<script> alert('Enrolment created and invoice generated.'); window.location.href = 'signup_enrol.php'; </script>";
			exit();
		} else {
			echo "<script> alert('Invoice creation failed: " . $conn->error . "'); window.history.back(); </script>";
		}
	} else {
		echo "<script> alert('Enrolment failed: " . $conn->error . "'); window.history.back(); </script>";
	}
}

$conn->close();

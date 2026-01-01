<?php
include 'db_connect.php';

$member_id = null;
if (isset($_REQUEST['member_id'])) {
	$member_id = $_REQUEST['member_id'];
}

// Handle self form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

	if (isset($_POST['member_id'], $_POST['name'], $_POST['contact'], $_POST['dob'], $_POST['gender'], $_POST['membership_type'], $_POST['membership_status'])) {
		$member_id = $_POST['member_id'];
		$person_name = $_POST['name'];
		$person_contact = $_POST['contact'];
		$person_dob = $_POST['dob'];
		$person_gender = $_POST['gender'];
		$membership_type = $_POST['membership_type'];
		$membership_status = $_POST['membership_status'];

		$conn->begin_transaction();

		$query_person = file_get_contents('queries/person/update_person_details.sql');
		$stmt_person = $conn->prepare($query_person);
		$stmt_person->bind_param("ssssi", $person_name, $person_contact, $person_dob, $person_gender, $member_id);

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
			$error_message = "Error updating member: " . $conn->error;
		}

		$stmt_person->close();
		$stmt_member->close();
	} else {
		$error_message = "Please fill in all fields!";
	}
}

// Fetch initial member data
$member_info = null;
if ($member_id) {
	// Fetch member's info
	$member_query = file_get_contents('queries/member/select_member_details.sql');
	$member_stmt = $conn->prepare($member_query);
	$member_stmt->bind_param("i", $member_id);
	$member_stmt->execute();
	$member_result = $member_stmt->get_result();
	$member_info = $member_result->fetch_assoc();
	$member_stmt->close();
}

include 'navbar.php';
?>

<div class="container mt-4">

	<h1 class="display-4 mb-4">Member Information</h1>

	<?php if (isset($error_message)) : ?>
		<div class="alert alert-danger" role="alert">
			<?php echo $error_message; ?>
		</div>
	<?php endif; ?>


	<?php if ($member_info): ?>
		<form action="<?php echo $_SERVER["PHP_SELF"]; ?>?member_id=<?php echo $member_id; ?>" method="post">
			<input type="hidden" name="member_id" value="<?php echo $member_id; ?>">

			<div class="mb-3">
				<label for="name" class="form-label">Name:</label>
				<input type="text" id="name" name="name" class="form-control" value="<?php echo $member_info['person_name']; ?>" required>
			</div>

			<div class="mb-3">
				<label for="contact" class="form-label">Contact:</label>
				<input type="text" id="contact" name="contact" class="form-control" value="<?php echo $member_info['person_contact']; ?>" required>
			</div>

			<div class="mb-3">
				<label for="dob" class="form-label">Date of Birth:</label>
				<input type="date" id="dob" name="dob" class="form-control" value="<?php echo $member_info['person_dob']; ?>" required>
			</div>

			<div class="mb-3">
				<label for="gender" class="form-label">Gender:</label>
				<select id="gender" name="gender" class="form-select" required>
					<option value="Male" <?php if ($member_info['person_gender'] == 'Male') echo 'selected="selected"'; ?>>Male</option>
					<option value="Female" <?php if ($member_info['person_gender'] == 'Female') echo 'selected="selected"'; ?>>Female</option>
				</select>
			</div>

			<div class="mb-3">
				<label for="membership_type" class="form-label">Membership type:</label>
				<select id="membership_type" name="membership_type" class="form-select" required>
					<option value=1 <?php if ($member_info['membership_type_id'] == 1) echo 'selected="selected"'; ?>>Basic</option>
					<option value=2 <?php if ($member_info['membership_type_id'] == 2) echo 'selected="selected"'; ?>>Premium</option>
					<option value=3 <?php if ($member_info['membership_type_id'] == 3) echo 'selected="selected"'; ?>>Gold</option>
				</select>
			</div>

			<div class="mb-3">
				<label for="membership_status" class="form-label">Membership status:</label>
				<select id="membership_status" name="membership_status" class="form-select" required>
					<option value="Active" <?php if ($member_info['membership_status'] == 'Active') echo 'selected="selected"'; ?>>Active</option>
					<option value="Inactive" <?php if ($member_info['membership_status'] == 'Inactive') echo 'selected="selected"'; ?>>Inactive</option>
					<option value="Suspended" <?php if ($member_info['membership_status'] == 'Suspended') echo 'selected="selected"'; ?>>Suspended</option>
				</select>
			</div>

			<div class="mb-3">
				<button type="submit" name="submit" class="btn btn-primary">Save Changes</button>
			</div>
		</form>

		<h2 class="mb-3">Enrolled Programs</h2>
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered">
				<thead>
					<tr>
						<th>Program Name</th>
						<th>Category</th>
						<th>Enrolment Date</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$program_query = file_get_contents('queries/member/select_member_programs.sql');
					$program_stmt = $conn->prepare($program_query);
					$program_stmt->bind_param("i", $member_id);
					$program_stmt->execute();
					$program_result = $program_stmt->get_result();
					while ($program_row = $program_result->fetch_assoc()):
					?>
						<tr>
							<td><?php echo $program_row['program_name']; ?></td>
							<td><?php echo $program_row['category_name']; ?></td>
							<td><?php echo $program_row['enrolment_date']; ?></td>
						</tr>
					<?php endwhile;
					$program_stmt->close(); ?>
				</tbody>
			</table>
		</div>

		<h2 class="mb-3">Class Attendance History</h2>
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered">
				<thead>
					<tr>
						<th>Program Name</th>
						<th>Class Date & Time</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$attendance_query = file_get_contents('queries/member/select_member_attendance.sql');
					$attendance_stmt = $conn->prepare($attendance_query);
					$attendance_stmt->bind_param("i", $member_id);
					$attendance_stmt->execute();
					$attendance_result = $attendance_stmt->get_result();
					while ($attendance_row = $attendance_result->fetch_assoc()):
					?>
						<tr>
							<td><?php echo $attendance_row['program_name']; ?></td>
							<td><?php echo $attendance_row['class_datetime']; ?></td>
							<td><?php echo $attendance_row['attendance_status']; ?></td>
						</tr>
					<?php endwhile;
					$attendance_stmt->close(); ?>
				</tbody>
			</table>
		</div>

		<h2 class="mb-3">Payments (Invoices)</h2>
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered">
				<thead>
					<tr>
						<th>Program Name</th>
						<th>Invoice Date</th>
						<th>Amount (RM)</th>
						<th>Payment Method</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$payment_query = file_get_contents('queries/member/select_member_payments.sql');
					$payment_stmt = $conn->prepare($payment_query);
					$payment_stmt->bind_param("i", $member_id);
					$payment_stmt->execute();
					$payment_result = $payment_stmt->get_result();
					while ($payment_row = $payment_result->fetch_assoc()):
					?>
						<tr>
							<td><?php echo $payment_row['program_name']; ?></td>
							<td><?php echo $payment_row['invoice_date']; ?></td>
							<td><?php echo $payment_row['invoice_amount']; ?></td>
							<td><?php echo $payment_row['invoice_payment_method']; ?></td>
						</tr>
					<?php endwhile;
					$payment_stmt->close(); ?>
				</tbody>
			</table>
		</div>

		<?php else:
		if (isset($_REQUEST['member_id'])):  ?>
			<div class="alert alert-danger">
				Member not found
			</div>
		<?php else:  ?>
			<div class="alert alert-danger">
				Member ID not specified
			</div>
	<?php endif;
	endif; ?>

</div>

<?php $conn->close(); ?>

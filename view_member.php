<?php
include("navbar.php");
include("db_connect.php");
?>

<h1>Member Information</h1>

<?php if (isset($_GET['member_id'])) {
	$member_id = $_GET['member_id'];

	// Fetch member's info
	$member_query = "SELECT person_name, person_contact, person_dob, person_gender FROM Person WHERE person_id = ?";
	$stmt = $conn->prepare($member_query);
	$stmt->bind_param("i", $member_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$member_info = $result->fetch_assoc();
	$stmt->close();

	if ($member_info) {
?>
		<form action="update_member.php" method="post">
			<input type="hidden" name="member_id" value="<?php echo $member_id; ?>">

			<p>
				<label for="name">Name:</label>
				<input type="text" id="name" name="name" value="<?php echo $member_info['person_name']; ?>" required>
			</p>

			<p>
				<label for="contact">Contact:</label>
				<input type="text" id="contact" name="contact" value="<?php echo $member_info['person_contact']; ?>" required>
			</p>

			<p>
				<label for="dob">Date of Birth:</label>
				<input type="date" id="dob" name="dob" value="<?php echo $member_info['person_dob']; ?>" required>
			</p>

			<p>
				<label for="gender">Gender:</label>
				<select id="gender" name="gender" required>
					<option value="Male" <?php if ($member_info['person_gender'] == 'Male') echo 'selected'; ?>>Male</option>
					<option value="Female" <?php if ($member_info['person_gender'] == 'Female') echo 'selected'; ?>>Female</option>
				</select>
			</p>

			<p>
				<input type="submit" value="Save Changes">
			</p>
		</form>
	<?php
	}
	?>

	<h2>Enrolled Programs</h2>
	<table>
		<tr>
			<th>Program Name</th>
			<th>Category</th>
			<th>Enrolment Date</th>
		</tr>
		<?php
		$program_query = file_get_contents('queries/member_programs.sql');
		$stmt = $conn->prepare($program_query);
		$stmt->bind_param("i", $member_id);
		$stmt->execute();
		$result = $stmt->get_result();
		while ($row = $result->fetch_assoc()) {
		?>
			<tr>
				<td><?php echo $row['program_name']; ?></td>
				<td><?php echo $row['category_name']; ?></td>
				<td><?php echo $row['enrolment_date']; ?></td>
			</tr>
		<?php }
		$stmt->close(); ?>
	</table>

	<h2>Class Attendance History</h2>
	<table>
		<tr>
			<th>Program Name</th>
			<th>Class Date & Time</th>
			<th>Status</th>
		</tr>
		<?php
		$attendance_query = file_get_contents('queries/member_attendance.sql');
		$stmt = $conn->prepare($attendance_query);
		$stmt->bind_param("i", $member_id);
		$stmt->execute();
		$result = $stmt->get_result();
		while ($row = $result->fetch_assoc()) {
		?>
			<tr>
				<td><?php echo $row['program_name']; ?></td>
				<td><?php echo $row['class_datetime']; ?></td>
				<td><?php echo $row['attendance_status']; ?></td>
			</tr>
		<?php }
		$stmt->close(); ?>
	</table>

	<h2>Payments (Invoices)</h2>
	<table>
		<tr>
			<th>Program Name</th>
			<th>Invoice Date</th>
			<th>Amount</th>
			<th>Payment Method</th>
		</tr>
		<?php
		$payment_query = file_get_contents('queries/member_payment.sql');
		$stmt = $conn->prepare($payment_query);
		$stmt->bind_param("i", $member_id);
		$stmt->execute();
		$result = $stmt->get_result();
		while ($row = $result->fetch_assoc()) {
		?>
			<tr>
				<td><?php echo $row['program_name']; ?></td>
				<td><?php echo $row['invoice_date']; ?></td>
				<td><?php echo $row['invoice_amount']; ?></td>
				<td><?php echo $row['invoice_payment_method']; ?></td>
			</tr>
		<?php }
		$stmt->close(); ?>
	</table>

<?php } else {
	echo "<p>No member ID specified.</p>";
} ?>

<?php $conn->close(); ?>

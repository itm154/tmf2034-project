<?php
include("navbar.php");
include("db_connect.php");
?>

<h1>Member Information</h1>

<?php if (isset($_GET['member_id'])) {
	$member_id = $_GET['member_id'];

	// Fetch member's info
	$member_query = "SELECT p.person_name, p.person_contact, p.person_dob, p.person_gender, m.membership_type_id, m.membership_status FROM Person p JOIN Member m ON p.person_id = m.person_id WHERE m.person_id = ?";
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
					<option value="Male" <?php if ($member_info['person_gender'] == 'Male') echo 'selected="selected"'; ?>>Male</option>
					<option value="Female" <?php if ($member_info['person_gender'] == 'Female') echo 'selected="selected"'; ?>>Female</option>
				</select>
			</p>

			<p>
				<label for="membership_type">Membership type:</label>
				<select id="membership_type" name="membership_type" required>
					<option value=1 <?php if ($member_info['membership_type_id'] == 1) echo 'selected="selected"'; ?>>Basic</option>
					<option value=2 <?php if ($member_info['membership_type_id'] == 2) echo 'selected="selected"'; ?>>Premium</option>
					<option value=3 <?php if ($member_info['membership_type_id'] == 3) echo 'selected="selected"'; ?>>Gold</option>
				</select>
			</p>

			<p>
				<label for="membership_status">Membership status:</label>
				<select id="membership_status" name="membership_status" required>
					<option value="Active" <?php if ($member_info['membership_status'] == 'Active') echo 'selected="selected"'; ?>>Active</option>
					<option value="Inactive" <?php if ($member_info['membership_status'] == 'Inactive') echo 'selected="selected"'; ?>>Inactive</option>
					<option value="Suspended" <?php if ($member_info['membership_status'] == 'Suspended') echo 'selected="selected"'; ?>>Suspended</option>
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
		$program_query = file_get_contents('queries/member/member_programs.sql');
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
		$attendance_query = file_get_contents('queries/member/member_attendance.sql');
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
		$payment_query = file_get_contents('queries/member/member_payment.sql');
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

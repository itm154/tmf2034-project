<?php
include 'db_connect.php';
include 'navbar.php';
?>

<div class="container mt-4">

	<h1 class="display-4 mb-4">Enrolment</h1>

	<h2 class="mb-3">Enrol Member</h2>
	<form method="POST" action="add_enrolment.php">

		<div class="mb-3">
			<label for="member_input" class="form-label">Member</label>
			<input class="form-control" list="datalistOptions" id="member_input" name="member_person_id" placeholder="Type to search member..." required>
			<datalist id="datalistOptions">
				<?php
				$member_query = "SELECT m.person_id, p.person_name FROM Member m JOIN Person p ON p.person_id = m.person_id ORDER BY p.person_name ASC";
				$member_result = $conn->query($member_query);

				while ($member = $member_result->fetch_assoc()):
				?>
					<option value="<?php echo $member['person_id'] . " - " . $member['person_name'] ?>"><?php echo $member['person_id'] . " - " . $member['person_name'] ?></option>
				<?php endwhile ?>
			</datalist>
		</div>

		<div class="mb-3">
			<label for="program_id" class="form-label">Program</label>
			<select name="program_id" class="form-select" required>
				<?php
				$program_query = " SELECT program_id, program_name, program_fee FROM Program ORDER BY program_name ASC";
				$program_result = $conn->query($program_query);

				while ($program = $program_result->fetch_assoc()):
				?>
					<option value="<?php echo $program['program_id'] ?>"><?php echo $program['program_name'] . " (RM" . $program['program_fee'] . ")" ?></option>
				<?php endwhile ?>
			</select>
		</div>

		<div class="mb-3">
			<label for="payment_method" class="form-label">Payment Method: </label>
			<select name="payment_method" class="form-select" required>
				<option value="Cash">Cash</option>
				<option value="Card">Card</option>
				<option value="Duitnow">DuitNow</option>
			</select>
		</div>

		<button type="submit" name="submit" class="btn btn-primary">Enrol Member</button>
	</form>

	<h2 class="mb-3">Payment History</h2>
	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered">
			<thead>
				<tr>
					<th>Invoice ID</th>
					<th>Date</th>
					<th>Amount (RM)</th>
					<th>Payment Method</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$invoice_query = "SELECT * FROM Invoice ORDER BY invoice_date DESC";
				$result = $conn->query($invoice_query);

				while ($row = mysqli_fetch_assoc($result)):
				?>
					<tr>
						<td><?php echo $row['invoice_id'] ?></td>
						<td><?php echo $row['invoice_date'] ?></td>
						<td><?php echo $row['invoice_amount'] ?></td>
						<td><?php echo $row['invoice_payment_method'] ?></td>
					</tr>
				<?php endwhile; ?>
			</tbody>
		</table>
	</div>

</div>

<?php $conn->close(); ?>

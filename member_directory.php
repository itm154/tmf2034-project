<?php
include 'db_connect.php';
include 'navbar.php';
?>

<div class="container mt-4">
	<h1 class="display-4 mb-4">Member Directory</h1>

	<h2 class="mb-3">Register New Member</h2>
	<form action="add_member.php" method="post">
		<div class="mb-3">
			<label for="name" class="form-label">Name:</label>
			<input type="text" class="form-control" id="name" name="name" required>
		</div>

		<div class="mb-3">
			<label for="contact" class="form-label">Contact:</label>
			<input type="text" class="form-control" id="contact" name="contact" required>
		</div>

		<div class="mb-3">
			<label for="dob" class="form-label">Date of Birth:</label>
			<input type="date" class="form-control" id="dob" name="dob" required>
		</div>

		<div class="mb-3">
			<label for="gender" class="form-label">Gender:</label>
			<select class="form-select" id="gender" name="gender" required>
				<option value="Male">Male</option>
				<option value="Female">Female</option>
			</select>
		</div>

		<div class="mb-3">
			<label for="membership_type" class="form-label">Membership:</label>
			<select class="form-select" id="membership_type" name="membership_type" required>
				<option value=1>Basic</option>
				<option value=2>Premium</option>
				<option value=3>Gold</option>
			</select>
		</div>

		<button type="submit" name="submit" class="btn btn-primary">Register</button>
	</form>

	<h2 class="mb-3">Member List</h2>
	<div class="table-responsive">
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th scope="col">ID</th>
					<th scope="col">Name</th>
					<th scope="col">Membership Tier</th>
					<th scope="col">Status</th>
					<th scope="col">Joined Programs</th>
					<th scope="col">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php

				$query = file_get_contents('queries/member/select_members_info.sql');
				$result = $conn->query($query);
				while ($row = mysqli_fetch_assoc($result)):
				?>
					<tr>
						<td>
							<?php echo $row['person_id'] ?>
						</td>
						<td>
							<?php echo $row['person_name'] ?>
						</td>
						<td>
							<?php echo $row['type_name'] ?>
						</td>
						<td>
							<?php echo $row['membership_status'] ?>
						</td>
						<td>
							<?php echo $row['program_count'] ?>
						</td>
						<td>
							<div class="d-flex gap-2">
								<a href="view_member.php?member_id=<?php echo $row['person_id'] ?>" class="btn btn-info btn-sm">View</a>
								<a href="delete_member.php?member_id=<?php echo $row['person_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this member?');">Delete</a>
							</div>
						</td>
					</tr>
				<?php endwhile; ?>
			</tbody>
		</table>
	</div>
</div>

<?php $conn->close(); ?>

<?php
include 'db_connect.php';
include 'navbar.php';
?>

<html>


<h1>Member Directory</h1>

<form action="add_member.php" method="post">
	Name: <input type="text" name="name" required><br>
	Contact: <input type="text" name="contact" required><br>
	Date of Birth: <input type="date" name="dob" required><br>
	Gender: <select name="gender" required>
		<option value="Male">Male</option>
		<option value="Female">Female</option>
	</select><br>
	Membership: <select name="membership_type" required>
		<option value=1>Basic</option>
		<option value=2>Premium</option>
		<option value=3>Gold</option>
	</select><br>
	<input type="submit" name="submit" value="Register">
</form>

<table>
	<tr>
		<th>ID</th>
		<th>Name</th>
		<th>Membership Tier</th>
		<th>Status</th>
		<th>Joined Programs</th>
		<th colspan="2">Actions</th>
	</tr>
	<?php

	$query = file_get_contents('queries/member_info.sql');
	$result = $conn->query($query);
	while ($row = mysqli_fetch_assoc($result)) {
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
				<a href="view_member.php?member_id=<?php echo $row['person_id'] ?>">View</a>
			</td>
			<td>
				<a href="delete_member.php?member_id=<?php echo $row['person_id'] ?>" onclick="return confirm('Are you sure you want to delete this member?');">Delete</a>
			</td>
		</tr>
	<?php
	}
	?>
</table>

</html>

<?php $conn->close(); ?>

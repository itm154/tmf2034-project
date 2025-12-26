<?php
include 'db_connect.php';
include 'navbar.php';
?>

<html>

<h1>Member Directory</h1>
<table>
	<tr>
		<th>ID</th>
		<th>Name</th>
		<th>Membership Tier</th>
		<th>Status</th>
		<th>Joined Programs</th>
		<th>Actions</th>
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
		</tr>
	<?php
	}
	?>
</table>

</html>

<?php $conn->close(); ?>

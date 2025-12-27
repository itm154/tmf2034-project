<?php
include 'db_connect.php';
include 'navbar.php';
?>

<html>
<h1>Class management</h1>

<h1>Class Schedule</h1>

<table>
	<tr>
		<th>Class ID</th>
		<th>Date and Time</th>
		<th>Class Status</th>
		<th>Trainer</th>

	</tr>

	<?php

	$query = file_get_contents('queries/class_info.sql');
	$result = $conn->query($query);
	while ($row = mysqli_fetch_assoc($result)) {
	?>
		<tr>
			<td>
				<?php echo $row['class_id'] ?>
			</td>
			<td>
				<?php echo $row['class_datetime'] ?>
			</td>
			<td>
				<?php echo $row['class_status'] ?>
			</td>
			<td>
				<?php echo $row['trainer_name'] ?>
			</td>
			<td>
				<a href="delete_class.php?class_id=<?php echo $row['class_id'] ?>" onclick="return confirm('Are you sure you want to delete this class?');">Delete</a>
			</td>
		</tr>
	<?php } ?>


</table>

</html>

<?php $conn->close(); ?>

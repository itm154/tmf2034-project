<?php
include 'db_connect.php';
include 'navbar.php';
?>

<html>
<h1>Class management</h1>

<h2>Add Class</h2>
<form action="add_class.php" method="post">
	<p>
		<label for="trainer">Trainer:</label>
		<select id="trainer" name="trainer_id" required>
			<?php
			$trainers_query = "SELECT T.person_id, P.person_name FROM Trainer T JOIN Person P ON T.person_id = P.person_id ORDER BY P.person_name";
			$trainers_result = $conn->query($trainers_query);
			while ($trainer = $trainers_result->fetch_assoc()):
			?>
				<option value="<?php echo $trainer['person_id']; ?>"><?php echo $trainer['person_name']; ?></option>
			<?php endwhile; ?>
		</select>
	</p>

	<p>
		<label for="program">Program:</label>
		<select id="program" name="program_id" required>
			<?php
			$programs_query = "SELECT program_id, program_name FROM Program ORDER BY program_name";
			$programs_result = $conn->query($programs_query);
			while ($program = $programs_result->fetch_assoc()):
			?>
				<option value="<?php echo $program['program_id']; ?>"><?php echo $program['program_name']; ?></option>
			<?php endwhile; ?>
		</select>
	</p>

	<p>
		<label for="datetime">Date and Time:</label>
		<input type="datetime-local" id="datetime" name="class_datetime" required>
	</p>

	<p>
		<input type="submit" name="submit" value="Add Class">
	</p>
</form>

<h2>Class Schedule</h2>

<table>
	<tr>
		<th>ID</th>
		<th>Trainer</th>
		<th>Program</th>
		<th>Class Status</th>
		<th>Date and Time</th>
		<th>Actions</th>

	</tr>

	<?php

	$query = file_get_contents('queries/class/class_info.sql');
	$result = $conn->query($query);
	while ($row = mysqli_fetch_assoc($result)) {
	?>
		<tr>
			<td>
				<?php echo $row['class_id'] ?>
			</td>
			<td>
				<?php echo $row['person_name'] ?>
			</td>
			<td>
				<?php echo $row['program_name'] ?>
			</td>
			<td>
				<?php echo $row['class_status'] ?>
			</td>
			<td>
				<?php echo $row['class_datetime'] ?>
			</td>
			<td>
				<a href="delete_class.php?class_id=<?php echo $row['class_id'] ?>" onclick="return confirm('Are you sure you want to delete this class?');">Delete</a>
			</td>
		</tr>
	<?php } ?>


</table>

</html>

<?php $conn->close(); ?>

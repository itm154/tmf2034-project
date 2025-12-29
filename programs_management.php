<?php
include 'db_connect.php';
include 'navbar.php';

?>

<h1>Programs management</h1>

<form action="add_program.php" method="post">
	<p>
		<label for="program_name">Program Name:</label>
		<input type="text" id="program_name" name="program_name" required>
	</p>

	<p>
		<label for="gender">Program Category:</label>
		<select id="program_category" name="program_category" required>
			<?php
			$query = "SELECT * from Program_Category ORDER BY category_name ASC";
			$result = $conn->query($query);
			while ($row = mysqli_fetch_assoc($result)) {
			?>
				<option value=<?php echo $row['category_id'] ?>><?php echo $row['category_name'] ?></option>
			<?php } ?>
		</select>
	</p>

	<p>
		<label for="program_duration">Program Duration (Weeks):</label>
		<input type="number" id="program_duration" name="program_duration" required>
	</p>

	<p>
		<label for="program_fee">Program Fee (RM):</label>
		<input type="number" id="program_fee" name="program_fee" step=".01" required>
	</p>

	<p>
		<input type="submit" name="submit" value="Add Program">
	</p>
</form>

<h2>Programs</h2>
<table>
	<th>Program Name</th>
	<th>Program Duration (Weeks)</th>
	<th>Program Fee (RM)</th>
	<th>Program category</th>
	<th colspan="2">Actions</th>
	<?php
	$query = "SELECT p.*, pc.category_name FROM Program p JOIN Program_Category pc ON p.category_id = pc.category_id ORDER BY p.program_name ASC";
	$result = $conn->query($query);

	while ($row = mysqli_fetch_assoc($result)) {
	?>
		<tr>
			<td><?php echo $row['program_name'] ?></td>
			<td><?php echo $row['program_duration_weeks'] ?></td>
			<td><?php echo $row['program_fee'] ?></td>
			<td><?php echo $row['category_name'] ?></td>
			<td>
				<a href="">Edit</a>
			</td>
			<td>
				<a href="delete_program.php?id=<?php echo $row['program_id'] ?>" onclick="return confirm('Are you sure you want to delete this program?')">Delete</a>
			</td>
		</tr>
	<?php } ?>
</table>

<form action="add_category.php" method="post">
	<p>
		<label for="category_name">Category name:</label>
		<input type="text" id="category_name" name="category_name" required>
	</p>

	<p>
		<input type="submit" name="submit" value="Add Category">
	</p>
</form>

<h2>Program Categories</h2>
<table>
	<th>Program category</th>
	<th colspan="2">Actions</th>
	<?php
	$query = "SELECT * FROM Program_Category ORDER BY category_name ASC";
	$result = $conn->query($query);

	while ($row = mysqli_fetch_assoc($result)) {
	?>
		<tr>
			<td><?php echo $row['category_name'] ?></td>
			<td>
				<a href="">Edit</a>
			</td>
			<td>
				<a href="delete_category.php?id=<?php echo $row['category_id'] ?>" onclick="return confirm('Are you sure you want to delete this category?')">Delete</a>
			</td>
		</tr>
	<?php } ?>
</table>

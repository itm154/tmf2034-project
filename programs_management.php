<?php
include 'db_connect.php';
include 'navbar.php';

?>

<div class="container mt-4">
	<h1 class="display-4 mb-4">Programs management</h1>

	<h2 class="mb-3">Add Categories</h2>
	<form action="add_category.php" method="post">
		<div class="mb-3">
			<label for="category_name" class="form-label">Category name:</label>
			<input type="text" id="category_name" name="category_name" class="form-control" required>
		</div>
		<button type="submit" name="submit" class="btn btn-primary">Add Category</button>
	</form>

	<h2 class="mb-3">Category List</h2>
	<div class="table-responsive">
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th>Program category</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$query = "SELECT * FROM Program_Category ORDER BY category_name ASC";
				$result = $conn->query($query);

				while ($row = mysqli_fetch_assoc($result)):
				?>
					<tr>
						<td><?php echo $row['category_name'] ?></td>
						<td>
							<div class="btn-group" role="group">
								<a href="edit_category.php?id=<?php echo $row['category_id'] ?>" class="btn btn-info btn-sm">Edit</a>
								<a href="delete_category.php?id=<?php echo $row['category_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this category?')">Delete</a>
							</div>
						</td>
					</tr>
				<?php endwhile; ?>
			</tbody>
		</table>
	</div>

	<h2 class="mb-3">Add programs</h2>
	<form action="add_program.php" method="post">
		<div class="mb-3">
			<label for="program_name" class="form-label">Program Name:</label>
			<input type="text" id="program_name" name="program_name" class="form-control" required>
		</div>

		<div class="mb-3">
			<label for="program_category" class="form-label">Program Category:</label>
			<select id="program_category" name="program_category" class="form-select" required>
				<?php
				$query = "SELECT * from Program_Category ORDER BY category_name ASC";
				$result = $conn->query($query);
				while ($row = mysqli_fetch_assoc($result)):
				?>
					<option value=<?php echo $row['category_id'] ?>><?php echo $row['category_name'] ?></option>
				<?php endwhile; ?>
			</select>
		</div>

		<div class="mb-3">
			<label for="program_duration" class="form-label">Program Duration (Weeks):</label>
			<input type="number" id="program_duration" name="program_duration" class="form-control" required>
		</div>

		<div class="mb-3">
			<label for="program_fee" class="form-label">Program Fee (RM):</label>
			<input type="number" id="program_fee" name="program_fee" step=".01" class="form-control" required>
		</div>

		<button type="submit" name="submit" class="btn btn-primary">Add Program</button>
	</form>

	<h2 class="mb-3">Program List</h2>
	<div class="table-responsive">
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th>Program Name</th>
					<th>Program Duration (Weeks)</th>
					<th>Program Fee (RM)</th>
					<th>Program category</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$query = file_get_contents('queries/program/select_programs_with_category.sql');
				$result = $conn->query($query);

				while ($row = mysqli_fetch_assoc($result)):
				?>
					<tr>
						<td><?php echo $row['program_name'] ?></td>
						<td><?php echo $row['program_duration_weeks'] ?></td>
						<td><?php echo $row['program_fee'] ?></td>
						<td><?php echo $row['category_name'] ?></td>
						<td>
							<div class="btn-group" role="group">
								<a href="edit_program.php?id=<?php echo $row['program_id'] ?>" class="btn btn-info btn-sm">Edit</a>
								<a href="delete_program.php?id=<?php echo $row['program_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this program?')">Delete</a>
							</div>
						</td>
					</tr>
				<?php endwhile; ?>
			</tbody>
		</table>
	</div>
</div>

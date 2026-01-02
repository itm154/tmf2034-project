<?php
include 'db_connect.php';
include 'navbar.php';
?>

<div class="container mt-4">
	<h1 class="display-4 mb-4">Notification Management</h1>

	<h2 class="mb-3">Add Notification</h2>
	<form action="add_notification.php" method="post">
		<div class="mb-3">
			<label for="notification_content" class="form-label">Content:</label>
			<textarea id="notification_content" name="notification_content" class="form-control" rows="3" required></textarea>
		</div>
		<button type="submit" name="submit" class="btn btn-primary">Add Notification</button>
	</form>

	<h2 class="mb-3">Notification List</h2>
	<div class="table-responsive">
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th>ID</th>
					<th>Content</th>
					<th>Date & Time</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$query = "SELECT * FROM Notification ORDER BY notification_datetime DESC";
				$result = $conn->query($query);

				while ($row = mysqli_fetch_assoc($result)):
				?>
					<tr>
						<td><?php echo $row['notification_id'] ?></td>
						<td><?php echo $row['notification_content'] ?></td>
						<td><?php echo $row['notification_datetime'] ?></td>
						<td>
							<div class="btn-group" role="group">
								<a href="edit_notification.php?id=<?php echo $row['notification_id'] ?>" class="btn btn-info btn-sm">Edit</a>
								<a href="delete_notification.php?id=<?php echo $row['notification_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this notification?')">Delete</a>
							</div>
						</td>
					</tr>
				<?php endwhile; ?>
			</tbody>
		</table>
	</div>
</div>

<?php $conn->close(); ?>

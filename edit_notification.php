<?php
include 'db_connect.php';

$notification_id = $_REQUEST['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

	$notification_content = $_POST['notification_content'];

	$stmt = $conn->prepare("UPDATE Notification SET notification_content = ? WHERE notification_id = ?");
	$stmt->bind_param("si", $notification_content, $notification_id);

	if ($stmt->execute()) {
		header("Location: notification_management.php");
		exit();
	} else {
		$error_message = "Error updating notification: " . $conn->error;
	}

	$stmt->close();
}

$notification = null;
if ($notification_id) {
	$stmt = $conn->prepare("SELECT * FROM Notification WHERE notification_id = ?");
	$stmt->bind_param("i", $notification_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$notification = $result->fetch_assoc();
	$stmt->close();
}

include 'navbar.php';
?>

<div class="container mt-4">
	<h1 class="display-4 mb-4">Edit Notification</h1>

	<?php if (isset($error_message)): ?>
		<div class="alert alert-danger" role="alert">
			<?php echo $error_message; ?>
		</div>
	<?php endif; ?>

	<?php if ($notification): ?>
		<form action="<?php echo $_SERVER["PHP_SELF"] . "?id=" . $notification_id; ?>" method="post">
			<div class="mb-3">
				<label for="notification_content" class="form-label">Content:</label>
				<textarea id="notification_content" name="notification_content" class="form-control" rows="3" required><?php echo $notification['notification_content']; ?></textarea>
			</div>
			<button type="submit" name="submit" class="btn btn-primary">Update Notification</button>
		</form>
	<?php else: ?>
		<div class="alert alert-warning" role="alert">
			Could not retrieve notification details.
		</div>
	<?php endif; ?>
</div>

<?php $conn->close(); ?>

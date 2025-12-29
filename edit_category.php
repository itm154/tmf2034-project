<?php
include 'db_connect.php';

$category_id = $_REQUEST['id'];

// Handle self form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

	$category_name = $_POST['category_name'];

	$stmt = $conn->prepare("UPDATE Program_Category SET category_name = ? WHERE category_id = ?");
	$stmt->bind_param("si", $category_name, $category_id);

	if ($stmt->execute()) {
		header("Location: programs_management.php");
		exit();
	} else {
		$error_message = "Error updating category: " . $conn->error;
	}

	$stmt->close();
}

// Fetch initial category information
$category = null;
if ($category_id) {
	$stmt = $conn->prepare("SELECT * FROM Program_Category WHERE category_id = ?");
	$stmt->bind_param("i", $category_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$category = $result->fetch_assoc();
	$stmt->close();
}

include 'navbar.php';
?>

<h1>Edit Program Category</h1>

<form action="<?php echo $_SERVER["PHP_SELF"] . "?id=" . $category_id; ?>" method="post">
	<p>
		<label for="category_name">Category name:</label>
		<input type="text" id="category_name" name="category_name" value="<?php echo $category['category_name']; ?>" required>
	</p>

	<p>
		<input type="submit" name="submit" value="Update Category">
	</p>
</form>

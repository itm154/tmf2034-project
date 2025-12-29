<?php
include("db_connect.php");

if (isset($_GET['id'])) {
	$category_id = $_GET['id'];

	// Check if the category is being used by any programs
	$check_stmt = $conn->prepare("SELECT COUNT(*) as program_count FROM Program WHERE category_id = ?");
	$check_stmt->bind_param("i", $category_id);
	$check_stmt->execute();
	$result = $check_stmt->get_result();
	$row = $result->fetch_assoc();

	if ($row['program_count'] > 0) {
		// Category is in use, show an error message and redirect
		echo "<script>
                alert('This category cannot be deleted because it is currently assigned to one or more programs.');
                window.location.href = 'programs_management.php';
              </script>";
		exit;
	} else {
		// No programs are using this category, so its safe to delete
		$delete_stmt = $conn->prepare("DELETE FROM Program_Category WHERE category_id = ?");
		$delete_stmt->bind_param("i", $category_id);

		if ($delete_stmt->execute()) {
			header("Location: programs_management.php");
			exit;
		} else {
			// This could happen if there's another unexpected error
			echo "<script>
                    alert('Error deleting category: " . $delete_stmt->error . "');
                    window.location.href = 'programs_management.php';
                  </script>";
			exit;
		}
	}
}

$conn->close();

<?php
include("db_connect.php");

if (isset($_POST['submit'])) {
	$category_name = $_POST['category_name'];

	$program_insert_query = $conn->prepare("INSERT INTO Program_Category (category_name) VALUES (?)");
	$program_insert_query->bind_param("s", $category_name);

	if ($program_insert_query->execute()) {
		header("Location: programs_management.php");
		exit;
	} else {
		echo "<script>alert('Error adding category: " . $conn->error . "');</script>";
	}
}

$conn->close();

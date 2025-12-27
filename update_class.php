<?php
include("db_connect.php");//create connectiuon with database

if ($_SERVER["REQUEST_METHOD"] == "POST") {//ensuring the existense of variables
	if (isset($_POST['class_id'], $_POST['person_name'], $_POST['program_name'], $_POST['class_status'], $_POST['class_datetime'])) {
		$class_id = $_POST['class_id'];
		$person_name = $_POST['person_name'];
        $program_name = $_POST['program_name'];
        $class_status = $_POST['class_status'];
        $class_datetime = $_POST['class_datetime'];


		$conn->begin_transaction();

        $query_class  = "UPDATE Class SET class_datetime = ?, class_status = ? WHERE class_id = ?";
        $stmt_class = $conn->prepare($query_class);
        $stmt_class->bind_param("ssi", $class_datetime, $class_status, $class_id);

        $query_trainer = "UPDATE Person p JOIN trainer_program_history thp ON p.person_id = thp.trainer_person_id JOIN Class c ON thp.history_id = c.history_id SET p.person_name = ? WHERE c.class_id = ?";
        $stmt_trainer = $conn->prepare($query_trainer);
        $stmt_trainer->bind_param("si",$person_name, $class_id);

        $query_program = "UPDATE Program pr JOIN trainer_program_history thp ON pr.program_id = thp.program_id JOIN Class c ON thp.history_id = c.history_id SET pr.program_name = ? WHERE c.class_id = ?";
        $stmt_program = $conn->prepare($query_program);
        $stmt_program->bind_param("si", $program_name, $class_id);


        if ($stmt_class->execute() && $stmt_trainer->execute() && $stmt_program->execute()) {
			$conn->commit();
			// Check class information
			header("Location: class_management.php?class_id=" . $class_id);
			exit();
		} else {
			$conn->rollback();
			echo "<script>alert('Error updating class: " . $conn->error . "');</script>";
		}

		$stmt_class->close();
		$stmt_trainer->close();
        $stmt_program->close();
	} else {
		echo "<script>alert('All fields must be filled.');</script>";
	}
} else {
	// Go back if not a post request
	header("Location: class_management.php");
	exit();
}

$conn->close();

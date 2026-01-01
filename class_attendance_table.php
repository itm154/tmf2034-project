<?php
// this code too messy and big to put in ./view_class.php so its split into its own thing

// Get program_id from history_id
$query_program_id = "SELECT program_id FROM Trainer_Program_History WHERE history_id = ?";
$stmt_program_id = $conn->prepare($query_program_id);
$stmt_program_id->bind_param("i", $class_info['history_id']);
$stmt_program_id->execute();
$result_program_id = $stmt_program_id->get_result();

if ($row_program_id = $result_program_id->fetch_assoc()) {
	$program_id = $row_program_id['program_id'];

	// Get members enrolled in the program
	$query_enrolled_members = "SELECT p.person_id, p.person_name FROM Person p JOIN Enrolment e ON p.person_id = e.member_person_id WHERE e.program_id = ?";
	$stmt_enrolled_members = $conn->prepare($query_enrolled_members);
	$stmt_enrolled_members->bind_param("i", $program_id);
	$stmt_enrolled_members->execute();
	$result_enrolled_members = $stmt_enrolled_members->get_result();

	while ($member = $result_enrolled_members->fetch_assoc()):
		// Check for attendance record
		$query_attendance = "SELECT attendance_status FROM Attendance WHERE person_id = ? AND class_id = ?";
		$stmt_attendance = $conn->prepare($query_attendance);
		$stmt_attendance->bind_param("ii", $member['person_id'], $class_id);
		$stmt_attendance->execute();
		$result_attendance = $stmt_attendance->get_result();

		if ($result_attendance->num_rows > 0) {
			$attendance = $result_attendance->fetch_assoc();
			$attendance_status = $attendance['attendance_status'];
		} else {
			// If attendance record dont exist create a new one
			// Default attendance to absent
			$insert_attendance = "INSERT INTO Attendance (person_id, class_id, attendance_status) VALUES (?, ?, 'Absent')";
			$stmt_insert_attendance = $conn->prepare($insert_attendance);
			$stmt_insert_attendance->bind_param("ii", $member['person_id'], $class_id);
			$stmt_insert_attendance->execute();
			$stmt_insert_attendance->close();
			$attendance_status = 'Absent';
		}
		$stmt_attendance->close();
?>
		<tr>
			<td><?php echo $member['person_name']; ?></td>
			<td>
				<input type='hidden' name='member_ids[]' value='<?php echo $member['person_id']; ?>'>
				<select name='attendance[<?php echo $member['person_id']; ?>]' class='form-select'>
					<option value='Attended' <?php echo ($attendance_status == 'Attended' ? 'selected' : ''); ?>>Attended</option>
					<option value='Absent' <?php echo ($attendance_status == 'Absent' ? 'selected' : ''); ?>>Absent</option>
				</select>
			</td>
		</tr>
<?php endwhile;
	$stmt_enrolled_members->close();
}
$stmt_program_id->close();
?>

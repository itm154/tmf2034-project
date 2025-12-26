<?php
include 'db_connect.php';
include 'navbar.php';
?>

<?php
// ================= ADD TRAINER =================
if (isset($_POST['add_trainer'])) {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $specialization = $_POST['specialization'];
    $cert = $_POST['cert'];

    // Insert into Person
    $conn->query("
        INSERT INTO Person (person_name, person_contact, person_dob, person_gender)
        VALUES ('$name', '$contact', '$dob', '$gender')
    ");

    $person_id = $conn->insert_id;

    // Insert into Trainer
    $conn->query("
        INSERT INTO Trainer (person_id, trainer_specialization, trainer_cert_lvl)
        VALUES ($person_id, '$specialization', '$cert')
    ");
}

// ================= DELETE TRAINER =================
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    // Cascade delete handles Trainer
    $conn->query("DELETE FROM Person WHERE person_id = $id");
}

// ================= EDIT TRAINER =================
$edit_id = "";
$edit = [];

if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $res = $conn->query("
        SELECT p.*, t.trainer_specialization, t.trainer_cert_lvl
        FROM Person p
        JOIN Trainer t ON p.person_id = t.person_id
        WHERE p.person_id = $edit_id
    ");
    $edit = $res->fetch_assoc();
}

// ================= UPDATE TRAINER =================
if (isset($_POST['update_trainer'])) {
    $id = $_POST['id'];

    $conn->query("
        UPDATE Person SET
            person_name='{$_POST['name']}',
            person_contact='{$_POST['contact']}',
            person_dob='{$_POST['dob']}',
            person_gender='{$_POST['gender']}'
        WHERE person_id=$id
    ");

    $conn->query("
        UPDATE Trainer SET
            trainer_specialization='{$_POST['specialization']}',
            trainer_cert_lvl='{$_POST['cert']}'
        WHERE person_id=$id
    ");
}

// ================= FETCH TRAINERS =================
$trainers = $conn->query("
    SELECT p.person_id, p.person_name, p.person_contact,
           t.trainer_specialization, t.trainer_cert_lvl
    FROM Person p
    JOIN Trainer t ON p.person_id = t.person_id
");

// ================= TRAINER PERFORMANCE =================
$performance = $conn->query("
    SELECT 
        p.person_name AS trainer_name,
        COUNT(c.class_id) AS total_classes_taught,
        SUM(CASE WHEN a.attendance_status='Absent' THEN 1 ELSE 0 END) AS total_missed_classes
    FROM Trainer t
    JOIN Person p ON t.person_id = p.person_id
    JOIN Trainer_Program_History tph ON t.person_id = tph.trainer_person_id
    JOIN Class c ON tph.history_id = c.history_id
    LEFT JOIN Attendance a ON c.class_id = a.class_id
    GROUP BY p.person_id
");
?>

<h1>Trainer management</h1>

<!-- ================= ADD / UPDATE FORM ================= -->
<div class="card">
<h2>Add / Update Trainer</h2>
<form method="POST">
<input type="hidden" name="id" value="<?php echo $edit_id; ?>">

Name:
<input type="text" name="name" required value="<?php echo $edit['person_name'] ?? ''; ?>">

Contact:
<input type="text" name="contact" required value="<?php echo $edit['person_contact'] ?? ''; ?>">

DOB:
<input type="date" name="dob" required value="<?php echo $edit['person_dob'] ?? ''; ?>">

Gender:
<select name="gender" required>
<option value="Male" <?php if(($edit['person_gender'] ?? '')=='Male') echo 'selected'; ?>>Male</option>
<option value="Female" <?php if(($edit['person_gender'] ?? '')=='Female') echo 'selected'; ?>>Female</option>
</select>

Specialization:
<input type="text" name="specialization" required value="<?php echo $edit['trainer_specialization'] ?? ''; ?>">

Cert Level:
<input type="text" name="cert" required value="<?php echo $edit['trainer_cert_lvl'] ?? ''; ?>">

<?php if ($edit_id) { ?>
    <input type="submit" name="update_trainer" value="Update Trainer">
    <a href="trainer_management.php">Cancel</a>
<?php } else { ?>
    <input type="submit" name="add_trainer" value="Add Trainer">
<?php } ?>
</form>
</div>

<!-- ================= TRAINER LIST ================= -->
<div class="card">
<h2>Trainer List</h2>
<table>
<tr>
<th>ID</th><th>Name</th><th>Contact</th><th>Specialization</th><th>Cert</th><th>Actions</th>
</tr>

<?php while($t = $trainers->fetch_assoc()) { ?>
<tr>
<td><?php echo $t['person_id']; ?></td>
<td><?php echo $t['person_name']; ?></td>
<td><?php echo $t['person_contact']; ?></td>
<td><?php echo $t['trainer_specialization']; ?></td>
<td><?php echo $t['trainer_cert_lvl']; ?></td>
<td>
<a href="?edit=<?php echo $t['person_id']; ?>">Edit</a> |
<a href="?delete=<?php echo $t['person_id']; ?>" onclick="return confirm('Delete trainer?')">Delete</a>
</td>
</tr>
<?php } ?>
</table>
</div>

<!-- ================= PERFORMANCE REPORT ================= -->
<div class="card">
<h2>Trainer Performance Report</h2>
<table>
<tr>
<th>Trainer Name</th>
<th>Total Classes Taught</th>
<th>Total Missed Classes</th>
</tr>

<?php while($p = $performance->fetch_assoc()) { ?>
<tr>
<td><?php echo $p['trainer_name']; ?></td>
<td><?php echo $p['total_classes_taught']; ?></td>
<td><?php echo $p['total_missed_classes'] ?? 0; ?></td>
</tr>
<?php } ?>
</table>
</div>

<?php $conn->close(); ?>

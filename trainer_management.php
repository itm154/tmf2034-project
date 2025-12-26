<?php
include 'db_connect.php';
include 'navbar.php';
?>

<?php
// ---- ADD TRAINER ----
if(isset($_POST['add_trainer'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $conn->query("INSERT INTO trainers (name, email) VALUES ('$name', '$email')");
}

// ---- DELETE TRAINER ----
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $conn->query("DELETE FROM trainers WHERE id=$id");
}

// ---- UPDATE TRAINERS ----
$edit_id = $edit_name = $edit_email = "";

if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $edit_result = $conn->query("SELECT * FROM trainers WHERE id=$edit_id");
    $edit_row = $edit_result->fetch_assoc();
    $edit_name = $edit_row['name'];
    $edit_email = $edit_row['email'];
}

// ---- UPDATE TRAINER ----
if (isset($_POST['update_trainer'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $conn->query("UPDATE trainers SET name='$name', email='$email' WHERE id=$id");
}

// ---- FETCH ALL TRAINERS ----
$result = $conn->query("SELECT * FROM trainers");

// ---- TRAINER PERFORMANCE REPORT ----
$performance = $conn->query("
    SELECT 
        t.name AS trainer_name,
        COUNT(c.class_id) AS total_classes,
        SUM(CASE WHEN c.status = 'cancelled' THEN 1 ELSE 0 END) AS cancelled_classes
    FROM trainers t
    LEFT JOIN classes c ON t.id = c.trainer_id
    GROUP BY t.id
");
?>

<h1>Trainer management</h1>

<!-- Add Trainer Form -->
<div class="card">
    <h2>Add / Update Trainer</h2>

    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $edit_id; ?>">

        Name:
        <input type="text" name="name" required value="<?php echo $edit_name; ?>">

        Email:
        <input type="email" name="email" required value="<?php echo $edit_email; ?>">

        <?php if ($edit_id) { ?>
            <input type="submit" name="update_trainer" value="Update Trainer">
            <a href="trainer_management.php">Cancel</a>
        <?php } else { ?>
            <input type="submit" name="add_trainer" value="Add Trainer">
        <?php } ?>
    </form>
</div>


<!-- Trainer List -->
<div class="card">
    <h2>Trainer List</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>

        <?php while($row = $result->fetch_assoc()){ ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td>
                <a href="trainer_management.php?edit=<?php echo $row['id']; ?>">Edit</a> |
                <a href="trainer_management.php?delete=<?php echo $row['id']; ?>"
                   onclick="return confirm('Delete this trainer?')">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>


<!-- Trainer Performance Report -->
<div class="card">
    <h2>Trainer Performance Report</h2>

    <table>
        <tr>
            <th>Trainer Name</th>
            <th>Total Classes Taught</th>
            <th>Total Missed / Cancelled</th>
        </tr>

        <?php while ($p = $performance->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $p['trainer_name']; ?></td>
            <td><?php echo $p['total_classes']; ?></td>
            <td><?php echo $p['cancelled_classes'] ?? 0; ?></td>
        </tr>
        <?php } ?>
    </table>
</div>

<?php $conn->close(); ?>

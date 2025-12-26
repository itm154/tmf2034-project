<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connect.php';
include 'navbar.php';

// ---- ADD TRAINER ----
if(isset($_POST['add_trainer'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $sql = "INSERT INTO trainers (name, email) VALUES ('$name', '$email')";
    $conn->query($sql);
}

// ---- DELETE TRAINER ----
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $conn->query("DELETE FROM trainers WHERE id=$id");
}

// ---- FETCH TRAINERS ----
$result = $conn->query("SELECT * FROM trainers");
?>

<h1>Trainer management</h1>

<!-- Add Trainer Form -->
<form method="POST">
    Name: <input type="text" name="name" required>
    Email: <input type="email" name="email" required>
    <input type="submit" name="add_trainer" value="Add Trainer">
</form>

<!-- Trainer List Table -->
<table border="1">
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
            <a href="trainer_management.php?delete=<?php echo $row['id']; ?>">Delete</a>
            <!-- Later you can add Update link -->
        </td>
    </tr>
    <?php } ?>
</table>

<?php $conn->close(); ?>

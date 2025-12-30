<?php
include 'db_connect.php';
include 'navbar.php';
?>

<div class="container mt-4">
    <h1 class="display-4 mb-4">Class management</h1>

    <h2 class="mb-3">Add Class</h2>
    <form action="add_class.php" method="post">
        <div class="mb-3">
            <label for="trainer" class="form-label">Trainer:</label>
            <select id="trainer" name="trainer_id" class="form-select" required>
                <?php
                $trainers_query = file_get_contents('queries/class/trainers.sql');
                $trainers_result = $conn->query($trainers_query);
                while ($trainer = $trainers_result->fetch_assoc()):
                ?>
                    <option value="<?php echo $trainer['person_id']; ?>"><?php echo $trainer['person_name']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="program" class="form-label">Program:</label>
            <select id="program" name="program_id" class="form-select" required>
                <?php
                $programs_query = "SELECT program_id, program_name FROM Program ORDER BY program_name";
                $programs_result = $conn->query($programs_query);
                while ($program = $programs_result->fetch_assoc()):
                ?>
                    <option value="<?php echo $program['program_id']; ?>"><?php echo $program['program_name']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="datetime" class="form-label">Date and Time:</label>
            <input type="datetime-local" id="datetime" name="class_datetime" class="form-control" required>
        </div>

        <button type="submit" name="submit" class="btn btn-primary">Add Class</button>
    </form>

    <h2 class="mb-3">Class Schedule</h2>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Trainer</th>
                    <th>Program</th>
                    <th>Class Status</th>
                    <th>Date and Time</th>
                    <th colspan="2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $query = file_get_contents('queries/class/class_info.sql');
                $result = $conn->query($query);
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <tr>
                        <td>
                            <?php echo $row['class_id'] ?>
                        </td>
                        <td>
                            <?php echo $row['person_name'] ?>
                        </td>
                        <td>
                            <?php echo $row['program_name'] ?>
                        </td>
                        <td>
                            <?php echo $row['class_status'] ?>
                        </td>
                        <td>
                            <?php echo $row['class_datetime'] ?>
                        </td>
                        <td>
                            <a href="edit_class.php?class_id=<?php echo $row['class_id'] ?>" class="btn btn-info btn-sm">Update</a>
                        </td>
                        <td>
                            <a href="delete_class.php?class_id=<?php echo $row['class_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this class?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php $conn->close(); ?>

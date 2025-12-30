<?php
include 'db_connect.php';
include 'navbar.php';
$message="";

if (isset($_POST['submit'])) {

$name = trim($_POST['name']);
    $contact = trim($_POST['contact']);
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $amount = floatval($_POST['amount']);

    if ($name === "" || $dob === "" || $gender === "" || $amount <= 0) {
        $message = "Please fill in all required fields.";
    } else {
        $conn->begin_transaction();

        try {
            //Insert into Person
            $person_stmt = $conn->prepare(
                "INSERT INTO Person (person_name, person_contact, person_dob, person_gender)
                 VALUES (?, ?, ?, ?)"
            );
            if (!$person_stmt) throw new Exception("Prepare Person failed: " . $conn->error);

            $person_stmt->bind_param("ssss", $name, $contact, $dob, $gender);
            if (!$person_stmt->execute()) throw new Exception("Person insert failed: " . $person_stmt->error);

            $person_id = $conn->insert_id;
            $person_stmt->close();

            //Insert into Enrolment
            $enrol_stmt = $conn->prepare(
                "INSERT INTO Enrolment (person_id, enrolment_date, enrolment_status)
                 VALUES (?, CURDATE(), 'Pending')"
            );
            if (!$enrol_stmt) throw new Exception("Prepare Enrolment failed: " . $conn->error);

            $enrol_stmt->bind_param("i", $person_id);
            if (!$enrol_stmt->execute()) throw new Exception("Enrolment insert failed: " . $enrol_stmt->error);

            $enrolment_id = $conn->insert_id;
            $enrol_stmt->close();

            //Insert into Invoice
            $invoice_stmt = $conn->prepare(
                "INSERT INTO Invoice (enrolment_id, invoice_date, invoice_amount, invoice_status)
                 VALUES (?, CURDATE(), ?, 'Unpaid')"
            );
            if (!$invoice_stmt) throw new Exception("Prepare Invoice failed: " . $conn->error);

            $invoice_stmt->bind_param("id", $enrolment_id, $amount);
            if (!$invoice_stmt->execute()) throw new Exception("Invoice insert failed: " . $invoice_stmt->error);

            $invoice_stmt->close();

            $conn->commit();
            $message = "✅ Enrolment and invoice successfully created.";

        } catch (Exception $e) {
            $conn->rollback();
            $message = "❌ " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Enrolment</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 400px; margin: 40px auto; }
        input, button { width: 100%; padding: 8px; margin-top: 6px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Enrolment Form</h2>

    <?php if ($message): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Full Name *</label>
        <input type="text" name="name" required>

        <label>Contact</label>
        <input type="text" name="contact">

        <label>Email *</label>
        <input type="email" name="email" required>

        <label>Invoice Amount (RM) *</label>
        <input type="number" step="0.01" name="amount" required>

        <button type="submit" name="submit">Submit Enrolment</button>
    </form>
</div>

</body>
</html>

<?php $conn->close(); ?>
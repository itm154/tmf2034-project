<?php
include 'db_connect.php';

$message="";

if (isset($_POST['submit'])) {

    //get form data
    $name   = trim($_POST['name']);
    $contact = trim($_POST['contact']);
    $email  = trim($_POST['email']);
    $amount = floatval($_POST['amount']);

    if ($name === "" || $email === "" || $amount <= 0) {
        $message = "Please fill in all required fields.";
    } else {

        // use transaction (important for enrolment + invoice)
        $conn->begin_transaction();

        try {
            //Insert into Person
            $person_stmt = $conn->prepare(
                "INSERT INTO Person (person_name, person_contact, person_email)
                 VALUES (?, ?, ?)"
            );
            $person_stmt->bind_param("sss", $name, $contact, $email);

            if (!$person_stmt->execute()) {
                throw new Exception("Error adding person");
            }

            $person_id = $conn->insert_id;
            $person_stmt->close();

            //Insert into Enrolment
            $enrol_stmt = $conn->prepare(
                "INSERT INTO Enrolment (person_id, enrolment_date, enrolment_status)
                 VALUES (?, CURDATE(), 'Pending')"
            );
            $enrol_stmt->bind_param("i", $person_id);

            if (!$enrol_stmt->execute()) {
                throw new Exception("Error creating enrolment");
            }

            $enrolment_id = $conn->insert_id;
            $enrol_stmt->close();

            //Insert into Invoice
            $invoice_stmt = $conn->prepare(
                "INSERT INTO Invoice (enrolment_id, invoice_date, invoice_amount, invoice_status)
                 VALUES (?, CURDATE(), ?, 'Unpaid')"
            );
            $invoice_stmt->bind_param("id", $enrolment_id, $amount);

            if (!$invoice_stmt->execute()) {
                throw new Exception("Error creating invoice");
            }

            $invoice_stmt->close();

            // commit everything
            $conn->commit();

            $message = "Enrolment and invoice successfully created.";

        } catch (Exception $e) {
            $conn->rollback();
            $message = "Failed to create enrolment.";
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
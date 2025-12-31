<?php
include 'db_connect.php';
include 'navbar.php';
$message="";

$members = [];
$sqlMembers = "
    SELECT m.person_id, p.person_name
    FROM Member m
    JOIN Person p ON p.person_id = m.person_id
    ORDER BY p.person_name ASC
";

$resMembers = $conn->query($sqlMembers);
if ($resMembers) {
    while ($row = $resMembers->fetch_assoc()) {
        $members[] = $row;
    }
}

$programs = [];
$sqlPrograms = "
    SELECT program_id, program_name, program_fee
    FROM Program
    ORDER BY program_name ASC
";
$resPrograms = $conn->query($sqlPrograms);
if ($resPrograms) {
    while ($row = $resPrograms->fetch_assoc()) {
        $programs[] = $row;
    }
}

if (isset($_POST['submit'])) {

    $member_person_id = intval($_POST['member_person_id'] ?? 0);
    $program_id = intval($_POST['program_id'] ?? 0);
    $payment_method = trim($_POST['payment_method'] ?? "");

    if ($member_person_id <= 0 || $program_id <= 0 || $payment_method === "") {
        $message = "❌ Please select a Member, Program, and Payment Method.";
    } else {
        $stmtFee = $conn->prepare("SELECT program_fee FROM Program WHERE program_id = ?");
        $stmtFee->bind_param("i", $program_id);
        $stmtFee->execute();
        $feeRow = $stmtFee->get_result()->fetch_assoc();
        $stmtFee->close();

        if (!$feeRow) {
            $message = "❌ Program not found.";
        } else {

            $fee = (float)$feeRow['program_fee'];

            // Use transaction so enrolment + invoice succeed together
            $conn->begin_transaction();

            try {
                $stmtEnrol = $conn->prepare("
                    INSERT INTO Enrolment (enrolment_date, program_id, member_person_id)
                    VALUES (CURDATE(), ?, ?)
                ");
                $stmtEnrol->bind_param("ii", $program_id, $member_person_id);
                if (!$stmtEnrol->execute()) {
                    throw new Exception("Enrolment insert failed: " . $stmtEnrol->error);
                }
                $enrolment_id = $conn->insert_id;
                $stmtEnrol->close();

                $stmtInv = $conn->prepare("
                    INSERT INTO Invoice (invoice_date, invoice_amount, invoice_payment_method, enrolment_id)
                    VALUES (CURDATE(), ?, ?, ?)
                ");
                $stmtInv->bind_param("dsi", $fee, $payment_method, $enrolment_id);
                if (!$stmtInv->execute()) {
                    throw new Exception("Invoice insert failed: " . $stmtInv->error);
                }
                $stmtInv->close();

                $conn->commit();
                $message = "✅ Enrolment created and invoice generated.";

            } catch (Exception $e) {
                $conn->rollback();
                $message = "❌ " . $e->getMessage();
            }
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
        
        <label>Date of Birth *</label>
        <input type="date" name="dob" required>

        <label>Gender *</label>
        <select name="gender" required>
            <option value="">-- choose --</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>

        <label>Invoice Amount (RM) *</label>
        <input type="number" step="0.01" name="amount" required>

        <button type="submit" name="submit">Submit Enrolment</button>
    </form>
</div>

</body>
</html>

<?php $conn->close(); ?>
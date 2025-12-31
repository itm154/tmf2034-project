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
    <title>Signup / Enrol</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 450px; margin: 40px auto; }
        select, button { width: 100%; padding: 8px; margin-top: 6px; }
        .msg { padding: 10px; border: 1px solid #ccc; margin: 10px 0; }
    </style>
</head>
<body>

<div class="container">
    <h1>Signup / Enrol</h1>

    <?php if ($message): ?>
        <div class="msg"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="POST">

        <label>Member *</label>
        <select name="member_person_id" required>
            <option value="">-- Select Member --</option>
            <?php foreach ($members as $m): ?>
                <option value="<?php echo (int)$m['person_id']; ?>">
                    <?php echo htmlspecialchars($m['person_name']); ?> (ID: <?php echo (int)$m['person_id']; ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <label>Program *</label>
        <select name="program_id" required>
            <option value="">-- Select Program --</option>
            <?php foreach ($programs as $p): ?>
                <option value="<?php echo (int)$p['program_id']; ?>">
                    <?php echo htmlspecialchars($p['program_name']); ?>
                    (RM <?php echo number_format((float)$p['program_fee'], 2); ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <label>Payment Method *</label>
        <select name="payment_method" required>
            <option value="">-- Select Payment Method --</option>
            <option value="Cash">Cash</option>
            <option value="Card">Card</option>
            <option value="Online">Online</option>
        </select>

        <button type="submit" name="submit">Submit Enrolment</button>
    </form>
</div>

</body>
</html>

<?php $conn->close(); ?>
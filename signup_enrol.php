<?php
include 'db_connect.php';
include 'navbar.php';
?>
<h1>Signup/Enrol</h1>
<?php $conn->close(); ?>

$programs =[];
$sqlPrograms = "SELECT program_id, program_name, program_fee 
                FROM programs
                WHERE program_status = 'Active'
                ORDER BY program_name ASC";
$resPrograms = $conn->query($sqlPrograms);
if ($resPrograms){
    while ($row = $resPrograms->fetch_assoc()) $programs[] = $row;
}
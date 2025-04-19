<?php
require '../config.php';

$id = $_GET['id'];
$action = $_GET['action'];

if ($action === 'approve') {
    $conn->query("UPDATE withdrawals SET status = 'Completed' WHERE id = $id");
    echo "Withdrawal approved!";
} elseif ($action === 'reject') {
    $conn->query("UPDATE withdrawals SET status = 'Rejected' WHERE id = $id");
    echo "Withdrawal rejected!";
}

header("Location: withdrawals.php");
exit();
?>

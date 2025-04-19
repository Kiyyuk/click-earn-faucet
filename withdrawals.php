<?php
session_start();
require '../config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Get all withdrawal requests
$result = $conn->query("SELECT w.*, u.username FROM withdrawals w JOIN users u ON w.user_id = u.id WHERE status = 'Pending'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Withdrawals</title>
</head>
<body>
    <h1>Pending Withdrawals</h1>
    <table border="1">
        <tr>
            <th>User</th>
            <th>FaucetPay Address</th>
            <th>Amount (USD)</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['faucetpay_address']) ?></td>
                <td><?= number_format($row['amount'], 6) ?></td>
                <td>
                    <a href="process_withdrawal.php?id=<?= $row['id'] ?>&action=approve">Approve</a> |
                    <a href="process_withdrawal.php?id=<?= $row['id'] ?>&action=reject">Reject</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>

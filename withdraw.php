<?php
session_start();
require 'config.php';

$user_id = $_SESSION['user_id'];

// Get user's total points
$result = $conn->query("SELECT points FROM users WHERE id = $user_id");
$user = $result->fetch_assoc();
$points = $user['points'];

// Get admin-defined conversion rate
$settings_result = $conn->query("SELECT point_to_usd FROM settings");
$settings = $settings_result->fetch_assoc();
$conversion_rate = $settings['point_to_usd'] ?? 0.0001;
$usd_amount = $points * $conversion_rate;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $faucetpay_address = $_POST['faucetpay_address'];

    // Ensure user has at least 10 points
    if ($points < 10) {
        die("You need at least 10 points to withdraw.");
    }

    // Insert withdrawal request
    $stmt = $conn->prepare("INSERT INTO withdrawals (user_id, faucetpay_address, amount) VALUES (?, ?, ?)");
    $stmt->bind_param("isd", $user_id, $faucetpay_address, $usd_amount);
    
    if ($stmt->execute()) {
        // Deduct points
        $conn->query("UPDATE users SET points = points - $points WHERE id = $user_id");
        echo "Withdrawal requested successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<h3>Withdraw Earnings</h3>
<p>You have <?= $points ?> points (<?= number_format($usd_amount, 6) ?> USD).</p>
<form method="POST">
    FaucetPay Address: <input type="text" name="faucetpay_address" required><br>
    <button type="submit">Withdraw</button>
</form>

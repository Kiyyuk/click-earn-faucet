<?php
session_start();
require '../config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Fetch settings
$result = $conn->query("SELECT * FROM settings LIMIT 1");
$settings = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Click & Earn</title>
</head>
<body>
    <h1>Admin Dashboard</h1>

    <h2>Site Settings</h2>
    <form method="POST" action="update_settings.php">
        Faucet Name: <input type="text" name="faucet_name" value="<?= $settings['faucet_name'] ?>"><br>
        Description: <textarea name="faucet_description"><?= $settings['faucet_description'] ?></textarea><br>
        reCAPTCHA Site Key: <input type="text" name="recaptcha_site_key" value="<?= $settings['recaptcha_site_key'] ?>"><br>
        reCAPTCHA Secret Key: <input type="text" name="recaptcha_secret_key" value="<?= $settings['recaptcha_secret_key'] ?>"><br>
        1 Point = <input type="number" step="0.0001" name="point_to_usd" value="<?= $settings['point_to_usd'] ?>"><br>
        Referral %: <input type="number" name="referral_percentage" value="<?= $settings['referral_percentage'] ?>"><br>
        <button type="submit">Update Settings</button>
    </form>

    <h2>Manage Ads</h2>
    <a href="ads.php">View Ads</a>

    <h2>Users & Withdrawals</h2>
    <a href="users.php">View Users</a> | <a href="withdrawals.php">Manage Withdrawals</a>

    <a href="logout.php">Logout</a>
</body>
</html>

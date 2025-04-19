<?php
session_start();
require 'config.php';

$user_id = $_SESSION['user_id'];

$result = $conn->query("SELECT referral_earnings FROM users WHERE id = $user_id");
$row = $result->fetch_assoc();

echo "<h3>Your Referral Earnings: " . number_format($row['referral_earnings'], 2) . " points</h3>";
?>

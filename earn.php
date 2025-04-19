<?php
session_start();
require 'config.php';

$user_id = $_SESSION['user_id'];
$earned_points = 5; // Example points earned from ad click

// Update user's points
$conn->query("UPDATE users SET points = points + $earned_points WHERE id = $user_id");

// Check if user was referred
$result = $conn->query("SELECT referred_by FROM users WHERE id = $user_id");
$row = $result->fetch_assoc();
if ($row['referred_by']) {
    $referrer_code = $row['referred_by'];

    // Get referrer ID
    $referrer_result = $conn->query("SELECT id FROM users WHERE referral_code = '$referrer_code'");
    if ($referrer_row = $referrer_result->fetch_assoc()) {
        $referrer_id = $referrer_row['id'];

        // Get referral percentage
        $settings_result = $conn->query("SELECT referral_percentage FROM settings");
        $settings = $settings_result->fetch_assoc();
        $commission = ($earned_points * $settings['referral_percentage']) / 100;

        // Update referrer earnings
        $conn->query("UPDATE users SET referral_earnings = referral_earnings + $commission WHERE id = $referrer_id");
    }
}

echo "Earnings credited!";
?>

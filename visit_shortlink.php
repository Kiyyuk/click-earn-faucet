<?php
session_start();
require 'config.php';

$user_id = $_SESSION['user_id'];
$shortlink_id = $_GET['id'];

// Check if user has clicked this shortlink today
$check = $conn->prepare("SELECT COUNT(*) FROM shortlink_clicks WHERE user_id = ? AND shortlink_id = ? AND DATE(clicked_at) = CURDATE()");
$check->bind_param("ii", $user_id, $shortlink_id);
$check->execute();
$clicked_today = $check->get_result()->fetch_row()[0];

if ($clicked_today > 0) {
    die("You have already completed this shortlink today.");
}

// Get shortlink details
$shortlink_result = $conn->prepare("SELECT url, earn_points FROM shortlinks WHERE id = ?");
$shortlink_result->bind_param("i", $shortlink_id);
$shortlink_result->execute();
$shortlink = $shortlink_result->get_result()->fetch_assoc();

if ($shortlink) {
    // Insert click record
    $stmt = $conn->prepare("INSERT INTO shortlink_clicks (user_id, shortlink_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $shortlink_id);
    $stmt->execute();

    // Reward user
    $conn->query("UPDATE users SET points = points + " . $shortlink['earn_points'] . " WHERE id = $user_id");

    // Redirect to shortlink
    header("Location: " . $shortlink['url']);
    exit();
} else {
    die("Invalid shortlink.");
}
?>

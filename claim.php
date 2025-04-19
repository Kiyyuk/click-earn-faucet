<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $ad_id = $_POST['ad_id'];

    // Check if the user has already claimed today
    $check = $conn->prepare("SELECT COUNT(*) FROM ad_clicks WHERE user_id = ? AND ad_id = ? AND DATE(clicked_at) = CURDATE()");
    $check->bind_param("ii", $user_id, $ad_id);
    $check->execute();
    $clicked_today = $check->get_result()->fetch_row()[0];

    if ($clicked_today > 0) {
        die("You have already clicked this ad today.");
    }

    // reCAPTCHA validation
    $recaptcha_secret = "YOUR_RECAPTCHA_SECRET_KEY";
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=" . $_POST['g-recaptcha-response']);
    $responseKeys = json_decode($response, true);
    if (!$responseKeys["success"]) {
        die("reCAPTCHA verification failed!");
    }

    // Reward user with points
    $earn_points = 10; // Example points per ad
    $conn->query("UPDATE users SET points = points + $earn_points WHERE id = $user_id");

    // Log the ad click
    $stmt = $conn->prepare("INSERT INTO ad_clicks (user_id, ad_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $ad_id);
    $stmt->execute();

    echo "Points claimed successfully!";
}
?>

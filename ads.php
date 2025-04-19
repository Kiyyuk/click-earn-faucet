<?php
session_start();
require 'config.php';

$user_id = $_SESSION['user_id'];

// Check if the user has watched all ads for today
$check_today = $conn->prepare("SELECT COUNT(*) FROM ad_clicks WHERE user_id = ? AND DATE(clicked_at) = CURDATE()");
$check_today->bind_param("i", $user_id);
$check_today->execute();
$click_count = $check_today->get_result()->fetch_row()[0];

if ($click_count >= 1) { // Limit to 1 ad per day
    echo "<p>All ads watched! Try again tomorrow.</p>";
    exit();
}

// Fetch a random active ad
$result = $conn->query("SELECT * FROM ads WHERE status = 'active' ORDER BY RAND() LIMIT 1");
$ad = $result->fetch_assoc();

if ($ad) {
    echo "<div id='ad-section'>";
    echo "<iframe src='" . $ad['adcode'] . "' width='100%' height='400'></iframe>";
    echo "<p>Stay on this page for <span id='timer'>20</span> seconds to earn points.</p>";
    echo "<form id='claim-form' method='POST' action='claim.php' style='display:none;'>
            <input type='hidden' name='ad_id' value='" . $ad['id'] . "'>
            <div class='g-recaptcha' data-sitekey='YOUR_RECAPTCHA_SITE_KEY'></div>
            <button type='submit'>Claim Points</button>
          </form>";
    echo "</div>";
} else {
    echo "<p>No ads available.</p>";
}
?>

<script>
let timeLeft = 20;
const timerElement = document.getElementById("timer");

const timerInterval = setInterval(() => {
    timeLeft--;
    timerElement.textContent = timeLeft;
    if (timeLeft === 0) {
        clearInterval(timerInterval);
        document.getElementById("claim-form").style.display = "block";
    }
}, 1000);
</script>

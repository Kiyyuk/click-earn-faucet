<?php
require '../config.php';

$adcode = $_POST['adcode'];
$country = $_POST['country'] ?? null;

$stmt = $conn->prepare("INSERT INTO ads (adcode, country) VALUES (?, ?)");
$stmt->bind_param("ss", $adcode, $country);

if ($stmt->execute()) {
    echo "Ad added successfully! <a href='ads.php'>Back</a>";
} else {
    echo "Error: " . $conn->error;
}
?>

<?php
require '../config.php';

$id = $_GET['id'];

if ($conn->query("DELETE FROM ads WHERE id = $id")) {
    echo "Ad deleted! <a href='ads.php'>Back</a>";
} else {
    echo "Error deleting ad: " . $conn->error;
}
?>

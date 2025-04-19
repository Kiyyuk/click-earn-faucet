<?php
session_start();
require 'config.php';

$user_id = $_SESSION['user_id'];

// Get available shortlinks
$result = $conn->query("SELECT * FROM shortlinks WHERE status = 'active'");
?>

<h1>Earn from Shortlinks</h1>
<table border="1">
    <tr>
        <th>Name</th>
        <th>Points</th>
        <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= $row['earn_points'] ?></td>
            <td>
                <a href="visit_shortlink.php?id=<?= $row['id'] ?>" target="_blank">Visit</a>
            </td>
        </tr>
    <?php } ?>
</table>

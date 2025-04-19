<?php
session_start();
require '../config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Fetch ads
$result = $conn->query("SELECT * FROM ads");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Ads - Click & Earn</title>
</head>
<body>
    <h1>Manage Ads</h1>
    <a href="index.php">Back to Dashboard</a>

    <h2>Add New Ad</h2>
    <form method="POST" action="add_ad.php">
        Ad Code: <textarea name="adcode" required></textarea><br>
        Country (optional): <input type="text" name="country"><br>
        <button type="submit">Add Ad</button>
    </form>

    <h2>Existing Ads</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Ad Code</th>
            <th>Country</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['adcode']) ?></td>
                <td><?= $row['country'] ?? 'All' ?></td>
                <td>
                    <a href="delete_ad.php?id=<?= $row['id'] ?>">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>

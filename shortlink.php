<?php
session_start();
require '../config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Handle new shortlink submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $url = $_POST['url'];
    $earn_points = $_POST['earn_points'];

    $stmt = $conn->prepare("INSERT INTO shortlinks (name, url, earn_points) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $name, $url, $earn_points);
    
    if ($stmt->execute()) {
        echo "Shortlink added successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Get all shortlinks
$result = $conn->query("SELECT * FROM shortlinks");
?>

<h1>Manage Shortlinks</h1>
<form method="POST">
    Name: <input type="text" name="name" required><br>
    URL: <input type="text" name="url" required><br>
    Earn Points: <input type="number" name="earn_points" required><br>
    <button type="submit">Add Shortlink</button>
</form>

<table border="1">
    <tr>
        <th>Name</th>
        <th>URL</th>
        <th>Points</th>
        <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['url']) ?></td>
            <td><?= $row['earn_points'] ?></td>
            <td>
                <a href="delete_shortlink.php?id=<?= $row['id'] ?>">Delete</a>
            </td>
        </tr>
    <?php } ?>
</table>

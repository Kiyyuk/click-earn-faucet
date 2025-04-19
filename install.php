<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db_host = $_POST['db_host'];
    $db_user = $_POST['db_user'];
    $db_pass = $_POST['db_pass'];
    $db_name = $_POST['db_name'];
    $admin_user = $_POST['admin_user'];
    $admin_pass = password_hash($_POST['admin_pass'], PASSWORD_BCRYPT);

    // Connect to MySQL
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    if ($conn->connect_error) {
        die("Database Connection Failed: " . $conn->connect_error);
    }

    // Create Users Table
    $conn->query("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(255) UNIQUE,
        points INT DEFAULT 0,
        referrer_id INT DEFAULT NULL,
        FOREIGN KEY (referrer_id) REFERENCES users(id)
    )");

    // Create Ads Table
    $conn->query("CREATE TABLE IF NOT EXISTS ads (
        id INT AUTO_INCREMENT PRIMARY KEY,
        adcode TEXT NOT NULL,
        status ENUM('active', 'inactive') DEFAULT 'active'
    )");

    // Create Ad Clicks Table
    $conn->query("CREATE TABLE IF NOT EXISTS ad_clicks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        ad_id INT NOT NULL,
        clicked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (ad_id) REFERENCES ads(id)
    )");

    // Create Withdrawals Table
    $conn->query("CREATE TABLE IF NOT EXISTS withdrawals (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        faucetpay_address VARCHAR(255) NOT NULL,
        amount DECIMAL(10,6) NOT NULL,
        status ENUM('Pending', 'Completed', 'Rejected') DEFAULT 'Pending',
        requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )");

    // Create Shortlinks Table
    $conn->query("CREATE TABLE IF NOT EXISTS shortlinks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        url TEXT NOT NULL,
        earn_points INT NOT NULL,
        status ENUM('active', 'inactive') DEFAULT 'active'
    )");

    // Create Shortlink Clicks Table
    $conn->query("CREATE TABLE IF NOT EXISTS shortlink_clicks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        shortlink_id INT NOT NULL,
        clicked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (shortlink_id) REFERENCES shortlinks(id)
    )");

    // Create Settings Table
    $conn->query("CREATE TABLE IF NOT EXISTS settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        faucet_name VARCHAR(255) DEFAULT 'My Faucet',
        faucet_desc TEXT DEFAULT 'Earn free crypto by watching ads!',
        recaptcha_sitekey VARCHAR(255),
        recaptcha_secret VARCHAR(255),
        point_to_usd DECIMAL(10,6) DEFAULT 0.0001,
        referral_percentage INT DEFAULT 10
    )");

    // Insert Default Admin
    $conn->query("INSERT INTO users (username, password) VALUES ('$admin_user', '$admin_pass')");

    echo "Installation Successful! Delete install.php for security.";
    exit();
}
?>

<h2>Install Click & Earn Script</h2>
<form method="POST">
    <h3>Database Details</h3>
    Host: <input type="text" name="db_host" required><br>
    Username: <input type="text" name="db_user" required><br>
    Password: <input type="password" name="db_pass"><br>
    Database Name: <input type="text" name="db_name" required><br>
    
    <h3>Admin Details</h3>
    Username: <input type="text" name="admin_user" required><br>
    Password: <input type="password" name="admin_pass" required><br>

    <button type="submit">Install</button>
</form>

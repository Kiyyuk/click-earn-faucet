<?php
session_start();
require_once "config.php"; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $referrer_code = isset($_POST["referrer"]) ? trim($_POST["referrer"]) : null;

    // Google reCAPTCHA Verification
    $recaptcha_response = $_POST["g-recaptcha-response"];
    $recaptcha_url = "https://www.google.com/recaptcha/api/siteverify";
    $recaptcha_data = [
        "secret" => $RECAPTCHA_SECRET_KEY,
        "response" => $recaptcha_response
    ];
    $recaptcha_options = [
        "http" => [
            "method" => "POST",
            "header" => "Content-Type: application/x-www-form-urlencoded",
            "content" => http_build_query($recaptcha_data)
        ]
    ];
    $recaptcha_context = stream_context_create(["http" => $recaptcha_options]);
    $recaptcha_verify = json_decode(file_get_contents($recaptcha_url, false, $recaptcha_context));

    if (!$recaptcha_verify->success) {
        die("reCAPTCHA verification failed. Please try again.");
    }

    // Validate Inputs
    if (empty($username) || empty($email) || empty($password)) {
        die("All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Check if username or email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        die("Username or Email already exists.");
    }
    $stmt->close();

    // Hash Password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Check for referral code
    $referrer_id = null;
    if (!empty($referrer_code)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $referrer_code);
        $stmt->execute();
        $stmt->bind_result($referrer_id);
        $stmt->fetch();
        $stmt->close();
    }

    // Insert New User
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, referrer_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $username, $email, $hashed_password, $referrer_id);
    if ($stmt->execute()) {
        echo "Registration successful! You can now <a href='login.php'>log in</a>.";
    } else {
        echo "Registration failed. Please try again.";
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <h2>Register</h2>
    <form action="register.php" method="POST">
        <label>Username:</label>
        <input type="text" name="username" required><br>

        <label>Email:</label>
        <input type="email" name="email" required><br>

        <label>Password:</label>
        <input type="password" name="password" required><br>

        <label>Referral Code (Optional):</label>
        <input type="text" name="referrer"><br>

        <div class="g-recaptcha" data-sitekey="<?= $RECAPTCHA_SITE_KEY ?>"></div><br>

        <button type="submit">Register</button>
    </form>
</body>
</html>

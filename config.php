<?php
// **Database Connection Settings**
$DB_HOST = "sql211.infinityfree.com";  // Change this if your database is hosted elsewhere
$DB_USER = "if0_38224720";  // Set your MySQL username
$DB_PASS = "difYN0i4NLC";  // Set your MySQL password
$DB_NAME = "if0_38224720_faucet";  // Set your MySQL database name

// **FaucetPay API Key (For Withdrawals)**
$FAUCETPAY_API_KEY = "fe59f433885dfe7ed77b51e446bb5127f1246a3d2c47bf53750ec7c12bf9019c"; // Add your FaucetPay API Key

// **reCAPTCHA Settings**
$RECAPTCHA_SITE_KEY = "6LcSkMoqAAAAAOGZ_Bnq5ScMcP8GKd_trR0dmH3f";  // Google reCAPTCHA Site Key
$RECAPTCHA_SECRET_KEY = "6LcSkMoqAAAAAM8jzmX5awla516dB_h4X1AIbVc5";  // Google reCAPTCHA Secret Key

// **Security Settings**
define("ENABLE_DEBUG", false);  // Change to "true" to show errors (for testing)
define("SITE_URL", "http://watchearn.ct.ws");  // Change to your website URL

// **Database Connection**
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// **Check Connection**
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// **Error Reporting (Enable in Debug Mode)**
if (ENABLE_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
?>

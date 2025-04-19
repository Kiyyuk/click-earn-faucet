<?php
require '../config.php';

$faucet_name = $_POST['faucet_name'];
$faucet_description = $_POST['faucet_description'];
$recaptcha_site_key = $_POST['recaptcha_site_key'];
$recaptcha_secret_key = $_POST['recaptcha_secret_key'];
$point_to_usd = $_POST['point_to_usd'];
$referral_percentage = $_POST['referral_percentage'];

$sql = "UPDATE settings SET 
        faucet_name='$faucet_name', 
        faucet_description='$faucet_description',
        recaptcha_site_key='$recaptcha_site_key', 
        recaptcha_secret_key='$recaptcha_secret_key',
        point_to_usd='$point_to_usd', 
        referral_percentage='$referral_percentage' 
        WHERE id=1";

if ($conn->query($sql)) {
    echo "Settings updated!";
} else {
    echo "Error updating settings: " . $conn->error;
}
?>

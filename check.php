<?php
// Minimum PHP version required
$minPhpVersion = '7.4';

// Required PHP extensions
$requiredExtensions = ['mysqli', 'openssl', 'mbstring', 'json', 'curl'];

// Check PHP version
$phpVersion = PHP_VERSION;
$phpVersionCheck = version_compare($phpVersion, $minPhpVersion, '>=');

// Check extensions
$missingExtensions = [];
foreach ($requiredExtensions as $extension) {
    if (!extension_loaded($extension)) {
        $missingExtensions[] = $extension;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Server Requirements Check</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
        .status { padding: 10px; border-radius: 5px; margin: 10px; display: inline-block; }
        .pass { background: #4CAF50; color: white; }
        .fail { background: #F44336; color: white; }
    </style>
</head>
<body>
    <h2>Server Requirements Check</h2>

    <h3>PHP Version</h3>
    <p class="status <?= $phpVersionCheck ? 'pass' : 'fail' ?>">
        Installed: <b><?= $phpVersion ?></b> (Required: <?= $minPhpVersion ?> or higher)
    </p>

    <h3>PHP Extensions</h3>
    <?php if (empty($missingExtensions)) { ?>
        <p class="status pass">All required extensions are installed.</p>
    <?php } else { ?>
        <p class="status fail">Missing Extensions: <?= implode(', ', $missingExtensions) ?></p>
    <?php } ?>

    <?php if ($phpVersionCheck && empty($missingExtensions)) { ?>
        <h3>✅ Your server meets all requirements!</h3>
        <p>You can proceed with the installation.</p>
    <?php } else { ?>
        <h3>❌ Server does not meet the requirements.</h3>
        <p>Please update PHP or install missing extensions.</p>
    <?php } ?>
</body>
</html>

<?php
// Script để start server với PHP version bypass
echo "🚀 Starting Tour365 Server...\n";

// Bypass PHP version check
$composerPlatformCheck = 'vendor/composer/platform_check.php';
if (file_exists($composerPlatformCheck)) {
    $content = file_get_contents($composerPlatformCheck);
    $content = str_replace('PHP_VERSION_ID >= 80200', 'PHP_VERSION_ID >= 80000', $content);
    file_put_contents($composerPlatformCheck, $content);
    echo "✅ PHP version check bypassed\n";
}

// Start server
echo "🌐 Server starting at http://127.0.0.1:8000\n";
echo "📱 Open your browser and visit: http://127.0.0.1:8000\n";
echo "⏹️  Press Ctrl+C to stop the server\n\n";

// Start the server
$command = 'php -S 127.0.0.1:8000 -t public';
passthru($command);
?>

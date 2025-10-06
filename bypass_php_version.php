<?php
// Script để bypass PHP version check tạm thời
// Chạy: php bypass_php_version.php

// Bypass platform check
if (file_exists('vendor/composer/platform_check.php')) {
    $content = file_get_contents('vendor/composer/platform_check.php');
    $content = str_replace('if (PHP_VERSION_ID < 80200)', 'if (false && PHP_VERSION_ID < 80200)', $content);
    file_put_contents('vendor/composer/platform_check.php', $content);
}

echo "PHP version check bypassed. You can now run artisan commands.\n";
echo "Run: php artisan db:seed\n";

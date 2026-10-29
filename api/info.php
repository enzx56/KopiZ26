<?php

// Test direct serverless function without Laravel bootstrap
header('Content-Type: text/plain');
echo "=== VERCEL PHP RUNTIME OK ===\n";
echo "URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
foreach ($_SERVER as $k => $v) {
    if (str_starts_with($k, 'HTTP_') || str_starts_with($k, 'VERCEL_')) {
        echo "$k: $v\n";
    }
}

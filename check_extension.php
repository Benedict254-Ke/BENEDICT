<?php
header('Content-Type: text/plain');

echo "=== Checking MongoDB Extension ===\n\n";

// Method 1: Check if extension is loaded
if (extension_loaded('mongodb')) {
    echo "✅ MongoDB extension is loaded via extension_loaded()\n";
} else {
    echo "❌ MongoDB extension NOT loaded via extension_loaded()\n";
}

// Method 2: Check loaded extensions
echo "\nLoaded Extensions:\n";
$extensions = get_loaded_extensions();
$mongoFound = false;
foreach ($extensions as $ext) {
    if (strpos($ext, 'mongo') !== false) {
        echo "✅ $ext\n";
        $mongoFound = true;
    }
}
if (!$mongoFound) {
    echo "❌ No MongoDB-related extensions found\n";
}

// Method 3: Check if classes exist
echo "\nChecking MongoDB Classes:\n";
$classes = ['MongoDB\Client', 'MongoDB\Driver\Manager', 'MongoDB\BSON\UTCDateTime'];
foreach ($classes as $class) {
    if (class_exists($class)) {
        echo "✅ $class exists\n";
    } else {
        echo "❌ $class not found\n";
    }
}

// Method 4: Check php.ini files
echo "\nPHP Configuration:\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "php_ini_loaded_file: " . php_ini_loaded_file() . "\n";
echo "php_ini_scanned_files: " . php_ini_scanned_files() . "\n";
?>
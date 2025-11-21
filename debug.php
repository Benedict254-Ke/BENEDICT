<?php
header('Content-Type: text/plain');

echo "=== PHP Environment Check ===\n";
echo "PHP Version: " . PHP_VERSION . "\n\n";

// Check if MongoDB extension is loaded
if (extension_loaded('mongodb')) {
    echo "✅ MongoDB extension is loaded\n";
    
    // Test MongoDB classes
    if (class_exists('MongoDB\Client')) {
        echo "✅ MongoDB\\Client class exists\n";
    } else {
        echo "❌ MongoDB\\Client class not found\n";
    }
    
    if (class_exists('MongoDB\BSON\UTCDateTime')) {
        echo "✅ MongoDB\\BSON\\UTCDateTime class exists\n";
    } else {
        echo "❌ MongoDB\\BSON\\UTCDateTime class not found\n";
    }
} else {
    echo "❌ MongoDB extension NOT loaded\n";
}

// Check environment variables
echo "\nEnvironment Variables:\n";
echo "MONGODB_URI: " . (getenv('MONGODB_URI') ? 'Set' : 'Not set') . "\n";
echo "DB_NAME: " . (getenv('DB_NAME') ? getenv('DB_NAME') : 'Not set') . "\n";

// List loaded extensions
echo "\nLoaded Extensions:\n";
$extensions = get_loaded_extensions();
sort($extensions);
foreach ($extensions as $ext) {
    if (strpos($ext, 'mongo') !== false) {
        echo "- ✅ $ext\n";
    } else {
        echo "- $ext\n";
    }
}
?>
<?php
require_once '../vendor/autoload.php';
include_once '../config/db.php';
include_once '../objects/user.php';

header('Content-Type: text/plain; charset=utf-8');

try {
    $database = new Database();
    $db = $database->getConnection();
    
    echo "=== Setting up MongoDB Collections ===\n\n";
    
    // Check if collections exist, create if they don't
    $collections = ['users', 'messages', 'newsletter', 'projects'];
    $existingCollections = $db->listCollections();
    
    $existingNames = [];
    foreach ($existingCollections as $collection) {
        $existingNames[] = $collection->getName();
    }
    
    foreach ($collections as $collectionName) {
        if (!in_array($collectionName, $existingNames)) {
            $db->createCollection($collectionName);
            echo "✅ Created collection: $collectionName\n";
        } else {
            echo "⚠️  Collection already exists: $collectionName\n";
        }
    }
    
    echo "\n=== Setting up Admin User ===\n";
    
    $user = new User($db);
    
    // Check if admin user exists
    $usersCollection = $db->selectCollection('users');
    $adminExists = $usersCollection->findOne(['username' => 'admin']);
    
    if (!$adminExists) {
        $result = $user->createUser('admin', 'admin123');
        if ($result) {
            echo "✅ Admin user created successfully!\n";
            echo "   Username: admin\n";
            echo "   Password: admin123\n";
            echo "   ⚠️  IMPORTANT: Change this password after first login!\n";
        } else {
            echo "❌ Failed to create admin user\n";
        }
    } else {
        echo "⚠️  Admin user already exists\n";
    }
    
    echo "\n=== Setup Complete ===\n";
    echo "You can now access your admin dashboard at:\n";
    echo "https://benedict-2-54ex.onrender.com/admin/login.html\n";
    
} catch (Exception $e) {
    echo "❌ Error during setup: " . $e->getMessage() . "\n";
}
?>
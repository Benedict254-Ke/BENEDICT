<?php
require_once '../vendor/autoload.php';
include_once '../config/db.php';
include_once '../objects/user.php';

header('Content-Type: text/plain; charset=utf-8');

try {
    $database = new Database();
    $db = $database->getConnection();
    
    echo "=== Setting up MongoDB Collections ===\n\n";
    
    // Collections to verify/create
    $collections = ['users', 'messages', 'newsletter', 'projects'];
    
    foreach ($collections as $collectionName) {
        try {
            $collection = $db->selectCollection($collectionName);
            // Test by inserting and removing a document
            $result = $collection->insertOne(['setup_test' => true, 'timestamp' => new MongoDB\BSON\UTCDateTime()]);
            $collection->deleteOne(['_id' => $result->getInsertedId()]);
            echo "✅ Collection ready: $collectionName\n";
        } catch (Exception $e) {
            echo "❌ Error with collection $collectionName: " . $e->getMessage() . "\n";
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
            echo "   ⚠️  Change this password after login!\n";
        } else {
            echo "❌ Failed to create admin user\n";
        }
    } else {
        echo "⚠️  Admin user already exists\n";
    }
    
    echo "\n=== MongoDB Setup Complete ===\n";
    echo "All data will now be stored in MongoDB Atlas!\n";
    
} catch (Exception $e) {
    echo "❌ Setup Error: " . $e->getMessage() . "\n";
}
?>
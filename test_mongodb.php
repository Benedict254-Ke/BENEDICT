<?php
header('Content-Type: application/json');

try {
    // Test if MongoDB extension is loaded
    if (!extension_loaded('mongodb')) {
        throw new Exception('MongoDB extension not loaded');
    }
    
    // Test if MongoDB Client class exists
    if (!class_exists('MongoDB\Client')) {
        throw new Exception('MongoDB\Client class not found');
    }
    
    include_once 'config/db.php';
    $database = new Database();
    $db = $database->getConnection();
    
    // Test connection by listing collections
    $collections = $db->listCollections();
    $collectionNames = [];
    
    foreach ($collections as $collection) {
        $collectionNames[] = $collection->getName();
    }
    
    echo json_encode([
        "success" => true,
        "message" => "MongoDB connection successful!",
        "database" => "BENEDICT",
        "collections" => $collectionNames,
        "extension_loaded" => extension_loaded('mongodb')
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "MongoDB test failed: " . $e->getMessage(),
        "extension_loaded" => extension_loaded('mongodb')
    ]);
}
?>
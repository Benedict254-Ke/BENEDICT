<?php
require_once 'vendor/autoload.php';
include_once 'config/db.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Test by listing collections
    $collections = $db->listCollections();
    $collectionNames = [];
    
    foreach ($collections as $collection) {
        $collectionNames[] = $collection->getName();
    }
    
    echo json_encode([
        "success" => true,
        "message" => "MongoDB connected successfully!",
        "database" => "BENEDICT",
        "collections" => $collectionNames
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "MongoDB connection failed: " . $e->getMessage()
    ]);
}
?>
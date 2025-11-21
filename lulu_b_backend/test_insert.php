<?php
require_once 'vendor/autoload.php';
include_once 'config/db.php';
include_once 'objects/contact.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $contact = new Contact($db);
    $contact->name = "Test User";
    $contact->email = "test@example.com";
    $contact->message = "This is a test message";
    
    if ($contact->create()) {
        echo json_encode([
            "success" => true,
            "message" => "Test message inserted successfully!"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Failed to insert test message"
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $e->getMessage()
    ]);
}
?>
<?php
include_once '../config/db.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $db = $database->getConnection();
    $messagesCollection = $db->getCollection('messages');
    
    $messages = $messagesCollection->find([], [
        'sort' => ['timestamp' => -1]
    ])->toArray();
    
    echo json_encode($messages);
    
} catch (Exception $e) {
    echo json_encode([]);
}
?>
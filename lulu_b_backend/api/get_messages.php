<?php
include_once '../config/db.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $db = $database->getConnection();
    $messagesCollection = $db->getCollection('messages');
    
    $cursor = $messagesCollection->find([], [
        'sort' => ['timestamp' => -1]
    ]);
    
    // convert the cursor (Traversable) to an array
    $messages = iterator_to_array($cursor, false);
    
    echo json_encode($messages);
    
} catch (Exception $e) {
    echo json_encode([]);
}
?>
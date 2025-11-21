<?php
class Contact {
    private $conn;
    private $collection;
    
    public $name;
    public $email;
    public $subject;
    public $message;
    public $created_at;
    
    public function __construct($db) {
        $this->conn = $db;
        $this->collection = $db->getCollection('contacts');
    }
    
    public function create() {
        try {
            $document = [
                'name' => $this->name,
                'email' => $this->email,
                'subject' => $this->subject,
                'message' => $this->message,
                'created_at' => new MongoDB\BSON\UTCDateTime((int)(microtime(true) * 1000)),
                'read' => false
            ];
            
            $result = $this->collection->insertOne($document);
            return $result->getInsertedCount() === 1;
            
        } catch (Exception $e) {
            error_log("Error creating contact: " . $e->getMessage());
            return false;
        }
    }
    
    // Optional: Method to get all messages
    public function getAll() {
        try {
            $cursor = $this->collection->find([], [
                'sort' => ['created_at' => -1]
            ]);
            return $cursor->toArray();
        } catch (Exception $e) {
            error_log("Error reading contacts: " . $e->getMessage());
            return [];
        }
    }
}
?>
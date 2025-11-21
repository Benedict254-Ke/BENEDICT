<?php
use MongoDB\BSON\UTCDateTime;

class Contact {
    private $conn;
    private $collection;
    
    public $name;
    public $email;
    public $subject;
    public $message;
    
    public function __construct($db) {
        $this->conn = $db;
        $this->collection = $db->getCollection('messages'); // Store in 'messages' collection
    }
    
    public function create() {
        try {
            $document = [
                'name' => $this->name,
                'email' => $this->email,
                'subject' => $this->subject,
                'message' => $this->message,
                'created_at' => new MongoDB\BSON\UTCDateTime()
            ];
            
            $result = $this->collection->insertOne($document);
            return $result->getInsertedCount() === 1;
            
        } catch (Exception $e) {
            error_log("Error creating contact: " . $e->getMessage());
            return false;
        }
    }
}
?>
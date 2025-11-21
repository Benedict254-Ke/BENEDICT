<?php
require_once __DIR__ . '/../vendor/autoload.php';

class Database {
    /** @var \MongoDB\Client */
    private $client;
    private $database;
    
    public function __construct() {
        // Use environment variable or fallback
        $connectionString = getenv('MONGODB_URI') ?: "mongodb+srv://lulub8929_db_user:UKgnaUXAy6xVB6gS@cluster0.1ktaoog.mongodb.net/BENEDICT?retryWrites=true&w=majority";
        $databaseName = getenv('DB_NAME') ?: "BENEDICT";
        
        try {
            $this->client = new \MongoDB\Client($connectionString);
            $this->database = $this->client->selectDatabase($databaseName);
        } catch (\Exception $e) {
            throw new \Exception("MongoDB connection failed: " . $e->getMessage());
        }
    }
    
    public function getConnection() {
        return $this->database;
    }
    
    public function getCollection($collectionName = "contacts") {
        return $this->database->selectCollection($collectionName);
    }
}
?>
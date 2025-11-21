<?php
require_once __DIR__ . '/../vendor/autoload.php';

class Database {
    /** @var \MongoDB\Client */
    private $client;
    private $database;
    
    public function __construct() {
        // Use your MongoDB connection string
        $connectionString = getenv('MONGODB_URI') ?: "mongodb+srv://lulub8929_db_user:UKgnaUXAy6xVB6gS@cluster0.1ktaoog.mongodb.net";
        $databaseName = "BENEDICT"; // Your database name
        
        try {
            $this->client = new \MongoDB\Client($connectionString);
            $this->database = $this->client->selectDatabase($databaseName);
            echo "Connected to MongoDB successfully!";
        } catch (\Exception $e) {
            throw new \Exception("Database connection failed: " . $e->getMessage());
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
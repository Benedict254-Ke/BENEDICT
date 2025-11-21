<?php
class User {
    private $conn;
    private $collection;
    
    public function __construct($db) {
        $this->conn = $db;
        $this->collection = $db->getCollection('users');
    }
    
    public function authenticate($username, $password) {
        try {
            $user = $this->collection->findOne(['username' => $username]);
            
            if ($user && password_verify($password, $user['password'])) {
                return $user;
            }
            return false;
        } catch (Exception $e) {
            error_log("Authentication error: " . $e->getMessage());
            return false;
        }
    }
    
    public function createUser($username, $password) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $result = $this->collection->insertOne([
                'username' => $username,
                'password' => $hashedPassword
            ]);
            return $result;
        } catch (Exception $e) {
            error_log("User creation error: " . $e->getMessage());
            return false;
        }
    }
}
?>
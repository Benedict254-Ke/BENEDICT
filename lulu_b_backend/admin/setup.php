<?php
require_once '../vendor/autoload.php';
include_once '../config/db.php';
include_once '../objects/user.php';

header('Content-Type: text/plain');

try {
    $database = new Database();
    $db = $database->getConnection();
    $user = new User($db);

    // Create admin user
    $username = "admin";
    $password = "admin123"; // Change this in production
    
    $result = $user->createUser($username, $password);
    
    if ($result) {
        echo "Admin user created successfully!\n";
        echo "Username: admin\n";
        echo "Password: admin123\n";
        echo "Please change the password after first login!";
    } else {
        echo "Failed to create admin user. It might already exist.";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
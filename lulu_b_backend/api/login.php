<?php
session_start();
include_once '../config/db.php';
include_once '../objects/user.php';

header('Content-Type: text/plain');

try {
    $database = new Database();
    $db = $database->getConnection();
    $user = new User($db);

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo "empty_fields";
        exit;
    }

    $authenticatedUser = $user->authenticate($username, $password);
    
    if ($authenticatedUser) {
        $_SESSION['admin'] = $authenticatedUser['username'];
        $_SESSION['admin_id'] = $authenticatedUser['_id'];
        echo "success";
    } else {
        echo "invalid_credentials";
    }
    
} catch (Exception $e) {
    echo "error";
}
?>
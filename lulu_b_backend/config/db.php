<?php
$host = getenv("DB_HOST") ?: "localhost";
$user = getenv("DB_USER") ?: "root";
$pass = getenv("DB_PASS") ?: "";
$db   = getenv("DB_NAME") ?: "myapp";
$port = getenv("DB_PORT") ?: 3306;

$conn = new mysqli($host, $user, $pass, $db, (int)$port);

if ($conn->connect_error) {
    error_log("Database connection warning: " . $conn->connect_error);
    // Don't die - allow app to continue
    // die("Connection failed: " . $conn->connect_error);
}
?>
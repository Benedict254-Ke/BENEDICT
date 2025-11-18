<?php
header("Content-Type: application/json");
include "../config/db.php";

$sql = "SELECT * FROM messages ORDER BY id DESC";
$result = $conn->query($sql);

$messages = [];

while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
?>

<?php
header("Content-Type: application/json");
include "../config/db.php";

$result = $conn->query("SELECT * FROM newsletter ORDER BY id DESC");

$subs = [];

while ($row = $result->fetch_assoc()) {
    $subs[] = $row;
}

echo json_encode($subs);
?>

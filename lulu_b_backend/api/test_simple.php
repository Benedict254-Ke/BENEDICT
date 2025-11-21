<?php
// test_simple.php
header("Access-Control-Allow-Origin: https://benedict-personal-portifolio.vercel.app");
header('Content-Type: application/json');

echo json_encode([
    "success" => true,
    "message" => "Simple contact API is working!",
    "timestamp" => date('Y-m-d H:i:s')
]);
?>
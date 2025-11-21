<?php
$allowedOrigins = [
    'https://benedict-personal-portifolio.vercel.app',
    'http://localhost:3000'
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if (in_array($origin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: $origin");
}

header('Content-Type: application/json');
echo json_encode([
    "success" => true,
    "message" => "CORS test successful",
    "your_origin" => $origin,
    "allowed" => in_array($origin, $allowedOrigins)
]);
?>
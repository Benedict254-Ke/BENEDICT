<?php
// simple_newsletter.php - No database required
header("Access-Control-Allow-Origin: https://benedict-personal-portifolio.vercel.app");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        
        $rawInput = file_get_contents('php://input');
        $input = json_decode($rawInput, true);
        
        if (empty($input['email'])) {
            $response['message'] = "Email is required!";
            echo json_encode($response);
            exit;
        }

        $email = trim($input['email']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = "Invalid email format!";
            echo json_encode($response);
            exit;
        }

        // Save to newsletter file
        $data = [
            'email' => htmlspecialchars($email),
            'timestamp' => date('Y-m-d H:i:s'),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ];
        
        // Create messages directory if it doesn't exist
        if (!is_dir('../messages')) {
            mkdir('../messages', 0755, true);
        }
        
        $filename = '../messages/newsletter_' . date('Y-m-d_H-i-s') . '_' . uniqid() . '.json';
        file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
        
        $response['success'] = true;
        $response['message'] = "Successfully subscribed to newsletter! ✅";

    } else {
        $response['message'] = "Invalid request method.";
    }

} catch (Exception $e) {
    $response['message'] = "Server error: " . $e->getMessage();
}

echo json_encode($response);
?>
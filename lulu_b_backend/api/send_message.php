<?php
require_once '../vendor/autoload.php';
include_once '../config/db.php';
include_once '../objects/contact.php';

// ✅ Dynamic CORS Handling
$allowedOrigins = [
    'https://benedict-personal-portifolio.vercel.app',
    'http://localhost:3000',
    'http://localhost:5173' // Add if using Vite
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if (in_array($origin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: $origin");
} else {
    // Default to your Vercel domain if no origin match
    header("Access-Control-Allow-Origin: https://benedict-personal-portifolio.vercel.app");
}

header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 3600");

// ✅ Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// ✅ Set content type to JSON for consistent responses
header('Content-Type: application/json');

// ✅ Initialize response array
$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        // ✅ Check if content type is JSON
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        $input = [];

        if (strpos($contentType, 'application/json') !== false) {
            // Handle JSON input
            $input = json_decode(file_get_contents('php://input'), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON data');
            }
        } else {
            // Handle form data
            $input = $_POST;
        }

        // ✅ Validate required fields
        if (empty($input['name']) || empty($input['email']) || empty($input['subject']) || empty($input['message'])) {
            $response['message'] = "All fields are required!";
            echo json_encode($response);
            exit;
        }

        // ✅ Get and sanitize data
        $name = trim($input['name']);
        $email = trim($input['email']);
        $subject = trim($input['subject']);
        $message = trim($input['message']);

        // ✅ Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = "Invalid email format!";
            echo json_encode($response);
            exit;
        }

        // ✅ Initialize MongoDB connection
        $database = new Database();
        $db = $database->getConnection();
        $contact = new Contact($db);

        // ✅ Set contact data
        $contact->name = htmlspecialchars($name);
        $contact->email = htmlspecialchars($email);
        $contact->subject = htmlspecialchars($subject);
        $contact->message = htmlspecialchars($message);

        // ✅ Insert into MongoDB
        if ($contact->create()) {
            $response['success'] = true;
            $response['message'] = "Message sent successfully! ✅ I'll get back to you soon.";
        } else {
            $response['message'] = "Failed to send message. Please try again. ❌";
        }

    } else {
        $response['message'] = "Invalid request method.";
    }

} catch (Exception $e) {
    $response['message'] = "Server error: " . $e->getMessage();
    error_log("Contact form error: " . $e->getMessage());
}

// ✅ Return JSON response
echo json_encode($response);
?>
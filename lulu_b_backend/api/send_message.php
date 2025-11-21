<?php
require_once '../vendor/autoload.php';
include_once '../config/db.php';
include_once '../objects/contact.php';

// ✅ CORS Headers - Allow your Vercel frontend and local development
header("Access-Control-Allow-Origin: https://benedict-personal-portifolio.vercel.app");
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

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

        // ✅ Validate required fields
        if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['subject']) || empty($_POST['message'])) {
            $response['message'] = "All fields are required!";
            echo json_encode($response);
            exit;
        }

        // ✅ Get and sanitize data
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $subject = trim($_POST['subject']);
        $message = trim($_POST['message']);

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
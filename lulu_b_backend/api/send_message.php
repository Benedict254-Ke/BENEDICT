<?php
// ✅ CORS Headers - Allow your Vercel frontend and local development
header("Access-Control-Allow-Origin: https://your-vercel-app.vercel.app"); // Replace with your actual Vercel URL
header("Access-Control-Allow-Origin: http://localhost:3000"); // For local development
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

include "../config/db.php";

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
        $name = $conn->real_escape_string(trim($_POST['name']));
        $email = $conn->real_escape_string(trim($_POST['email']));
        $subject = $conn->real_escape_string(trim($_POST['subject']));
        $message = $conn->real_escape_string(trim($_POST['message']));

        // ✅ Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = "Invalid email format!";
            echo json_encode($response);
            exit;
        }

        // ✅ Insert into database
        $sql = "INSERT INTO messages (name, email, subject, message, created_at)
                VALUES ('$name', '$email', '$subject', '$message', NOW())";

        if ($conn->query($sql)) {
            $response['success'] = true;
            $response['message'] = "Message sent successfully! ✅ I'll get back to you soon.";
        } else {
            $response['message'] = "Failed to send message. Please try again. ❌";
            // Optional: Log the error for debugging
            // error_log("Database error: " . $conn->error);
        }
    } else {
        $response['message'] = "Invalid request method.";
    }

} catch (Exception $e) {
    $response['message'] = "Server error: " . $e->getMessage();
}

// ✅ Close database connection
$conn->close();

// ✅ Return JSON response
echo json_encode($response);
?>
<?php
// ✅ CORS Headers - Allow your Vercel frontend and local development
header("Access-Control-Allow-Origin: https://your-vercel-app.vercel.app"); // Replace with your actual Vercel URL
header("Access-Control-Allow-Origin: http://localhost:3000"); // For local development
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

// ✅ Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// ✅ Set content type
header('Content-Type: application/json');

include "../config/db.php";

// ✅ Initialize response array
$response = ['success' => false, 'message' => ''];

try {
    // ✅ Accept email from POST if available, otherwise from GET (for testing)
    $email = "";

    if (isset($_POST['email'])) {
        $email = trim($_POST['email']);
    } elseif (isset($_GET['email'])) {
        $email = trim($_GET['email']);
    }

    // ✅ Validate email
    if (empty($email)) {
        $response['message'] = "Email is required!";
        echo json_encode($response);
        exit;
    }

    // ✅ Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = "Invalid email format!";
        echo json_encode($response);
        exit;
    }

    // ✅ Escape email
    $email = $conn->real_escape_string($email);

    // ✅ Check if email already exists
    $check_sql = "SELECT id FROM newsletter WHERE email = '$email'";
    $result = $conn->query($check_sql);
    
    if ($result && $result->num_rows > 0) {
        $response['message'] = "You are already subscribed! ✅";
        echo json_encode($response);
        exit;
    }

    // ✅ Insert into DB
    $sql = "INSERT INTO newsletter (email, subscribed_at) VALUES ('$email', NOW())";

    if ($conn->query($sql)) {
        $response['success'] = true;
        $response['message'] = "Subscribed successfully! 🎉";
    } else {
        $response['message'] = "Database error: " . $conn->error;
    }

} catch (Exception $e) {
    $response['message'] = "Server error: " . $e->getMessage();
}

// ✅ Close connection and return response
$conn->close();
echo json_encode($response);
?>
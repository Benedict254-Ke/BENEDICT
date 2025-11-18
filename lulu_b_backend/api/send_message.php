<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: POST");

include "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $message = $conn->real_escape_string($_POST['message']);

    $sql = "INSERT INTO messages (name, email, subject, message)
            VALUES ('$name', '$email', '$subject', '$message')";

    if ($conn->query($sql)) {
        echo "Message sent successfully ✅";
    } else {
        echo "Failed to send message ❌";
    }
}
?>

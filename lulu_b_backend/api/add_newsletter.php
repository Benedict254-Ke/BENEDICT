<?php
header("Access-Control-Allow-Origin: *");
include "../config/db.php";

// ✅ Accept email from POST if available, otherwise from GET (for testing)
$email = "";

if (isset($_POST['email'])) {
    $email = $_POST['email'];
} elseif (isset($_GET['email'])) {
    $email = $_GET['email'];
}

// ✅ If still empty → stop
if (trim($email) == "") {
    echo "Email required!";
    exit;
}

// ✅ Escape email
$email = $conn->real_escape_string($email);

// ✅ Insert into DB
$sql = "INSERT INTO newsletter (email) VALUES ('$email')";

if ($conn->query($sql)) {
    echo "Subscribed successfully ✅";
} else {
    echo "You are already subscribed ❗";
}
?>

<?php
session_start();
include "../config/db.php";

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM admin_users WHERE username = '$username'";
$res = $conn->query($sql);

if ($res->num_rows == 1) {
    $row = $res->fetch_assoc();

    if (hash('sha256', $password) === $row['password']) {
        $_SESSION['admin'] = $row['id'];
        echo "success";
    } else {
        echo "invalid_password";
    }
} else {
    echo "invalid_user";
}
?>

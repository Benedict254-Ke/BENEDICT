<?php
include "../config/db.php";
include "send_to_subscribers.php";   // ✅ ADD THIS LINE

$title = $_POST['title'];
$description = $_POST['description'];
$demo_link = $_POST['demo_link'];
$github_link = $_POST['github_link'];

$img_name = str_replace(" ", "_", $_FILES['image']['name']);
$img_tmp = $_FILES['image']['tmp_name'];

$path = "../uploads/projects/" . $img_name;
move_uploaded_file($img_tmp, $path);

$sql = "INSERT INTO projects (title, description, image, demo_link, github_link)
        VALUES ('$title', '$description', '$img_name', '$demo_link', '$github_link')";

if ($conn->query($sql)) {

    // ✅ SEND EMAIL TO SUBSCRIBERS WHEN NEW PROJECT IS ADDED
    notifySubscribers($title, $description, $demo_link, $github_link);

    echo "success";
} else {
    echo "error";
}
?>

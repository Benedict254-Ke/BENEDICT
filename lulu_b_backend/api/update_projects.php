<?php
include "../config/db.php";

$id = $_POST['id'];
$title = $_POST['title'];
$description = $_POST['description'];
$demo_link = $_POST['demo_link'];
$github_link = $_POST['github_link'];

$sql = "UPDATE projects
        SET title='$title', description='$description',
            demo_link='$demo_link', github_link='$github_link'
        WHERE id='$id'";

if ($conn->query($sql)) {
    echo "success";
} else {
    echo "error";
}
?>

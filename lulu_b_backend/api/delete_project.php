<?php
include "../config/db.php";

if (!isset($_POST['id'])) {
    echo "no_id";
    exit;
}

$id = intval($_POST['id']);

// ✅ Get image name before deleting
$imgQuery = $conn->query("SELECT image FROM projects WHERE id = $id");
$imgRow = $imgQuery->fetch_assoc();
$imageFile = $imgRow ? $imgRow['image'] : "";

// ✅ Delete project from DB
$sql = "DELETE FROM projects WHERE id = $id";
if ($conn->query($sql)) {

    // ✅ Delete image file
    if (!empty($imageFile)) {
        $path = "../uploads/projects/" . $imageFile;
        if (file_exists($path)) {
            unlink($path);
        }
    }

    echo "success";
} else {
    echo "error";
}
?>

<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.html");
    exit;
}

include "../config/db.php";
?>
<!doctype html>
<html>
<head>
<title>Newsletter Subscribers</title>

<style>
body { background:#0b1220; color:white; font-family:Poppins; }
table { width:100%; border-collapse:collapse; margin-top:20px; }
th, td { padding:10px; border-bottom:1px solid #333; }
h2 { text-align:center; }
</style>

</head>
<body>

<h2>Newsletter Subscribers</h2>

<table>
    <tr>
        <th>Email</th>
        <th>Subscribed At</th>
    </tr>

    <?php
    $result = $conn->query("SELECT * FROM newsletter ORDER BY id DESC");

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['email']}</td>
                <td>{$row['subscribed_at']}</td>
              </tr>";
    }
    ?>
</table>

</body>
</html>

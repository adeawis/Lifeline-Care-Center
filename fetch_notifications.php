<?php
include 'db.php';

$result = $conn->query("SELECT * FROM notifications ORDER BY created_at DESC LIMIT 4");
$notifications = [];
if (!$result) {
    die("Query Error: " . $conn->error);
}

while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

echo json_encode($notifications);
?>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lifeline_carecenter";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['schedule_id'])) {
    $scheduleId = $_POST['schedule_id'];

    if (empty($scheduleId)) {
        die("Invalid schedule ID.");
    }

    $stmt = $conn->prepare("DELETE FROM schedule_admin WHERE schedule_id = ?");
    $stmt->bind_param("s", $scheduleId);

    if ($stmt->execute()) {
        echo "success"; // Return success message
    } else {
        echo "Error deleting schedule: " . $conn->error;
    }

    $stmt->close();
} else {
    die("No schedule ID provided.");
}

$conn->close();
?>


<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lifeline_carecenter";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$checkBookingQuery = "SELECT id FROM booking WHERE id = ?";
$stmt = $conn->prepare($checkBookingQuery);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Error: The provided booking ID does not exist in the booking table.");
}

// Get form data
$booking_id = $_POST['booking_id'];
$caregiver_id = $_POST['caregiver_id'];
$task = $_POST['task'];
$schedule_date = $_POST['schedule_date'];

// Insert schedule into the database
$scheduleQuery = "INSERT INTO schedule (booking_id, caregiver_id, task, schedule_date) 
VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($scheduleQuery);
$stmt->bind_param("iiss", $booking_id, $caregiver_id, $task, $schedule_date);

if ($stmt->execute()) {
    echo "Schedule saved successfully!";
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>

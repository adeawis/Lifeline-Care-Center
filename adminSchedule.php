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

// Fetch bookings
$bookingQuery = "SELECT 
    first_name, 
    last_name, 
    start_date, 
    end_date, 
    service_type 
FROM booking";
$bookingResult = $conn->query($bookingQuery);

// Fetch caregivers
$staffQuery = "SELECT 
    full_name, 
    position, 
    availability 
FROM staffreg";
$staffResult = $conn->query($staffQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Task</title>
</head>
<body>
    <h1>Schedule Task</h1>
    <form method="POST" action="save_schedule.php">
        <h2>Booking Details</h2>
        <label for="booking">Select Booking:</label>
        <select id="booking" name="booking_id" required>
    <?php while ($row = $bookingResult->fetch_assoc()): ?>
        <option value="<?php echo htmlspecialchars($row['id']); ?>">
            <?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name'] . ' (' . $row['service_type'] . ') - ' . $row['start_date'] . ' to ' . $row['end_date']); ?>
        </option>
    <?php endwhile; ?>
</select>


        <h2>Caregiver Details</h2>
        <label for="caregiver">Select Caregiver:</label>
        <select id="caregiver" name="caregiver_id" required>
            <?php while ($row = $staffResult->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($row['full_name']); ?>">
                    <?php echo htmlspecialchars($row['full_name'] . ' (' . $row['position'] . ') - ' . $row['availability']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <h2>Task Details</h2>
        <label for="task">Task:</label>
        <input type="text" id="task" name="task" required>

        <label for="schedule_date">Schedule Date:</label>
        <input type="date" id="schedule_date" name="schedule_date" required>

        <button type="submit">Save Schedule</button>
    </form>
</body>
</html>

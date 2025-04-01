<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the selected booking and staff details
    $bookingInfo = explode("_", $_POST['booking']);
    $clientFirstName = $bookingInfo[0];
    $clientLastName = $bookingInfo[1];
    $staffMember = $_POST['staff'];

    // Fetch the booking details
    $bookingQuery = "SELECT * FROM booking WHERE first_name = ? AND last_name = ?";
    $stmt = $conn->prepare($bookingQuery);
    $stmt->bind_param("ss", $clientFirstName, $clientLastName);
    $stmt->execute();
    $bookingResult = $stmt->get_result();
    $bookingData = $bookingResult->fetch_assoc();

    // Insert into schedule table
    $insertQuery = "INSERT INTO schedule_admin (client_name, staff_name, service_type, start_date, end_date, status) 
                   VALUES (?, ?, ?, ?, ?, 'Scheduled')";
    $stmt = $conn->prepare($insertQuery);
    $clientName = $clientFirstName;
    $stmt->bind_param("sssss", 
        $clientName,
        $staffMember,
        $bookingData['service_type'],
        $bookingData['start_date'],
        $bookingData['end_date']
    );

    if ($stmt->execute()) {
        // Update staff availability
        $updateStaffQuery = "UPDATE staffreg SET availability = 'unavailable' WHERE full_name = ?";
        $stmt = $conn->prepare($updateStaffQuery);
        $stmt->bind_param("s", $staffMember);
        $stmt->execute();

        echo "<script>
            alert('Schedule created successfully!');
            window.location.href = 'schedulingAdmin.php?success=1';
        </script>";
    } else {
        echo "<script>
        alert('Unable to create schedule. Please try again.');
        window.location.href = 'schedulingAdmin.php?error=1';
        </script>";
    }
}

$conn->close();
?>
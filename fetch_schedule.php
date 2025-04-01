<?php
session_start();
include 'db.php';

if (isset($_SESSION['full_name'])) {
    $email = $_SESSION['full_name'];

    // Fetch the staff member's full name based on the email
    $query = "SELECT full_name FROM staffreg WHERE full_name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $staff = $result->fetch_assoc();

    if ($staff) {
        $full_name = $staff['full_name'];
        
        // Fetch the schedules for the staff member
        $schedule_query = "SELECT * FROM schedule_admin WHERE staff_name = ?";
        $schedule_stmt = $conn->prepare($schedule_query);
        $schedule_stmt->bind_param("s", $full_name);
        $schedule_stmt->execute();
        $schedules = $schedule_stmt->get_result();

        $schedule_data = [];
        while ($row = $schedules->fetch_assoc()) {
            $schedule_data[] = $row;
        }

        header('Content-Type: application/json');
        echo json_encode($schedule_data);
    } else {
        header('Content-Type: application/json');
        echo json_encode([]);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode([]);
}
?>
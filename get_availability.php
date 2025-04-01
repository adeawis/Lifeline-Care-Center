<?php
// Set headers to prevent caching and specify JSON response
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

// Include database connection
include 'db.php';

try {
    // Get the service type from the request
    $service_type = isset($_GET['service_type']) ? $_GET['service_type'] : '';

    // Map the service types from the booking form to the database positions
    $position_map = [
        'nurse' => 'nurses',
        'caregiver' => 'caregivers',
        'daily_basis_worker' => 'daily'
    ];

    $position = isset($position_map[$service_type]) ? $position_map[$service_type] : '';

    if (empty($position)) {
        throw new Exception('Invalid service type provided');
    }

    // Query to get available staff count
    $query = "SELECT COUNT(*) as count FROM staffreg WHERE position = ? AND availability = 'Available'";
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        throw new Exception($conn->error);
    }

    $stmt->bind_param("s", $position);
    
    if (!$stmt->execute()) {
        throw new Exception($stmt->error);
    }

    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    // Send success response
    echo json_encode([
        'status' => 'success',
        'available_count' => (int)$data['count']
    ]);

} catch (Exception $e) {
    // Send error response
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>
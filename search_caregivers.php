<?php
header('Content-Type: application/json');
include 'db.php';

try {
    // Get search parameters
    $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
    $location = isset($_GET['location']) ? $_GET['location'] : '';

    // Basic search query without distance calculation
    $query = "SELECT 
                full_name,
                position,
                location,
                experience,
                availability 
            FROM staffreg 
            WHERE (position = 'nurses' OR position = 'caregivers' OR position = 'daily')
            AND (full_name LIKE ? OR position LIKE ?)
            AND (location LIKE ?)";

    $searchTerm = "%" . $searchTerm . "%";
    $locationTerm = "%" . $location . "%";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $locationTerm);
    
    if (!$stmt->execute()) {
        throw new Exception($stmt->error);
    }
    
    $result = $stmt->get_result();
    $caregivers = [];
    
    while ($row = $result->fetch_assoc()) {
        $caregivers[] = $row;
    }

    echo json_encode([
        'status' => 'success',
        'data' => $caregivers
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>
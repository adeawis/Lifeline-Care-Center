<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors in response

try {
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "lifeline_carecenter";

    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the type parameter
    $type = $_GET['type'] ?? '';
    
    // Log the received type for debugging
    error_log("Requested type: " . $type);

    // Prepare and execute query
    $stmt = $pdo->prepare("
    SELECT id, full_name 
    FROM staffreg 
    WHERE CASE 
        WHEN ? = 'Nurse' THEN position = 'nurses'
        WHEN ? = 'Caregiver' THEN position = 'caregivers'
        WHEN ? = 'Daily Basis Worker' THEN position = 'daily'
    END
");
$stmt->execute([$type, $type, $type]);
    
    // Fetch all matching staff members
    $staff = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Log the result for debugging
    error_log("Found " . count($staff) . " staff members");
    
    // Return JSON response
    echo json_encode($staff);

    

} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
} catch(Exception $e) {
    error_log("General error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred']);
}
?>
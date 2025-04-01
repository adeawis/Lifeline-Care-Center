<?php
include 'db.php';
header('Content-Type: application/json');

if(isset($_POST['client_id'])) {
    $client_id = $_POST['client_id'];
    
    // Sanitize the input to prevent SQL injection
    $client_id = mysqli_real_escape_string($conn, $client_id);
    
    // Check if an invoice exists for this client
    $query = "SELECT * FROM invoices WHERE client_id = '$client_id'";
    $result = $conn->query($query);
    
    if($result && $result->num_rows > 0) {
        // Invoice exists
        echo json_encode(['exists' => true]);
    } else {
        // No invoice exists
        echo json_encode(['exists' => false]);
    }
} else {
    // Invalid request
    echo json_encode(['error' => 'Invalid request']);
}
?>
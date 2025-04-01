<?php
session_start();
include 'db.php';

if(isset($_POST['generate_invoice'])) {
    $client_id = $_POST['client_id'];
    
    // Fetch client details from booking table
    $query = "SELECT 
        CONCAT(first_name, ' ', last_name) as full_name,
        email,
        amount,
        service_type
    FROM booking 
    WHERE id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($row = $result->fetch_assoc()) {
        // Insert into invoices table
        $insert_query = "INSERT INTO invoices 
            (client_id, full_name, email, amount, service_type) 
            VALUES (?, ?, ?, ?, ?)";
            
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("issds", 
            $client_id,
            $row['full_name'],
            $row['email'],
            $row['amount'],
            $row['service_type']
        );
        
        if($stmt->execute()) {
            $response = ['status' => 'success', 'message' => 'Invoice generated successfully'];
        } else {
            $response = ['status' => 'error', 'message' => 'Failed to generate invoice: ' . $conn->error];
        }
    } else {
        $response = ['status' => 'error', 'message' => 'Booking not found'];
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    
    $stmt->close();
    $conn->close();
} else {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
<?php
session_start();
header('Content-Type: application/json');



// Database connection
$conn = new mysqli("localhost", "root", "", "lifeline_carecenter");

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

// Get the posted data
$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'];
$password = $data['password'];

// Prepare and execute the SQL statement
$stmt = $conn->prepare("SELECT * FROM staffreg WHERE email = ? AND password = ?");
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Successful login
    $user = $result->fetch_assoc();
    
    // Store user data in session
    
    $_SESSION['full_name'] = $user['full_name']; // Assuming your table has a full_name column
    
    $debug_info = [
        'success' => true,
        'session_data' => $_SESSION,
        'user_data' => $user
    ];
    
    echo json_encode(['success' => true]);
} else {
    // Failed login
    echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
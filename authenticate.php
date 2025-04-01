<?php
session_start();

// Debug mode - turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Database connection
$conn = new mysqli("localhost", "root", "", "lifeline_carecenter");

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

// Get the posted data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Debug output
error_log("Received data: " . print_r($input, true));

if (!$data || !isset($data['email']) || !isset($data['password'])) {
    die(json_encode(['success' => false, 'message' => 'Missing email or password']));
}

$email = $data['email'];
$password = $data['password'];

// Simple query first to verify connection
$sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
$result = $conn->query($sql);

if ($result === false) {
    die(json_encode(['success' => false, 'message' => 'Query failed: ' . $conn->error]));
}

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['logged_in'] = true;
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
}

$conn->close();
?>
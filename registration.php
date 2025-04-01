<?php

$server = "localhost";
$username = "root";
$password = "";
$database = "lifeline_carecenter";

// Create connection
$conn = new mysqli($server, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// Retrieve POST data
$full_name = $_POST['full_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = $_POST['password'];
$con_password = $_POST['con_password'];

// Check if email already exists
$check_email = $conn->prepare("SELECT email FROM users WHERE email = ?");
$check_email->bind_param("s", $email);
$check_email->execute();
$check_email->store_result();

if ($check_email->num_rows > 0) {
    echo "<script>alert('Error: This email is already registered. Please use a different email.'); window.location.href = 'register.html';</script>";
}
else {
// SQL query
$sql = "INSERT INTO users (full_name, email, phone, password, con_password) 
VALUES ('$full_name', '$email', '$phone', '$password', '$con_password')";

// Execute query and check if successful
if ($conn->query($sql) === TRUE) {
    $message = "New customer registered: $full_name";
        $stmt = $conn->prepare("INSERT INTO notifications (type, message) VALUES ('registration', ?)");
        $stmt->bind_param("s", $message);
        $stmt->execute();
    echo "<script>
        alert('Successfully Your account has been created!');
        window.location.href = 'sign-in.html';
    </script>";
    
} 
else {
    echo "<script>alert('Error: Unable to create account. Please try again.');</script>";
}
    $stmt->close();
    $notif_stmt->close();
}

// Close connection
$stmt->close();
$conn->close();

?>

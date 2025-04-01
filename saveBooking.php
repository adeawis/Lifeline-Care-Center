<?php
// Establish connection to the database
$conn = new mysqli("localhost", "root", "", "lifeline_carecenter");

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    // Retrieve form data
$first_name = $_POST["first_name"];
$last_name = $_POST["last_name"];
$email = $_POST["email"];
$service_type = $_POST["service_type"];
$start_date = $_POST["start_date"]; 
$end_date = $_POST["end_date"]; 
$gender = $_POST["gender"];
$name_on_card = $_POST["name_on_card"];
$card_number = $_POST["card_number"] ?? '';  // Default to empty if not present
$exp_month = $_POST["exp_month"] ?? '';      // Default to empty if not present
$exp_year = $_POST["exp_year"] ?? '';        // Default to empty if not present
$cvv = $_POST["cvv"] ?? ''; 
$amount = $_POST["amount"] ?? '';                  // Default to empty if not present


    // Prepare SQL statement
$stmt = $conn->prepare(
    "INSERT INTO booking (first_name, last_name, email, service_type, start_date, end_date, gender, name_on_card, card_number, exp_month, exp_year, cvv, amount) 
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '25000')"
);

// Bind parameters
$stmt->bind_param(
    "sssssssssssss", 
    $first_name, 
    $last_name, 
    $email, 
    $service_type, 
    $start_date, 
    $end_date, 
    $gender, 
    $name_on_card, 
    $card_number, 
    $exp_month, 
    $exp_year, 
    $cvv,
    $amount
    
);

// Execute statement
if ($stmt->execute()) {
    $message = "New booking confirmed for $first_name $last_name: $service_type from $start_date to $end_date.";
        $stmt = $conn->prepare("INSERT INTO notifications (type, message) VALUES ('booking', ?)");
        $stmt->bind_param("s", $message);
        $stmt->execute();
    echo "Booking Made Successfully!";
} 
else {
    echo "Error: " . $stmt->error;
}

// Close statement
$stmt->close();
$conn->close();
}

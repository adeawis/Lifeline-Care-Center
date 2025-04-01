<?php
// Database connection
$server = "localhost"; // Your database host
$username = "root"; // Your database username
$password = ""; // Your database password
$databse = "lifeline_carecenter"; // Your database name

$conn = new mysqli($server, $username, $password, $databse);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $age = $_POST['age'];
    $location = $_POST['location'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $position = $_POST['position'];
    $experience = $_POST['experience'];
    $availability = $_POST['availability'];

// Check if email already exists
$check_email = $conn->prepare("SELECT email FROM staffReg WHERE email = ?");
$check_email->bind_param("s", $email);
$check_email->execute();
$check_email->store_result();

if ($check_email->num_rows > 0) {
    echo "<script>alert('Error: This email is already registered. Please use a different email.'); window.location.href = 'staffReg.html';</script>";
} else {
    // Prepare SQL query to insert data
    $sql = "INSERT INTO staffReg (full_name, email, password, age, location, phone, gender, position, experience, availability) 
            VALUES ('$full_name', '$email', '$password', '$age','$location', '$phone', '$gender', '$position', '$experience', '$availability')";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        $message = "New staff member added: $full_name ($position)";
        $stmt = $conn->prepare("INSERT INTO notifications (type, message) VALUES ('staff_addition', ?)");
        $stmt->bind_param("s", $message);
        $stmt->execute();
        echo "<script>
        alert('Successfully Staff had been Added!');
        window.location.href = 'manageCaretakers.php';
        </script>";
    } else {
        echo "<script>alert('Error: Unable to add member. Please try again.');</script>";
    }
    $notif_stmt->close();
}
$check_email->close();
}

// Close connection
$stmt->close();
$conn->close();
?>

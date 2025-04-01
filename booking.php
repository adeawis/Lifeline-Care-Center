<?php
session_start();
include 'db.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: index.html');
    exit();
}

// Get the logged-in user's email
$user_email = $_SESSION['email'];

// Fetch user details from the 'users' table
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caregiver Management & Booking</title>
    <link rel="shortcut icon" href="images/titleIcon.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #b8adf5;
            color: #333;
            min-height: 100vh;
            overflow: auto;
        }

        .container {
            width: 90%;
            margin: 20px auto;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            position: relative;
        }

        .form-section {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #09017e;
            margin: 0 0 20px 0;
            font-size: 24px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 14px;
        }

        input, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .price-box {
            padding: 10px;
            background-color: #f8f8f8;
            text-align: center;
            border-radius: 4px;
            margin: 10px 0;
        }

        .price-box label {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .price-box input {
            text-align: center;
            font-weight: bold;
            color: #6841f5;
            border: none;
            font-size: 18px;
            background: transparent;
        }

        .payment-title {
            color: #6841f5;
            text-align: center;
            font-size: 20px;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #444dc9;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 15px;
        }

        button:hover {
            background-color: #3339a5;
        }
        .payment-section {
            background: #ffffff;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
        }

        .payment-header {
            margin-bottom: 17px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        .payment-title {
            color: #2c3e50;
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
            text-align: left;
        }

        .payment-subtitle {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-top: 3px;
        }

        .card-icons {
            
            margin: 15px 0;
        }

        .card-icon {
            width: 189px;
            height: 50px;
            background: #f8f9fa;
            border-radius: 4px;
            padding: 5px;
            border: 1px solid #e0e0e0;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            flex: 1;
        }

        .form-group label {
            display: block;
            color: #2c3e50;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 15px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1.5px solid #e0e0e0;
            border-radius: 6px;
            font-size: 0.95rem;
            transition: border-color 0.2s ease;
            box-sizing: border-box;
        }

        .form-group input:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .card-number-input {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%23718096" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>');
            background-repeat: no-repeat;
            background-position: 97% center;
            background-size: 20px;
        }

        .cvv-input {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%23718096" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2s8 3 8 9v1c0 4.4-3.6 8-8 8s-8-3.6-8-8v-1c0-6 8-9 8-9"></path><path d="M12 11h.01"></path><path d="M12 15h.01"></path></svg>');
            background-repeat: no-repeat;
            background-position: 97% center;
            background-size: 20px;
        }

        .expiry-container {
            display: flex;
            gap: 10px;
        }

        .expiry-container select {
            flex: 1;
            padding: 12px;
            border: 1.5px solid #e0e0e0;
            border-radius: 6px;
            font-size: 0.95rem;
            background-color: white;
        }

        .pay-button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(to right, #444dc9, #3339a5);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.1s ease;
            margin-top: 10px;
        }

        .pay-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.15);
        }

        .secure-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 15px;
            color: #7f8c8d;
            font-size: 0.85rem;
        }

        .secure-badge svg {
            width: 14px;
            height: 14px;
        }

        .amount-display {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            text-align: center;
        }

        .amount-display .amount {
            font-size: 1.8rem;
            color: #2c3e50;
            font-weight: 600;
        }

        .amount-display .label {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-top: 5px;
        }
        .close-btn{
            position: absolute;
            font-weight: 600;
            top: 10px;
            right: 10px;
            width: 20px;
            height: 20px;
            background: white;
            color: black;
            text-align: center;
            line-height: 19px;
            border-radius: 15px;
            cursor: pointer;
            text-decoration: none;
        }
        .close-btn:hover{
            background: #f0f0f0;
        }

        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 15px;
            }
        }

        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
                width: 95%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
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
$amount = $_POST["amount"] ?? '';                  // Default to empty if not present


    // Prepare SQL statement
$stmt = $conn->prepare(
    "INSERT INTO booking (first_name, last_name, email, service_type, start_date, end_date, gender, amount) 
     VALUES ( ?, ?, ?, ?, ?, ?, ?, '25000')"
);

// Bind parameters
$stmt->bind_param(
    "sssssss", 
    $first_name, 
    $last_name, 
    $email, 
    $service_type, 
    $start_date, 
    $end_date, 
    $gender
    
);

// Execute statement
if ($stmt->execute()) {
    $message = "New booking confirmed for $first_name $last_name: $service_type from $start_date to $end_date.";
        $stmt = $conn->prepare("INSERT INTO notifications (type, message) VALUES ('booking', ?)");
        $stmt->bind_param("s", $message);
        $stmt->execute();
    echo "<script>alert('Thank you for Booking our Service and submitting your payment!'); 
            paymentForm.reset();
            window.location.href = 'booking.php'; </script>";
} 
else {
    echo "Error: " . $stmt->error;
}

// Close statement
$stmt->close();
$conn->close();
}
?>
        <!-- Left section - Caregiver Form -->
        <div class="form-section">
        <a href="indexWelcome.php" class="close-btn">&times;</a>
            <h1>CAREGIVER BOOKING FORM</h1>
            <form id="caregiver-form" action="" method="post">
                <div class="form-group">
                    <label for="first-name">First Name:</label>
                    <input type="text" id="first-name" name="first_name" required>
                </div>

                <div class="form-group">
                    <label for="last-name">Last Name:</label>
                    <input type="text" id="last-name" name="last_name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>" readonly required>
                </div>

                <div class="form-group">
                    <label for="service-type">Service Type:</label>
                    <select id="service-type" name="service_type" required>
                        <option value="" disabled selected>Select Service Type</option>
                        <option value="nurse">Nurse</option>
                        <option value="caregiver">Caregiver</option>
                        <option value="daily_basis_worker">Daily Basis Worker</option>
                    </select>
                </div>
                <div class="form-group">
                    <div id="availability-display" style="margin-top: 5px; font-size: 0.9em; color: #666;"></div>
                </div>

                <div class="form-group">
                    <label for="start_date">Start Period:</label>
                    <input type="date" id="start_date" name="start_date" required>
                </div>

                <div class="form-group">
                    <label for="end_date">End Period:</label>
                    <input type="date" id="end_date" name="end_date" required>
                </div>

                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender" required>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                

            
            

        </div>

        <!-- Right section - Payment Form -->
        <div class="payment-section">
            <div class="payment-header">
                <h2 class="payment-title">Payment Details</h2>
                <p class="payment-subtitle">Enter your card information to complete the payment</p>
            </div>
    
            <div class="amount-display">
                <div class="label">Advanced Payment Amount</div>
                <div class="amount">Rs. 25,000.00</div>
            </div>
    
            <div class="card-icons">
                <img src="images/cards.png" alt="all cards" class="card-icon">
                
            </div>
    
            
                <div class="form-group">
                    <label>Card Holder Name</label>
                    <input type="text" name="" placeholder="Name on card" required>
                </div>
    
                <div class="form-group">
                    <label>Card Number</label>
                    <input type="text" class="card-number-input" name="" placeholder="1234 5678 9012 3456" maxlength="16" required>
                </div>
    
                <div class="form-row">
                    <div class="form-group">
                        <label>Expiry Date</label>
                        <div class="expiry-container">
                            <select required name="">
                                <option value="">Month</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <!-- Add more months -->
                            </select>
                            <select required name="">
                                <option value="">Year</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
                                <option value="2026">2026</option>
                                <option value="2027">2027</option>
                                <option value="2028">2028</option>
                                <option value="2029">2029</option>
                                <option value="2030">2030</option>
                                <option value="2031">2031</option>
                                <option value="2032">2032</option>
                                <option value="2033">2033</option>
                                <option value="2034">2034</option>
                                <option value="2035">2035</option>
                                <option value="2036">2036</option>
                                <option value="2037">2037</option>
                                <!-- Add more years -->
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>CVV</label>
                        <input type="text" class="cvv-input" name="" placeholder="123" maxlength="3" required>
                    </div>
                    <div class="form-group">
                        <label>Amount</label>
                        <input type="text" name="amount" value="25,000.00" readonly required>
                    </div>
                </div>
                
    
                <button type="submit" class="pay-button">Pay Rs. 25,000.00</button>
    
                <div class="secure-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2s8 3 8 9v1c0 4.4-3.6 8-8 8s-8-3.6-8-8v-1c0-6 8-9 8-9"></path>
                        <path d="M12 11h.01"></path>
                        <path d="M12 15h.01"></path>
                    </svg>
                    Secured by SSL encryption
                </div>
            
        </div>
    </form>

    <!-- Popup for confirmation -->
    

    <script>

// Add this inside your existing DOMContentLoaded event listener
document.getElementById('service-type').addEventListener('change', function() {
    const serviceType = this.value;
    const availabilityDisplay = document.getElementById('availability-display');
    
    if (!availabilityDisplay) {
        // Create the display element if it doesn't exist
        const displayDiv = document.createElement('div');
        displayDiv.id = 'availability-display';
        displayDiv.style.marginTop = '5px';
        displayDiv.style.fontSize = '0.9em';
        displayDiv.style.color = '#666';
        this.parentNode.appendChild(displayDiv);
    }
    
    // Show loading message
    availabilityDisplay.textContent = 'Checking availability...';
    
    // Fetch availability data
    fetch(`get_availability.php?service_type=${encodeURIComponent(serviceType)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text().then(text => {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('Failed to parse JSON:', text);
                    throw new Error('Invalid JSON response');
                }
            });
        })
        .then(data => {
            if (data.status === 'success') {
                const count = parseInt(data.available_count);
                availabilityDisplay.textContent = `${count} staff member(s) available`;
                
                if (count === 0) {
                    availabilityDisplay.style.color = '#dc3545';
                } else if (count < 3) {
                    availabilityDisplay.style.color = '#ffc107';
                } else {
                    availabilityDisplay.style.color = '#28a745';
                }
            } else {
                throw new Error(data.message || 'Failed to check availability');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            availabilityDisplay.textContent = 'Error checking availability';
            availabilityDisplay.style.color = '#dc3545';
        });
});

        </script>

        <script src="js/book.js"></script>
</body>
</html>
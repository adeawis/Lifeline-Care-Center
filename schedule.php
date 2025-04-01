<?php
// Get the next service ID from database
$pdo = new PDO("mysql:host=localhost;dbname=lifeline_carecenter", "root", "");
$stmt = $pdo->query("SELECT MAX(CAST(SUBSTRING(serviceId, 2) AS UNSIGNED)) as max_id FROM scheduling");
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$nextId = 'S' . str_pad(($result['max_id'] + 1), 4, '0', STR_PAD_LEFT);
?>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lifeline_carecenter";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ID for the booking query
//$bookingId = 1; // Replace this with the actual value you need
//$staffName = "John Doe"; // Replace this with the actual value you need

// Query for booking details
$bookingQuery = "SELECT 
    first_name,
    last_name,
    first_name AS full_name,
    start_date,
    end_date,
    service_type
FROM booking 
WHERE id = ?";


$bookingStmt = $conn->prepare($bookingQuery);
$bookingStmt->bind_param("i", $bookingId); // Assuming `id` is an integer
$bookingStmt->execute();
$bookingResult = $bookingStmt->get_result();

$bookings = array();
while ($row = $bookingResult->fetch_assoc()) {
    $bookings[] = $row;
}

// Query for staff details
$staffQuery = "SELECT 
    full_name,
    position,
    availability 
FROM staffreg 
WHERE full_name = ?";

$staffStmt = $conn->prepare($staffQuery);
$staffStmt->bind_param("s", $staffName); // Assuming `full_name` is a string
$staffStmt->execute();
$staffResult = $staffStmt->get_result();

$staff = array();
while ($row = $staffResult->fetch_assoc()) {
    $staff[] = $row;
}

// Store data for JavaScript use
$combinedData = array(
    'bookings' => $bookings,
    'staff' => $staff
);

// Close statements and connection
$bookingStmt->close();
$staffStmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="images/titleIcon.png">
    <title>Scheduling Form</title>
    <style>
        /* General Reset */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #93b7ec;
            color: #333;
        }

        /* Container Styling */
        .container {
            max-width: 500px;
            margin: 60px auto;
            background: #ffffff;
            padding: 20px 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        /* Heading */
        h1 {
            text-align: center;
            color: #020336;
            margin-bottom: 20px;
        }

        /* Form Elements */
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: large;
            
        }

        input, select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #007BFF;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        /* Button Styling */
        button {
            background-color: #007BFF;
            color: #fff;
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #c5127b;
        }
        .form-group {
            margin-bottom: 15px;
        }
        
        #staffName {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        
        #staffName:disabled {
            background-color: #f5f5f5;
            cursor: not-allowed;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 15px 20px;
            }

            button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Schedule a Service</h1>
        <form id="schedulingForm" action="submit_form.php" method="POST">
        <div class="form-group">
    <label for="serviceId">Service ID:</label>
    <input type="text" id="serviceId" name="serviceId" value="<?php echo $nextId; ?>" readonly>
</div>

<div class="form-group">
    <label for="type">Type:</label>
    <select id="type" name="type" required onchange="console.log('Type changed to:', this.value); filterStaffNames();">
        <option value="">Select Type</option>
        <option value="Nurse">Nurse</option>
        <option value="Caregiver">Caregiver</option>
        <option value="Daily Basis Worker">Daily Basis Worker</option>
    </select>
</div>

            <div class="form-group">
                <label for="staffName">Staff Name:</label>
                <select id="staffName" name="name" required disabled>
                    <option value="">First select a type</option>
                </select>
            </div>

            <!-- Add this to your HTML form -->
<div class="form-group">
    <label for="client_name">Client Name:</label>
    <input type="text" id="client_name" name="client_name" list="clientList" required>
    <datalist id="clientList"></datalist>
</div>

<div class="form-group">
    <label for="startDate">Start Date:</label>
    <input type="date" id="startDate" name="startDate" required>
</div>

<div class="form-group">
    <label for="endDate">End Date:</label>
    <input type="date" id="endDate" name="endDate" required>
</div>

<div class="form-group">
    <label for="tele">Telephone:</label>
    <input type="tel" id="tele" name="tele" required>
</div>

            <button type="submit" id="addEvent">Add Schedule</button>
        </form>
    </div>

    <script>
async function filterStaffNames() {
    const typeSelect = document.getElementById('type');
    const staffSelect = document.getElementById('staffName');
    const selectedType = typeSelect.value;
    
    console.log('Selected type:', selectedType); // Debug log

    if (!selectedType) {
        staffSelect.innerHTML = '<option value="">Select Staff Member</option>';
        staffSelect.disabled = true;
        return;
    }

    try {
        staffSelect.disabled = true;
        staffSelect.innerHTML = '<option value="">Loading...</option>';
        
        const response = await fetch(`getStaff_schedule.php?type=${encodeURIComponent(selectedType)}`);
        const text = await response.text(); // Get the raw response text first
        
        console.log('Raw response:', text); // Debug log
        
        try {
            const staffMembers = JSON.parse(text);
            console.log('Parsed staff members:', staffMembers);
            
            staffSelect.innerHTML = '<option value="">Select Staff Member</option>';
            if (Array.isArray(staffMembers)) {
                staffMembers.forEach(staff => {
                    const option = document.createElement('option');
                    option.value = staff.full_name;
                    option.textContent = staff.full_name;
                    staffSelect.appendChild(option);
                });
            }
        } catch (parseError) {
            console.error('JSON parse error:', parseError);
            console.log('Invalid JSON response:', text);
            staffSelect.innerHTML = '<option value="">Error parsing staff data</option>';
        }
        
    } catch (error) {
        console.error('Fetch error:', error);
        staffSelect.innerHTML = '<option value="">Error loading staff members</option>';
    } finally {
        staffSelect.disabled = false;
    }
}

// Add event listener when the page loads
document.addEventListener('DOMContentLoaded', () => {
    const typeSelect = document.getElementById('type');
    typeSelect.addEventListener('change', filterStaffNames);
});

const combinedData = <?php echo json_encode($combinedData); ?>;

// Populate client datalist
const clientList = document.getElementById('clientList');
combinedData.bookings.forEach(booking => {
    const option = document.createElement('option');
    option.value = booking.full_name;
    option.dataset.firstName = booking.first_name;
    option.dataset.lastName = booking.last_name;
    option.dataset.startDate = booking.start_date;
    option.dataset.endDate = booking.end_date;
    option.dataset.serviceType = booking.service_type;
    option.dataset.telephone = booking.telephone;
    clientList.appendChild(option);
});

// Filter staff based on type
function filterStaffNames() {
    const selectedType = document.getElementById('type').value;
    const staffSelect = document.getElementById('staffName');
    staffSelect.innerHTML = '<option value="">Select Staff</option>';
    
    const availableStaff = combinedData.staff.filter(s => 
        s.position === selectedType && s.availability === 'Available'
    );
    
    availableStaff.forEach(staff => {
        const option = document.createElement('option');
        option.value = staff.full_name;
        option.textContent = staff.full_name;
        staffSelect.appendChild(option);
    });
    
    staffSelect.disabled = !selectedType;
}

// Auto-populate fields when client is selected
document.getElementById('client_name').addEventListener('change', (e) => {
    const selectedBooking = combinedData.bookings.find(b => b.full_name === e.target.value);
    if (selectedBooking) {
        document.getElementById('startDate').value = selectedBooking.start_date;
        document.getElementById('endDate').value = selectedBooking.end_date;
        document.getElementById('tele').value = selectedBooking.telephone;
        
        // Set service type if it matches available options
        const typeSelect = document.getElementById('type');
        if (selectedBooking.service_type) {
            typeSelect.value = selectedBooking.service_type;
            filterStaffNames();
        }
    }
});
</script>
</body>
</html>

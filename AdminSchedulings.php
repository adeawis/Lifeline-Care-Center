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

// Handle delete request
if(isset($_POST['delete_schedule'])) {
    $scheduleId = $_POST['schedule_id'];
    
    // Prepare and execute delete statement
    $deleteStmt = $conn->prepare("DELETE FROM schedule_admin WHERE schedule_id = ?");
    $deleteStmt->bind_param("s", $scheduleId);
    
    if($deleteStmt->execute()) {
        $deleteMessage = "Schedule deleted successfully.";
    } else {
        $deleteError = "Error deleting schedule: " . $conn->error;
    }
    
    $deleteStmt->close();
}

// Fetch all schedules
$query = "SELECT 
    s.schedule_id AS serviceId,
    s.service_type,
    s.staff_name,
    s.client_name,
    s.start_date AS startDate,
    s.end_date,
    s.status
FROM schedule_admin s
ORDER BY s.start_date DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Manage Schedules - Lifeline Care Center</title>
    <link rel="shortcut icon" href="images/titleIcon.png">
    <link rel="stylesheet" href="css/adminManageStaff.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <style>
        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .schedule-table th, .schedule-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        
        .schedule-table th {
            background-color: #f2f2f2;
            color: #333;
            font-weight: 600;
        }
        
        .schedule-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .schedule-table tr:hover {
            background-color: #f1f1f1;
        }
        
        .delete-btn {
            background-color: #D54A6A;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }
        
        .delete-btn:hover {
            background-color: #A53954;
        }
        
        .add-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            margin-bottom: 20px;
            display: inline-block;
        }
        
        .add-btn:hover {
            background-color: #45a049;
            color: white;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .filter-section {
            margin-bottom: 20px;
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .filter-section select, .filter-section input {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        
        .filter-btn {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .filter-btn:hover {
            background-color: #0056b3;
        }
        
        
    </style>
</head>
<body>
    <div class="side-menu">
        <div class="company-name">
            <h1>Lifeline Care Center</h1>
        </div>
        <ul>
        <li><i class="fa-solid fa-chart-line"></i>&nbsp; <span><a href="adminHome.php">Dashboard</a></span></li>
            <li class="active"><i class="fa-solid fa-id-card-clip"></i>&nbsp; <span><a href="manageCaretakers.php">Manage Caretakers</a></span></li>
            <li><i class="fa-solid fa-user-gear"></i>&nbsp; <span><a href="manageClient.php">Manage Clients</a></span></li>
            <li><i class="fa-solid fa-clipboard"></i>&nbsp; <span><a href="manageBooking.php">Bookings</a></span></li>
            <li><i class="fa-solid fa-credit-card"></i>&nbsp; <span><a href="managePayment.php">Payment & Billing</a></span></li>
            <li><i class="fa-solid fa-bell"></i>&nbsp; <span><a href="adminNotify_alerts.php">Notifications & Alerts</a></span></li>
            <li><i class="fa-solid fa-screwdriver-wrench"></i>&nbsp; <span><a href="userManagement.php">User Management</a></span></li>
            <li><i class="fa-solid fa-question"></i>&nbsp; <span><a href="adminHelp.php">Help</a></span></li>
        </ul>
    </div>
    
    <div class="container-panel">
        <div class="headerAdmin">
            <div class="nav">
                <div class="search">
                    <input type="text" id="searchInput" placeholder="Search schedules...">
                    <button type="button"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
                <div class="user">
                    <img src="images/notifications.png" alt="">
                    <div class="img-case" id="userImage">
                        <img src="images/user.png" alt="">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="notification-panel" id="notificationPanel">
            <div class="notification-header">
                Notifications
            </div>
            <div class="notification-list">
                <div class="notification-item unread">
                    <span class="notification-dot"></span>
                    <div>New schedule added</div>
                </div>
                <div class="notification-item">
                    <div>Welcome to the dashboard!</div>
                </div>
            </div>
        </div>
        
        <div class="popup-logout" id="logoutPopup">
            <p>Are you sure you want to log out?</p>
            <button class="btn confirm-logout">Logout</button>
            <button class="btn cancel-logout">Cancel</button>
        </div>
        
        <div class="content">
            <div class="content-3">
                <div class="staff-details">
                    <div class="title">
                        <h2>Schedule Management</h2>
                        <a href="schedulingAdmin.php" class="add-btn"><i class="fa-solid fa-plus"></i> Add New Schedule</a>
                    </div>
                    
                    <?php if(isset($deleteMessage)): ?>
                        <div class="alert alert-success"><?php echo $deleteMessage; ?></div>
                    <?php endif; ?>
                    
                    <?php if(isset($deleteError)): ?>
                        <div class="alert alert-danger"><?php echo $deleteError; ?></div>
                    <?php endif; ?>
                    
                    <div class="filter-section">
                        <select id="filterType">
                            <option value="">All Types</option>
                            <option value="Nurse">Nurse</option>
                            <option value="Caregiver">Caregiver</option>
                            <option value="Daily Basis Worker">Daily Basis Worker</option>
                        </select>
                        
                        <input type="date" id="filterStartDate" placeholder="Start Date">
                        <input type="date" id="filterEndDate" placeholder="End Date">
                        
                        <button type="button" class="filter-btn" id="applyFilter">Apply Filter</button>
                        <button type="button" class="filter-btn" id="resetFilter">Reset</button>
                    </div>
                    
                    <table class="schedule-table" id="scheduleTable">
                        <thead>
                            <tr>
                                <th>Service ID</th>
                                <th>Service Type</th>
                                <th>Staff Name</th>
                                <th>Client Name</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr data-type="' . htmlspecialchars($row['service_type']) . '">';
                                    echo '<td>' . htmlspecialchars($row['serviceId']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['service_type']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['staff_name']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['client_name']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['startDate']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['end_date']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['status']) . '</td>';
                                    echo '<td>
                                        <button class="delete-btn" onclick="deleteSchedule(\'' . htmlspecialchars($row['serviceId']) . '\')">Delete</button>
                                    </td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="8">No schedules found</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    
    
    <script>
        // Get elements
const userImage = document.getElementById('userImage');
const logoutPopup = document.getElementById('logoutPopup');
const searchInput = document.getElementById('searchInput');
const scheduleTable = document.getElementById('scheduleTable');
const filterType = document.getElementById('filterType');
const filterStartDate = document.getElementById('filterStartDate');
const filterEndDate = document.getElementById('filterEndDate');
const applyFilter = document.getElementById('applyFilter');
const resetFilter = document.getElementById('resetFilter');

// Toggle logout popup
userImage.addEventListener('click', () => {
    logoutPopup.style.display = logoutPopup.style.display === 'block' ? 'none' : 'block';
});

// Hide logout popup when clicking cancel
document.querySelector('.cancel-logout').addEventListener('click', () => {
    logoutPopup.style.display = 'none';
});

// Logout functionality
document.querySelector('.confirm-logout').addEventListener('click', () => {
    alert('Logged out successfully!');
    window.location.href = 'adminLogin.html';
});

// Search functionality
searchInput.addEventListener('keyup', () => {
    const searchValue = searchInput.value.toLowerCase();
    const rows = scheduleTable.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const visible = Array.from(rows[i].getElementsByTagName('td'))
            .some(cell => cell.textContent.toLowerCase().includes(searchValue));
        
        rows[i].style.display = visible ? '' : 'none';
    }
});

// Filter functionality
applyFilter.addEventListener('click', () => {
    const typeValue = filterType.value.toLowerCase();
    const startDate = filterStartDate.value;
    const endDate = filterEndDate.value;
    
    const rows = scheduleTable.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        if (cells.length < 6) continue; // Skip rows without enough cells
        
        const rowType = cells[1].textContent.toLowerCase();
        const rowStartDate = cells[4].textContent;
        const rowEndDate = cells[5].textContent;
        
        let typeMatch = !typeValue || rowType.includes(typeValue);
        let dateMatch = true;
        
        if (startDate && endDate) {
            dateMatch = rowStartDate >= startDate && rowEndDate <= endDate;
        } else if (startDate) {
            dateMatch = rowStartDate >= startDate;
        } else if (endDate) {
            dateMatch = rowEndDate <= endDate;
        }
        
        rows[i].style.display = typeMatch && dateMatch ? '' : 'none';
    }
});

// Reset filters
resetFilter.addEventListener('click', () => {
    filterType.value = '';
    filterStartDate.value = '';
    filterEndDate.value = '';
    
    const rows = scheduleTable.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    for (let i = 0; i < rows.length; i++) {
        rows[i].style.display = '';
    }
});

// Notification panel functionality
document.addEventListener('DOMContentLoaded', function() {
    const notificationImg = document.querySelector('.user img[src*="notifications"]');
    const panel = document.getElementById('notificationPanel');
    
    notificationImg.addEventListener('click', function(e) {
        e.preventDefault();
        panel.classList.toggle('show');
        
        const unreadItems = document.querySelectorAll('.notification-item.unread');
        unreadItems.forEach(item => {
            item.classList.remove('unread');
            const dot = item.querySelector('.notification-dot');
            if (dot) {
                dot.remove();
            }
        });
    });
    
    document.addEventListener('click', function(event) {
        if (!notificationImg.contains(event.target) && !panel.contains(event.target)) {
            panel.classList.remove('show');
        }
    });
});

function deleteSchedule(serviceId) {
    if (confirm("Are you sure you want to delete this schedule?")) {
        fetch("delete_schedules.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "schedule_id=" + encodeURIComponent(serviceId)
        })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === "success") {  
                alert("Schedule deleted successfully!");
                window.location.href = "AdminSchedulings.php";
            } else {
                alert(data);
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Failed to delete the schedule.");
        });
    }
}


        

    </script>
</body>
</html>
<?php
// Close connection
$conn->close();
?>
<?php
session_start();
include 'db.php';

// Fetch bookings from booking table
$bookingQuery = "SELECT first_name, last_name, start_date, end_date, gender, service_type FROM booking";
$bookingResult = $conn->query($bookingQuery);

// Fetch available staff from staffreg table
$staffQuery = "SELECT full_name, position, gender, availability FROM staffreg";
$staffResult = $conn->query($staffQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--==title========================-->
    <title>Admin | Lifeline Care Center</title>
    <!--==Fav-icon=====================-->
    <link rel="shortcut icon" href="images/titleIcon.png">
    <!--==CSS==========================-->
    <link rel="stylesheet" href="css/adminManageStaff.css">
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!--==Font-Awesome-for-icons=====-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <!-- Link Swiper's CSS -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css"/>
    <style>
        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px;
            font-family: 'Poppins', sans-serif;
        }
        .schedule-form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        .booking-select, .staff-select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        button {
            background: #DB4C77;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background: #C23B62;
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
                    <input type="text" placeholder="Search here">
                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
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
            <div>New message received</div>
        </div>
        <div class="notification-item unread">
            <span class="notification-dot"></span>
            <div>Your post was liked</div>
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
    <div class="container">
        <div class="schedule-form">
            <h2>Create Schedule</h2>
            <form action="process_schedule.php" method="POST">
                <div class="form-group">
                    <label>Select Booking:</label>
                    <select name="booking" class="booking-select" required>
                        <option value="">Select a booking...</option>
                        <?php
                        if ($bookingResult->num_rows > 0) {
                            while($booking = $bookingResult->fetch_assoc()) {
                                echo "<option value='" . $booking['first_name'] . "_" . $booking['last_name'] . "'>" .
                                     htmlspecialchars($booking['first_name'] . " " . $booking['last_name']) . 
                                     " - Service: " . htmlspecialchars($booking['service_type']) .
                                     " - Gender: " . htmlspecialchars($booking['gender']) .
                                     " (From: " . htmlspecialchars($booking['start_date']) . 
                                     " To: " . htmlspecialchars($booking['end_date']) . ")</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Assign Staff Member:</label>
                    <select name="staff" class="staff-select" required>
                        <option value="">Select staff member...</option>
                        <?php
                        if ($staffResult->num_rows > 0) {
                            while($staff = $staffResult->fetch_assoc()) {
                                if($staff['availability'] == 'available') {
                                    echo "<option value='" . htmlspecialchars($staff['full_name']) . "'>" .
                                         htmlspecialchars($staff['full_name']) . 
                                         " - Position: " . htmlspecialchars($staff['position']) . 
                                         " - Gender: " . htmlspecialchars($staff['gender']) .
                                         " (Status: " . htmlspecialchars($staff['availability']) . ")</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                </div>

                <button type="submit">Create Schedule</button>
                <div style="margin-top: 20px;">
                    <button type="button" onclick="window.location.href='AdminSchedulings.php'" style="background-color: #4CAF50;">View All Schedules</button>
                </div>
            </form>
        </div>
    </div>
    </div>
    <script>
        // Get the elements
        const userImage = document.getElementById('userImage');
        const logoutPopup = document.getElementById('logoutPopup');

        // Toggle popup visibility
        userImage.addEventListener('click', () => {
        logoutPopup.style.display = logoutPopup.style.display === 'block' ? 'none' : 'block';
    });

        // Hide popup when clicking cancel button
        document.querySelector('.cancel-logout').addEventListener('click', () => {
        logoutPopup.style.display = 'none';
    });

        // Add functionality for the logout button
        document.querySelector('.confirm-logout').addEventListener('click', () => {
        // Perform the logout action here
        alert('Logged out successfully!');
        logoutPopup.style.display = 'none';
    });

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
    </script>
</body>
</html>

<?php
$conn->close();
?>
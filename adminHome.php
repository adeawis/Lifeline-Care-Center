<?php include 'db.php'; ?>

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
    <link rel="stylesheet" href="css/adminPanel.css">
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!--==Font-Awesome-for-icons=====-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <!-- Link Swiper's CSS -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css"
    />

    <style>
        .box-income h1{
            font-size: 24px;
            color: #D54A6A;
        }
        /* Notification Panel Container */
.notification-panel {
    position: absolute;
    top: 60px;
    right: 80px;
    width: 350px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    display: none;
    z-index: 1000;
    max-height: 400px;
    overflow-y: auto;
}

.notification-panel.show {
    display: block;
}

/* Notification Header */
.notification-header {
    padding: 15px 20px;
    background: #f8f9fa;
    border-bottom: 1px solid #eee;
    font-weight: 600;
    color: #D54A6A;
    border-radius: 8px 8px 0 0;
}

/* Notification List Container */
.notification-list {
    padding: 0 !important;
    margin: 0 !important;
    list-style: none;
}

/* Individual Notification Items */
.notification-list li {
    padding: 15px 20px;
    border-bottom: 1px solid #eee;
    color: #444;
    font-size: 14px;
    line-height: 1.4;
    display: flex;
    align-items: flex-start;
    transition: background-color 0.2s;
}

.notification-list li:last-child {
    border-bottom: none;
}

.notification-list li:hover {
    background-color: #f8f9fa;
}

/* Unread Notification Styling */
.notification-list li.unread {
    background-color: #f0f7ff;
}

.notification-list li.unread:hover {
    background-color: #e6f2ff;
}

/* Empty State */
.notification-list:empty::after {
    content: "No notifications";
    display: block;
    padding: 20px;
    text-align: center;
    color: #666;
    font-style: italic;
}

/* Scrollbar Styling */
.notification-panel::-webkit-scrollbar {
    width: 8px;
}

.notification-panel::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.notification-panel::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 4px;
}

.notification-panel::-webkit-scrollbar-thumb:hover {
    background: #bbb;
}
        
    </style>

</head>
<body>
    <div class="side-menu">
        <div class="company-name">
            <h1>Lifeline Care Center</h1>
        </div>
        <ul>
            <li class="active"><i class="fa-solid fa-chart-line"></i>&nbsp; <span><a href="adminHome.php">Dashboard</a></span></li>
            <li><i class="fa-solid fa-id-card-clip"></i>&nbsp; <span><a href="manageCaretakers.php">Manage Caretakers</a></span></li>
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
                    
                    <img src="images/notifications.png" alt="notify bell">
                    <div class="img-case" id="userImage">
                        <img src="images/user.png" alt="admin profile-pic">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="notification-panel" id="notificationPanel">
        <div class="notification-header">
        Notifications
        </div>
        
        <div class="notification-list">
        <ul id="notification-list" class="notification-list"></ul>
        
    </div>
    </div>
        <div class="popup-logout" id="logoutPopup">
            <p>Are you sure you want to log out?</p>
            <button class="btn confirm-logout">Logout</button>
            <button class="btn cancel-logout">Cancel</button>
        </div>

        <div class="content">
            <div class="cards">
                    <?php
                    // Count queries
                    $nurses = $conn->query("SELECT COUNT(*) as count FROM staffreg WHERE position='nurses'")->fetch_assoc();
                    $caregivers = $conn->query("SELECT COUNT(*) as count FROM staffreg WHERE position='caregivers'")->fetch_assoc();
                    $daily = $conn->query("SELECT COUNT(*) as count FROM staffreg WHERE position='daily'")->fetch_assoc();
                    $total_income = $conn->query("SELECT SUM(amount) as total FROM booking")->fetch_assoc();

                    $formatted_income = number_format($total_income['total'], 2);

                    $caretaker_query = "SELECT full_name FROM staffreg 
                    WHERE (position='nurses' OR position='caregivers' OR position='daily') 
                    ORDER BY full_name ASC LIMIT 5";
                    $caretaker_result = $conn->query($caretaker_query);

                    $booking_query = "SELECT email, service_type, start_date, end_date, amount 
                    FROM booking ORDER BY start_date DESC  LIMIT 5";  // Limit to 5 most recent bookings
                    $booking_result = $conn->query($booking_query);
                    ?>

                <div class="card">
                    <div class="box">
                        <h1><?php echo $nurses['count']; ?></h1>
                        <h3>Nurses</h3>
                    </div>
                    <div class="icon-case">
                        <i class="fa-solid fa-user-nurse"></i>
                    </div>
                </div>
                <div class="card">
                    <div class="box">
                        <h1><?php echo $caregivers['count']; ?></h1>
                        <h3>Caregivers</h3>
                    </div>
                    <div class="icon-case">
                        <i class="fa-solid fa-house-chimney-user"></i>
                    </div>
                </div>
                <div class="card">
                    <div class="box">
                        <h1><?php echo $daily['count']; ?></h1>
                        <h3>Daily Basis</h3>
                    </div>
                    <div class="icon-case">
                        <i class="fa-solid fa-person-circle-plus"></i>
                    </div>
                </div>
                <div class="card">
                    <div class="box-income">
                        <h1>Rs. <?php echo $formatted_income; ?></h1>
                        <h3>Income</h3>
                    </div>
                    <div class="icon-case">
                        <i class="fa-solid fa-hand-holding-dollar"></i>
                    </div>
                </div>
            </div>
            <div class="content-2">
                <div class="recent-bookings">
                    <div class="title">
                        <h2>Recent Bookings</h2>
                        <a href="manageBooking.php" class="btn">View All</a>
                    </div>
                    <table>
                        <tr>
                            <th>Email</th>
                            <th>Service Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Advance Amount</th>
                            
                        </tr>
                        <?php
                    if ($booking_result->num_rows > 0) {
                    while($row = $booking_result->fetch_assoc()) {
                    ?>
                    <tr>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['service_type']); ?></td>
                    <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['end_date']); ?></td>
                    <td>Rs. <?php echo number_format($row['amount'], 2); ?></td>
                    
                    </tr>
                <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="5">No recent bookings found</td>
                </tr>
            <?php
            }
            ?>
                </table>
                </div>
                <div class="new-caretaker">
                    <div class="title">
                        <h2>New Caretakers</h2>
                        <a href="manageCaretakers.php" class="btn">View All</a>
                    </div>
                    <table>
                        <tr>
                            <th>Profile</th>
                            <th>Name</th>
                            <th>Option</th>
                        </tr>
                        <?php
                        if ($caretaker_result->num_rows > 0) {
                        while($row = $caretaker_result->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><i class="fa-solid fa-circle-user"></i></td>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><i class="fa-solid fa-circle-info"></i></td>
                        </tr>
                        <?php
                    }
                    } else {
                    ?>
                    <tr>
                        <td colspan="3">No new caretakers found</td>
                    </tr>
                    <?php
                    }
                    ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        const userImage = document.getElementById('userImage');
        const logoutPopup = document.getElementById('logoutPopup');

        userImage.addEventListener('click', () => {
        logoutPopup.style.display = logoutPopup.style.display === 'block' ? 'none' : 'block';
    });

        document.querySelector('.cancel-logout').addEventListener('click', () => {
        logoutPopup.style.display = 'none';
    });

        document.querySelector('.confirm-logout').addEventListener('click', () => {
        alert('Logged out successfully!');
        window.location.href = 'adminLogin.html';
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

setInterval(() => {
        fetch('fetch_notifications.php')
            .then(response => response.json())
            .then(data => {
                const list = document.getElementById('notification-list');
                list.innerHTML = '';
                data.forEach(notification => {
                    const li = document.createElement('li');
                    li.textContent = notification.message;
                    list.appendChild(li);
                });
            });
    }, 1000); // Fetch every 1 seconds

    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help - Lifeline Care Center</title>
    <link rel="stylesheet" href="css/adminManageStaff.css">
    <link rel="shortcut icon" href="images/titleIcon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        .help-container {
            padding: 20px;
            margin-left: 20px;
        }

        .help-header {
            margin-bottom: 30px;
        }

        .help-header h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .help-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .help-section h2 {
            color: #D54A6A;
            font-size: 20px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .help-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .help-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .help-card h3 {
            color: #333;
            font-size: 16px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .help-content {
            display: none;
            margin-top: 10px;
            padding: 10px;
            background: white;
            border-radius: 5px;
            color: #666;
            line-height: 1.6;
        }

        .help-card.active .help-content {
            display: block;
        }

        .contact-support {
            background: #D54A6A;
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }

        .contact-support h3 {
            margin-bottom: 15px;
        }

        .contact-info {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-help {
            margin-bottom: 20px;
        }

        .search-help input {
            width: 100%;
            max-width: 400px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .help-card i {
            color: #D54A6A;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .action-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #eee;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .action-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .action-card i {
            color: #D54A6A;
            font-size: 20px;
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
            <li><i class="fa-solid fa-id-card-clip"></i>&nbsp; <span><a href="manageCaretakers.php">Manage Caretakers</a></span></li>
            <li><i class="fa-solid fa-user-gear"></i>&nbsp; <span><a href="manageClient.php">Manage Clients</a></span></li>
            <li><i class="fa-solid fa-clipboard"></i>&nbsp; <span><a href="manageBooking.php">Bookings</a></span></li>
            <li><i class="fa-solid fa-credit-card"></i>&nbsp; <span><a href="managePayment.php">Payment & Billing</a></span></li>
            <li><i class="fa-solid fa-bell"></i>&nbsp; <span><a href="">Notifications & Alerts</a></span></li>
            <li><i class="fa-solid fa-screwdriver-wrench"></i>&nbsp; <span><a href="userManagement.php">User Management</a></span></li>
            <li class="active"><i class="fa-solid fa-question"></i>&nbsp; <span><a href="adminHelp.php">Help</a></span></li>
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
    <div class="help-container">
        <div class="help-header">
            <h1>Help Center</h1>
            <div class="search-help">
                <input type="text" placeholder="Search for help topics..." id="searchHelp">
            </div>
        </div>

    

        <div class="help-section">
            <h2><i class="fas fa-star"></i> Common Tasks</h2>
            <div class="help-card">
                <h3><i class="fas fa-user-plus"></i> Managing Staff</h3>
                <div class="help-content">
                    1. Navigate to Admin Dashboard and select the Manage Caretakers Section.<br>
                    2. Click on the Add Caretaker button to add a new staff member.<br>
                    3. Fill in the required details and click Save to create the new staff account.<br>
                    4. You can also edit or delete existing staff accounts from the same section.
            </div>
            </div>
            <div class="help-card">
                <h3><i class="fas fa-calendar-alt"></i> Booking Management</h3>
                <div class="help-content">
                    1. Go to the Bookings section in the Admin Dashboard.<br>
                    2. View the list of upcoming bookings and their details.<br>
                    3. Click on Delete button shown in the action section to delete that relevant booking detail.
            </div>
            </div>
            <div class="help-card">
                <h3><i class="fas fa-credit-card"></i> Payment Processing</h3>
                <div class="help-content">
                    1. Navigate to the Payment & Billing section in the Admin Dashboard.<br>
                    2. View the list of payments received.<br>
                    3. Click on the Invoice button to send the payment invoice to that relevant client.
            </div>
            </div>
        </div>

        <div class="help-section">
            <h2><i class="fas fa-cog"></i> System Settings</h2>
            <div class="help-card">
                <h3><i class="fas fa-shield-alt"></i> Admin User Settings</h3>
                <div class="help-content">
                    1. Go to the User Management section in the Admin Dashboard.<br>
                    2. View User Admin's details.<br>
                    3. You can edit user admin's details from this section.
            </div>
            </div>
            <div class="help-card">
                <h3><i class="fas fa-bell"></i> Notification Settings</h3>
                <div class="help-content">
                    1. Click on the bell icon in the top right corner to view notifications.<br>
                    2. You can mark notifications as read or delete them from the Notifications & Alerts list.<br>
            </div>
            </div>
        </div>

        <div class="contact-support">
            <h3><i class="fas fa-headset"></i> Need Additional Help?</h3>
            <div class="contact-info">
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <span>infolifelinecarecenter@gmail.com</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <span>+94 123 4567 890</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-clock"></i>
                    <span>24/7 Support Available</span>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        // Toggle help content visibility
        document.querySelectorAll('.help-card').forEach(card => {
            card.addEventListener('click', () => {
                card.classList.toggle('active');
            });
        });

        // Search functionality
        document.getElementById('searchHelp').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            document.querySelectorAll('.help-card').forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(searchTerm) ? 'block' : 'none';
            });
        });

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
    </script>
</body>
</html>
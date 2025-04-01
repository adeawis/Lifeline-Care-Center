<?php
// Include database connection
require_once 'db.php';

// Function to fetch notifications
function getNotifications($conn) {
    $query = "SELECT 
        id,
        type,
        message,
        related_id,
        is_read,
        CASE
            WHEN TIMESTAMPDIFF(MINUTE, created_at, NOW()) < 60 THEN CONCAT(TIMESTAMPDIFF(MINUTE, created_at, NOW()), ' minutes ago')
            WHEN TIMESTAMPDIFF(HOUR, created_at, NOW()) < 24 THEN CONCAT(TIMESTAMPDIFF(HOUR, created_at, NOW()), ' hours ago')
            ELSE DATE_FORMAT(created_at, '%M %d, %Y')
        END as time_ago
    FROM notifications 
    ORDER BY created_at DESC";
    
    $result = mysqli_query($conn, $query);
    $notifications = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $notifications[] = $row;
    }
    
    return $notifications;
}

// Handle AJAX requests
if (isset($_POST['action'])) {
    $response = ['success' => false];
    
    switch ($_POST['action']) {
        case 'mark_read':
            $id = mysqli_real_escape_string($conn, $_POST['id']);
            $query = "UPDATE notifications SET is_read = 1 WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            $response['success'] = mysqli_stmt_execute($stmt);
            break;

        case 'delete':
            $id = mysqli_real_escape_string($conn, $_POST['id']);
            $query = "DELETE FROM notifications WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            $response['success'] = mysqli_stmt_execute($stmt);
            break;
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Fetch initial notifications
$notifications = getNotifications($conn);
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
      href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css"
    />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        .notification-container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .notification-header h1 {
            color: #D54A6A;
        }

        .notification-toggle {
            display: flex;
            gap: 10px;
        }

        .toggle-btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            background: #D54A6A;
            color: white;
            cursor: pointer;
        }

        .toggle-btn.active {
            background: #fff;
            color: #D54A6A;
            border: 1px solid #D54A6A;
        }

        .notification-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            align-items: flex-start;
            gap: 15px;
            transition: transform 0.2s;
        }

        .notification-card:hover {
            transform: translateY(-2px);
        }

        .notification-card.unread {
            border-left: 4px solid #D54A6A;
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            background: #D54A6A;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .notification-time {
            color: #666;
            font-size: 0.9em;
        }

        .notification-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .action-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9em;
        }

        .mark-read {
            background: #D54A6A;
            color: white;
        }

        .delete {
            background: #ff4444;
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        .notification-related {
            font-size: 0.85em;
            color: #666;
            margin-top: 5px;
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
            <li class="active"><i class="fa-solid fa-bell"></i>&nbsp; <span><a href="adminNotify_alerts.php">Notifications & Alerts</a></span></li>
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
    <div class="notification-container">
        <div class="notification-header">
            <h1>Notifications & Alerts</h1>
            <div class="notification-toggle">
                <button class="toggle-btn active" data-filter="all">All</button>
                <button class="toggle-btn" data-filter="unread">Unread</button>
            </div>
        </div>

        <div id="notifications-list">
            <!-- Notifications will be dynamically inserted here -->
        </div>
    </div>
</div>

    <script>
        // Global variables for notifications
        let notifications = <?php echo json_encode($notifications); ?>;
        let currentFilter = 'all';

        // Function to mark notification as read
        function markAsRead(id) {
            makeRequest('mark_read', id)
                .then(response => {
                    if (response.success) {
                        const notification = notifications.find(n => n.id == id);
                        if (notification) {
                            notification.is_read = 1;
                            renderNotifications(currentFilter);
                        }
                    }
                });
        }

        // Function to delete notification
        function deleteNotification(id) {
            if (confirm("Are you sure you want to delete this notification?")) {
                makeRequest('delete', id)
                    .then(response => {
                        if (response.success) {
                            notifications = notifications.filter(n => n.id != id);
                            renderNotifications(currentFilter);
                        }
                    });
            }
        }

        // Function to make AJAX request
        async function makeRequest(action, id) {
            const formData = new FormData();
            formData.append('action', action);
            formData.append('id', id);

            try {
                const response = await fetch('adminNotify_alerts.php', {
                    method: 'POST',
                    body: formData
                });
                return await response.json();
            } catch (error) {
                console.error('Error:', error);
                return { success: false };
            }
        }

        // Function to get icon based on notification type
        function getIconForType(type) {
            const icons = {
                'booking': 'fa-calendar-check',
                'payment': 'fa-credit-card',
                'staff': 'fa-user',
                'system': 'fa-gear',
                'client': 'fa-users'
            };
            return icons[type] || 'fa-bell';
        }

        // Function to create notification card
        function createNotificationCard(notification) {
            return `
                <div class="notification-card ${notification.is_read == 0 ? 'unread' : ''}" data-id="${notification.id}">
                    <div class="notification-icon">
                        <i class="fa-solid ${getIconForType(notification.type)}"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-message">${notification.message}</div>
                        <div class="notification-time">${notification.time_ago}</div>
                        ${notification.related_id ? `<div class="notification-related">ID: ${notification.related_id}</div>` : ''}
                        <div class="notification-actions">
                            ${notification.is_read == 0 ? 
                                `<button class="action-btn mark-read" onclick="markAsRead(${notification.id})">Mark as Read</button>` : 
                                ''}
                            <button class="action-btn delete" onclick="deleteNotification(${notification.id})">Delete</button>
                        </div>
                    </div>
                </div>
            `;
        }

        // Function to render notifications
        function renderNotifications(filter = 'all') {
            const notificationsList = document.getElementById('notifications-list');
            const filteredNotifications = filter === 'unread' 
                ? notifications.filter(n => n.is_read == 0)
                : notifications;

            if (filteredNotifications.length === 0) {
                notificationsList.innerHTML = `
                    <div class="empty-state">
                        <i class="fa-regular fa-bell" style="font-size: 2em; margin-bottom: 10px;"></i>
                        <p>No notifications to show</p>
                    </div>
                `;
                return;
            }

            notificationsList.innerHTML = filteredNotifications
                .map(createNotificationCard)
                .join('');
        }

        // DOM ready handler
        document.addEventListener('DOMContentLoaded', function() {
            // Set up filter buttons
            document.querySelectorAll('.toggle-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    document.querySelectorAll('.toggle-btn').forEach(b => b.classList.remove('active'));
                    e.target.classList.add('active');
                    currentFilter = e.target.dataset.filter;
                    renderNotifications(currentFilter);
                });
            });

            // Initial render
            renderNotifications();

            // Logout popup
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
            
            // Notification panel toggle
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

        // Initial render outside DOMContentLoaded to ensure it's available
        renderNotifications();
    </script>
</body>
</html>
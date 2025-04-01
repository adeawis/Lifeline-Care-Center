<?php
include 'db.php';

// Create notifications table if it doesn't exist
$create_table = "CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    related_id INT,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($create_table);

// Function to add new notification
function addNotification($conn, $type, $message, $related_id = null) {
    $sql = "INSERT INTO notifications (type, message, related_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $type, $message, $related_id);
    $stmt->execute();
}

// Function to get unread notifications count
function getUnreadCount($conn) {
    $result = $conn->query("SELECT COUNT(*) as count FROM notifications WHERE is_read = 0");
    return $result->fetch_assoc()['count'];
}

// Function to mark notification as read
function markAsRead($conn, $notification_id) {
    $sql = "UPDATE notifications SET is_read = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $notification_id);
    $stmt->execute();
}

// Function to get recent notifications
function getNotifications($conn, $limit = 10) {
    $sql = "SELECT * FROM notifications ORDER BY created_at DESC LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// API endpoint to get notifications
if(isset($_GET['action'])) {
    header('Content-Type: application/json');
    
    switch($_GET['action']) {
        case 'get':
            echo json_encode(getNotifications($conn));
            break;
        case 'count':
            echo json_encode(['count' => getUnreadCount($conn)]);
            break;
        case 'markRead':
            if(isset($_POST['id'])) {
                markAsRead($conn, $_POST['id']);
                echo json_encode(['success' => true]);
            }
            break;
    }
    exit;
}
?>
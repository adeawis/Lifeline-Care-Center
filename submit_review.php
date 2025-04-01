<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $caretaker_name = $_POST['caretaker_name'];
    $client_name = $_POST['client_name'];
    $client_email = $_POST['client_email'];
    $rating = $_POST['rating'];
    $review_text = $_POST['review_text'];

    $stmt = $conn->prepare("INSERT INTO reviews (caretaker_name, client_name, client_email, rating, review_text) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $caretaker_name, $client_name, $client_email, $rating, $review_text);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Review submitted successfully!";
    } else {
        $_SESSION['error_message'] = "Error submitting review.";
    }

    header('Location: userProfile.php');
    exit();
}
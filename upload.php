<?php
session_start();
include("connect.php");

// Check if the user is logged in
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

// Define the target directory for uploads
$target_dir = "uploads/"; // Make sure this directory exists and is writable
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755, true); // Create the directory if it doesn't exist
}
$target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Validate file type
$allowed_types = array("jpg", "jpeg", "png");
if (!in_array($imageFileType, $allowed_types)) {
    header("Location: lobby.php?status=error&type");
    exit();
}

// Validate file size (limit to 25MB)
if ($_FILES["profile_picture"]["size"] > 25 * 1024 * 1024) { // 25 MB
    header("Location: lobby.php?status=error:size");
    exit();
}

// Move the uploaded file to the target directory
if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
    // Update session with new profile picture path
    $_SESSION['profile_picture'] = $target_file;

    // Optionally, save the path to the database
    $user_id = $_SESSION['id']; // Get the user ID from session
    $query = "UPDATE users SET profile_picture = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $target_file, $user_id);
    $stmt->execute();

    // Redirect back to lobby.php with success message
    header("Location: lobby.php?status=success");
} else {
    header("Location: lobby.php?status=error:upload");
}
?>

<?php
/**
 * Starts a new PHP session.
 * Includes the database connection file.
 * Checks if the user is logged in, and redirects to the login page if not.
 * Handles the submission of the phone number update form.
 * Validates the phone number format.
 * Updates the user's phone number in the database and the session.
 * Redirects to the lobby page with a success or error message.
 */

session_start();
include("connect.php");

if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate the phone number
    $phone_number = isset($_POST['phone_number']) ? trim($_POST['phone_number']) : '';

    // Check if phone number is valid
    if (!preg_match('/^\d{10,15}$/', $phone_number)) {
        // Redirect back with an error message
        header("Location: lobby.php?status=error:phone&section=profile");
        exit();
    }

    // Prepare an update statement
    $id = $_SESSION['id'];
    $stmt = $conn->prepare("UPDATE users SET phone_number = ? WHERE id = ?");
    $stmt->bind_param("si", $phone_number, $id);

    // Execute the statement
    if ($stmt->execute()) {
        // Update session variable
        $_SESSION['phone_number'] = $phone_number;
        header("Location: lobby.php?status=success&section=profile");
    } else {
        header("Location: lobby.php?status=error:update&section=profile");
    }
    
    $stmt->close();
    $conn->close();
}
?>

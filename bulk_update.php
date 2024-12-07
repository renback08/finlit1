<?php
session_start();
include("connect.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

$data = json_decode(file_get_contents('php://input'), true);
$success = true;
$messages = [];

foreach ($data as $update) {
    if ($update['type'] === 'users') {
        $stmt = $conn->prepare("UPDATE users SET last_modified = NOW() WHERE id = ?");
        $stmt->bind_param("i", $update['id']);
        
        if (!$stmt->execute()) {
            $success = false;
            $messages[] = "Failed to update record ID: " . $update['id'];
        }
    }
}

echo json_encode([
    'success' => $success,
    'messages' => $messages
]);

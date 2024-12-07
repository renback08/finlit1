<?php
session_start();
include("connect.php");

if (!isset($_SESSION['user_name']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userIds = explode(',', $_POST['userId']);
    
    $query = "DELETE FROM users WHERE id IN (" . str_repeat('?,', count($userIds) - 1) . '?)';
    $stmt = $conn->prepare($query);
    
    $types = str_repeat('i', count($userIds));
    $stmt->bind_param($types, ...$userIds);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Users deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting users']);
    }
    
    $stmt->close();
}
?>

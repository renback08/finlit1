<?php
session_start();
include("connect.php");

if (!isset($_SESSION['user_name']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userIds = json_decode(file_get_contents('php://input'))->userIds;
    
    $query = "DELETE FROM users WHERE id IN (" . str_repeat('?,', count($userIds) - 1) . "?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param(str_repeat('i', count($userIds)), ...$userIds);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Users deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting users']);
    }
    
    $stmt->close();
}
?>

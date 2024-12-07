<?php
session_start();
include("connect.php");

if (!isset($_SESSION['user_name']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

$data = json_decode(file_get_contents('php://input'));
$moduleId = $data->id;

$stmt = $conn->prepare("DELETE FROM modules WHERE module_id = ?");
$stmt->bind_param("i", $moduleId);

echo json_encode(['success' => $stmt->execute()]);
$stmt->close();
?>

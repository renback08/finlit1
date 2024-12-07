<?php
session_start();
include("connect.php");

if (!isset($_SESSION['user_name']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

$data = json_decode(file_get_contents('php://input'));
$assessmentId = $data->id;

$stmt = $conn->prepare("DELETE FROM assessments WHERE assessment_id = ?");
$stmt->bind_param("i", $assessmentId);

echo json_encode(['success' => $stmt->execute()]);
$stmt->close();
?>

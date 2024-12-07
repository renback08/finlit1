<?php
session_start();
include("connect.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    exit(json_encode(['error' => 'Unauthorized']));
}

$id = $_GET['id'];
$type = $_GET['type'];

if ($type === 'users') {
    $stmt = $conn->prepare("SELECT id, first_name, last_name, email, role FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode($result->fetch_assoc());
}
if ($type === 'modules') {
    $stmt = $conn->prepare("SELECT module_id, topics, subtopics, description, 
        content_1, content_2, content_3, content_4, content_5, created_at 
        FROM modules WHERE module_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode($result->fetch_assoc());
}

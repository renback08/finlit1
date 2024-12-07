<?php
session_start();
include("connect.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("
        UPDATE modules 
        SET topics = ?, 
            subtopics = ?, 
            description = ?, 
            content_1 = ?,
            content_2 = ?,
            content_3 = ?,
            content_4 = ?,
            content_5 = ?
        WHERE module_id = ?
    ");
     
    $stmt->bind_param("ssssssssi", 
        $_POST['topics'],
        $_POST['subtopics'],
        $_POST['description'],
        $_POST['content_1'],
        $_POST['content_2'],
        $_POST['content_3'],
        $_POST['content_4'],
        $_POST['content_5'],
        $_POST['module_id']
    );
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
    }
}

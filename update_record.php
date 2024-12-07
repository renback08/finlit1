<?php
session_start();
include("connect.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

header('Content-Type: application/json');

try {
    $type = $_POST['type'] ?? 'users';
    
    if ($type === 'users') {
        $stmt = $conn->prepare("
            UPDATE users 
            SET first_name = ?, last_name = ?, email = ?, role = ?, 
                modified_at = CURRENT_TIMESTAMP 
            WHERE id = ?
        ");
        
        $stmt->bind_param("ssssi", 
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['email'],
            $_POST['role'],
            $_POST['user_id']
        );
    } else if ($type === 'modules') {
        $stmt = $conn->prepare("
            UPDATE modules 
            SET topics = ?, subtopics = ?, description = ?, 
                content_1 = ?, content_2 = ?, content_3 = ?, 
                content_4 = ?, content_5 = ? 
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
    }

    $result = $stmt->execute();
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Record updated successfully'
        ]);
    } else {
        throw new Exception($stmt->error);
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error updating record: ' . $e->getMessage()
    ]);
}

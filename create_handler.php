<?php
session_start();
include("connect.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => ''];
    
    try {
        $type = $_POST['type'];

        if ($type === 'user') {
            $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, student_id, email, password, role) VALUES (?, ?, ?, ?, ?, ?)");
            
            $default_password = password_hash('defaultpass123', PASSWORD_DEFAULT);
            $stmt->bind_param("ssssss", 
                $_POST['first_name'],
                $_POST['last_name'],
                $_POST['student_id'],
                $_POST['email'],
                $default_password,
                $_POST['role']
            );

        } elseif ($type === 'module') {
            $stmt = $conn->prepare("INSERT INTO modules (topics, subtopics, description, content_1, content_2, content_3, content_4, content_5) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            
            $stmt->bind_param("ssssssss",
                $_POST['topics'],
                $_POST['subtopics'],
                $_POST['description'],
                $_POST['content_1'],
                $_POST['content_2'],
                $_POST['content_3'],
                $_POST['content_4'],
                $_POST['content_5']
            );
        }

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Created successfully';
        } else {
            throw new Exception($stmt->error);
        }

    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    echo json_encode($response);
    exit;
}
?>

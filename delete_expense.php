<?php
session_start();
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['id'];
    $expense_id = $_POST['id'];

    $sql = "DELETE FROM expenses WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $expense_id, $user_id);
    
    $response = ['success' => false];
    if ($stmt->execute()) {
        $response['success'] = true;
    }

    echo json_encode($response);
}
?>
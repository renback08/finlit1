<?php
session_start();
require_once 'connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    die(json_encode(['success' => false, 'message' => 'User not logged in']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn->begin_transaction();
    try {
        $user_id = $_SESSION['id'];
        $text = trim($_POST['text']);
        $amount = floatval($_POST['amount']);
        $type = $_POST['type'];
        $transaction_date = $_POST['transaction_date'];
        
        if ($type === 'expense') {
            $amount = -$amount;
        }
        
        $transaction_date = $_POST['transaction_date'];
        if (!empty($transaction_date)) {
            $sql = "INSERT INTO expenses (user_id, text, amount, type, transaction_date) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isdss", $user_id, $text, $amount, $type, $transaction_date);
        }
        $stmt->execute();
        
        $conn->commit();
        echo json_encode(['success' => true]);
        
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Transaction failed']);
    }
}

$conn->close();
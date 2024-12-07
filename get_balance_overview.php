<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['id'])) {
    http_response_code(401);
    exit;
}

$userId = $_SESSION['id'];
$timeRange = isset($_GET['range']) ? $_GET['range'] : 'total';

$sql = "SELECT 
    -- Weekly averages
    (SELECT SUM(amount) / CEIL(DATEDIFF(NOW(), MIN(transaction_date))/7) 
     FROM expenses WHERE user_id = ?) as weekly_balance,
    (SELECT SUM(CASE WHEN amount >= 0 THEN amount ELSE 0 END) / CEIL(DATEDIFF(NOW(), MIN(transaction_date))/7)
     FROM expenses WHERE user_id = ?) as weekly_income,
    (SELECT ABS(SUM(CASE WHEN amount < 0 THEN amount ELSE 0 END)) / CEIL(DATEDIFF(NOW(), MIN(transaction_date))/7)
     FROM expenses WHERE user_id = ?) as weekly_expense,
    
    -- Monthly averages
    (SELECT SUM(amount) / CEIL(DATEDIFF(NOW(), MIN(transaction_date))/30)
     FROM expenses WHERE user_id = ?) as monthly_balance,
    (SELECT SUM(CASE WHEN amount >= 0 THEN amount ELSE 0 END) / CEIL(DATEDIFF(NOW(), MIN(transaction_date))/30)
     FROM expenses WHERE user_id = ?) as monthly_income,
    (SELECT ABS(SUM(CASE WHEN amount < 0 THEN amount ELSE 0 END)) / CEIL(DATEDIFF(NOW(), MIN(transaction_date))/30)
     FROM expenses WHERE user_id = ?) as monthly_expense,
    
    -- Annual averages
    (SELECT SUM(amount) / CEIL(DATEDIFF(NOW(), MIN(transaction_date))/365)
     FROM expenses WHERE user_id = ?) as yearly_balance,
    (SELECT SUM(CASE WHEN amount >= 0 THEN amount ELSE 0 END) / CEIL(DATEDIFF(NOW(), MIN(transaction_date))/365)
     FROM expenses WHERE user_id = ?) as yearly_income,
    (SELECT ABS(SUM(CASE WHEN amount < 0 THEN amount ELSE 0 END)) / CEIL(DATEDIFF(NOW(), MIN(transaction_date))/365)
     FROM expenses WHERE user_id = ?) as yearly_expense,
     
    -- Total values
    (SELECT SUM(amount) FROM expenses WHERE user_id = ?) as total_balance,
    (SELECT SUM(CASE WHEN amount >= 0 THEN amount ELSE 0 END) FROM expenses WHERE user_id = ?) as total_income,
    (SELECT ABS(SUM(CASE WHEN amount < 0 THEN amount ELSE 0 END)) FROM expenses WHERE user_id = ?) as total_expense";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiiiiiiiiiii", $userId, $userId, $userId, $userId, $userId, $userId, $userId, $userId, $userId, $userId, $userId, $userId);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

header('Content-Type: application/json');
echo json_encode($result);

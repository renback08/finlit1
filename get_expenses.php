<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'User not authenticated']);
    exit;
}

try {
    $user_id = $_SESSION['id'];
    $range = isset($_GET['range']) ? $_GET['range'] : 'daily';
    $sql = "SELECT * FROM expenses WHERE user_id = ? ";

    switch($range) {
        case 'daily':
            $sql .= "AND transaction_date >= DATE_SUB(NOW(), INTERVAL 30 DAY) ";
            break;
        case 'weekly':
            $sql .= "AND transaction_date >= DATE_SUB(NOW(), INTERVAL 12 WEEK) ";
            break;
        case 'monthly':
            $sql .= "AND transaction_date >= DATE_SUB(NOW(), INTERVAL 12 MONTH) ";
            break;
        case 'annually':
            $sql .= "AND transaction_date >= DATE_SUB(NOW(), INTERVAL 5 YEAR) ";
            break;
    }

    $sql .= "ORDER BY transaction_date ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $transactions = [];
    
    while ($row = $result->fetch_assoc()) {
        $transactions[] = [
            'id' => $row['id'],
            'text' => $row['text'],
            'amount' => floatval($row['amount']),
            'transaction_date' => $row['transaction_date'],
            'type' => $row['type']
        ];
    }
    
    header('Content-Type: application/json');
    echo json_encode($transactions);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
// Add this after the existing user_id check
$range = isset($_GET['range']) ? $_GET['range'] : 'daily';
  $sql = "SELECT 
    DATE(transaction_date) as date,
    SUM(CASE WHEN amount >= 0 THEN amount ELSE 0 END) as income,
    ABS(SUM(CASE WHEN amount < 0 THEN amount ELSE 0 END)) as expense
    FROM expenses 
    WHERE user_id = ? ";

  switch($range) {
      case 'daily':
          $sql .= "AND transaction_date >= DATE_SUB(NOW(), INTERVAL 30 DAY) ";
          break;
      case 'weekly':
          $sql .= "AND transaction_date >= DATE_SUB(NOW(), INTERVAL 12 WEEK) ";
          break;
      case 'monthly':
          $sql .= "AND transaction_date >= DATE_SUB(NOW(), INTERVAL 12 MONTH) ";
          break;
      case 'annually':
          $sql .= "AND transaction_date >= DATE_SUB(NOW(), INTERVAL 5 YEAR) ";
          break;
  }

  $sql .= "GROUP BY DATE(transaction_date) ORDER BY date ASC";
  $sql .= "ORDER BY transaction_date ASC";
<?php
session_start();
include("connect.php");

$sql = "SELECT text, amount, transaction_date 
        FROM expenses 
        WHERE user_id = ? 
        ORDER BY transaction_date DESC, created_at DESC 
        LIMIT 5";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();
$activities = $result->fetch_all(MYSQLI_ASSOC);

header('Content-Type: application/json');
echo json_encode($activities);
?>

<?php
include 'connect.php';
session_start();

// Verify admin access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit('Unauthorized access');
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : 'all';
$limit = 10;
$offset = ($page - 1) * $limit;

$sql = "SELECT id, first_name, last_name, student_id, email, role, signup_date 
        FROM users 
        WHERE (first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR student_id LIKE ?)";
$searchTerm = "%$search%";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode([
    'users' => $users,
    'currentPage' => $page,
    'totalPages' => ceil($result->num_rows / $limit)
]);

<?php
session_start();
include("connect.php");

if (!isset($_SESSION['user_name']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$limit = 10;
$offset = ($page - 1) * $limit;
 
$whereClause = $search ? "WHERE topics LIKE ? OR subtopics LIKE ?" : "";
$query = "SELECT * FROM modules $whereClause LIMIT ? OFFSET ?";
$countQuery = "SELECT COUNT(*) as total FROM modules $whereClause";

$stmt = $conn->prepare($query);
$countStmt = $conn->prepare($countQuery);

if ($search) {
    $searchParam = "%$search%";
    $stmt->bind_param("ssii", $searchParam, $searchParam, $limit, $offset);
    $countStmt->bind_param("ss", $searchParam, $searchParam);
} else {
    $stmt->bind_param("ii", $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();
$modules = $result->fetch_all(MYSQLI_ASSOC);

$countStmt->execute();
$totalModules = $countStmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalModules / $limit);

echo json_encode([
    'records' => $modules,
    'currentPage' => $page,
    'totalPages' => $totalPages
]);

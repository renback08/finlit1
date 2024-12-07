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

$query = "SELECT a.*, m.subtopics as module_name, 
          (SELECT COUNT(*) FROM assessment_questions WHERE assessment_id = a.assessment_id) as question_count 
          FROM assessments a 
          LEFT JOIN modules m ON a.module_id = m.module_id";

if ($search)  {
    $query .= " WHERE m.subtopics LIKE ?";
}

$query .= " LIMIT ? OFFSET ?";

$stmt = $conn->prepare($query);

if ($search) {
    $searchParam = "%$search%";
    $stmt->bind_param("sii", $searchParam, $limit, $offset);
} else {
    $stmt->bind_param("ii", $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();
$assessments = $result->fetch_all(MYSQLI_ASSOC);

$countQuery = "SELECT COUNT(*) as total FROM assessments";
$totalAssessments = $conn->query($countQuery)->fetch_assoc()['total'];
$totalPages = ceil($totalAssessments / $limit);

echo json_encode([
    'records' => $assessments,
    'currentPage' => $page,
    'totalPages' => $totalPages
]);

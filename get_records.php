<?php
session_start();
include("connect.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    exit(json_encode(['error' => 'Unauthorized']));
}
 
$type = $_GET['type'] ?? 'users';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$limit = 10;
$offset = ($page - 1) * $limit;

if ($type === 'users') {
    $searchCondition = "WHERE first_name LIKE '%$search%' 
                       OR last_name LIKE '%$search%' 
                       OR email LIKE '%$search%'
                       OR student_id LIKE '%$search%'";
                       
    $query = "SELECT * FROM users $searchCondition ORDER BY signup_date DESC LIMIT $offset, $limit";
    $countQuery = "SELECT COUNT(*) as total FROM users $searchCondition";
} else if ($type === 'assessments') {
             $searchCondition = "WHERE m.topics LIKE '%$search%' 
                                OR m.subtopics LIKE '%$search%'";
                       
             $query = "SELECT q.module_id, m.topics, m.subtopics, 
                       COUNT(q.question_id) as question_count,
                       MIN(m.created_at) as created_date
                       FROM questions q
                       JOIN modules m ON q.module_id = m.module_id
                       $searchCondition
                       GROUP BY q.module_id
                       ORDER BY m.created_at DESC 
                       LIMIT $offset, $limit";
              
             $countQuery = "SELECT COUNT(DISTINCT module_id) as total 
                            FROM questions q
                            JOIN modules m ON q.module_id = m.module_id
                            $searchCondition";
} else {
             $searchCondition = "WHERE topics LIKE '%$search%' 
                                OR subtopics LIKE '%$search%'";
                       
             $query = "SELECT * FROM modules $searchCondition ORDER BY created_at DESC LIMIT $offset, $limit";
             $countQuery = "SELECT COUNT(*) as total FROM modules $searchCondition";
}

$result = $conn->query($query);
$records = [];

if ($type === 'users') {
    while ($row = $result->fetch_assoc()) {
        $records[] = [
            'id' => $row['id'],
            'name' => $row['first_name'] . ' ' . $row['last_name'],
            'student_id' => $row['student_id'],
            'email' => $row['email'],
            'role' => $row['role'],
            'signup_date' => date('M d, Y', strtotime($row['signup_date']))
        ];
    }
} else {
    while ($row = $result->fetch_assoc()) {
        $records[] = [
            'id' => $row['module_id'],
            'topic' => $row['topics'],
            'subtopic' => $row['subtopics'],
            'created_date' => date('M d, Y', strtotime($row['created_at']))
        ];
    }
}

$totalResult = $conn->query($countQuery);
$total = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

echo json_encode([
    'records' => $records,
    'totalPages' => $totalPages,
    'currentPage' => $page
]);

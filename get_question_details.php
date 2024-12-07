<?php
session_start();
include("connect.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    exit(json_encode(['error' => 'Unauthorized']));
}

if (isset($_GET['module_id'])) {
    $module_id = $_GET['module_id'];
    
    $stmt = $conn->prepare("
        SELECT q.question_id, m.topics, m.subtopics,
               q.question_text, q.option_a, q.option_b, 
               q.option_c, q.option_d, q.correct_answer
        FROM questions q
        JOIN modules m ON q.module_id = m.module_id
        WHERE q.module_id = ?
        ORDER BY q.question_id
    ");
    
    $stmt->bind_param("i", $module_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $questions = [];
    while ($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
    
    echo json_encode($questions);
}

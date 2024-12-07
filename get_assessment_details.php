<?php
session_start();
include("connect.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    exit(json_encode(['error' => 'Unauthorized']));
}

if (isset($_GET['module_id'])) {
    $module_id = $_GET['module_id'];
    
    $stmt = $conn->prepare("
        SELECT m.topics, m.subtopics, m.created_at,
               q.question_id, q.question_text, 
               q.option_a, q.option_b, q.option_c, q.option_d, 
               q.correct_answer
        FROM modules m
        JOIN questions q ON m.module_id = q.module_id
        WHERE m.module_id = ?
    ");
    
    $stmt->bind_param("i", $module_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $assessment = [
        'module' => '',
        'created_date' => '',
        'questions' => []
    ];
    
    while ($row = $result->fetch_assoc()) {
        if (empty($assessment['module'])) {
            $assessment['module'] = $row['topics'] . ' - ' . $row['subtopics'];
            $assessment['created_date'] = $row['created_at'];
        }
        
        $assessment['questions'][] = [
            'id' => $row['question_id'],
            'text' => $row['question_text'],
            'options' => [
                'A' => $row['option_a'],
                'B' => $row['option_b'],
                'C' => $row['option_c'],
                'D' => $row['option_d']
            ],
            'correct_answer' => $row['correct_answer']
        ];
    }
    
    echo json_encode($assessment);
}

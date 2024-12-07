<?php
session_start();
include("connect.php");

// Verify admin privileges
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $module_id = $_POST['module_id'];
    $questions = $_POST['questions'];
    
    try {
        $conn->begin_transaction();
        
        // Validate module exists
        $moduleCheck = $conn->prepare("SELECT module_id FROM modules WHERE module_id = ?");
        $moduleCheck->bind_param("i", $module_id);
        $moduleCheck->execute();
        if (!$moduleCheck->get_result()->fetch_assoc()) {
            throw new Exception("Invalid module selected");
        }
        
        // Insert questions and update module has_assessment status
        $stmt = $conn->prepare("INSERT INTO questions (module_id, question_text, option_a, option_b, option_c, option_d, correct_answer) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        foreach ($questions as $question) {
            $stmt->bind_param("issssss", 
                $module_id,
                $question['text'],
                $question['option_a'],
                $question['option_b'],
                $question['option_c'],
                $question['option_d'],
                $question['correct_answer']
            );
            
            if (!$stmt->execute()) {
                throw new Exception("Error inserting question: " . $stmt->error);
            }
        }
        
        // Update module status
        $updateModule = $conn->prepare("UPDATE modules SET has_assessment = 1 WHERE module_id = ?");
        $updateModule->bind_param("i", $module_id);
        if (!$updateModule->execute()) {
            throw new Exception("Error updating module status: " . $updateModule->error);
        }
        
        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Assessment created successfully']);
        
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }}
?>

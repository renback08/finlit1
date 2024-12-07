<?php
session_start();
$conn = new mysqli("localhost", "root", "", "user_signup");

if (!isset($_SESSION['assessment_result']) || !isset($_GET['module_id'])) {
    header("Location: lobby.php");
    exit();
}

$result = $_SESSION['assessment_result'];
$module_id = $_GET['module_id'];
$user_id = $_SESSION['id'];

// Get module information
$module_sql = "SELECT subtopics FROM modules WHERE module_id = ?";
$module_stmt = $conn->prepare($module_sql);
$module_stmt->bind_param("i", $module_id);
$module_stmt->execute();
$module = $module_stmt->get_result()->fetch_assoc();

// Get questions and answers
$questions_sql = "SELECT q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, q.correct_answer, ua.user_answer 
                FROM questions q 
                LEFT JOIN user_answers ua ON q.question_id = ua.question_id 
                WHERE q.module_id = ? AND ua.user_id = ?";
$questions_stmt = $conn->prepare($questions_sql);
$questions_stmt->bind_param("ii", $module_id, $user_id);
$questions_stmt->execute();
$questions_result = $questions_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assessment Result</title>
    <link rel="stylesheet" href="assessment_result.css">
    <script src="assessment_result.js" defer></script>
</head>
<body>
    <div class="result-container">
        <h1><?php echo htmlspecialchars($module['subtopics']); ?></h1>
        <h2>Assessment Results</h2>
        
        <div class="score-display">
            <?php echo $result['score']; ?>/<?php echo $result['total']; ?>
        </div>
        
        <div class="percentage">
            <?php echo number_format($result['percentage'], 1); ?>%
        </div>
        
        <div class="message <?php echo $result['percentage'] >= 80 ? 'pass' : 'fail'; ?>">
            <?php if ($result['percentage'] >= 80): ?>
                Congratulations! You have passed the assessment.
            <?php else: ?>
                You need to score at least 80% to pass. Please try again.
            <?php endif; ?>
        </div>

        <button class="review-btn" onclick="toggleReview()">Review Answers</button>
        
        <div id="answersReview" class="answers-review" style="display: none;">
            <?php 
            $question_num = 1;
            while ($question = $questions_result->fetch_assoc()): 
            ?>
                <div class="review-question">
                    <h3>Question <?php echo $question_num; ?></h3>
                    <p><?php echo htmlspecialchars($question['question_text']); ?></p>
                    
                    <div class="review-options">
                        <div class="option <?php echo $question['user_answer'] === 'A' ? ($question['correct_answer'] === 'A' ? 'correct' : 'incorrect') : ($question['correct_answer'] === 'A' ? 'correct' : ''); ?>">
                            A: <?php echo htmlspecialchars($question['option_a']); ?>
                            <?php if ($question['user_answer'] === 'A'): ?>
                                <span class="user-answer">(Your Answer)</span>
                            <?php endif; ?>
                            <?php if ($question['correct_answer'] === 'A'): ?>
                                <span class="correct-answer">(Correct Answer)</span>
                            <?php endif; ?>
                        </div>
                        <div class="option <?php echo $question['user_answer'] === 'B' ? ($question['correct_answer'] === 'B' ? 'correct' : 'incorrect') : ($question['correct_answer'] === 'B' ? 'correct' : ''); ?>">
                            B: <?php echo htmlspecialchars($question['option_b']); ?>
                            <?php if ($question['user_answer'] === 'B'): ?>
                                <span class="user-answer">(Your Answer)</span>
                            <?php endif; ?>
                            <?php if ($question['correct_answer'] === 'B'): ?>
                                <span class="correct-answer">(Correct Answer)</span>
                            <?php endif; ?>
                        </div>
                        <div class="option <?php echo $question['user_answer'] === 'C' ? ($question['correct_answer'] === 'C' ? 'correct' : 'incorrect') : ($question['correct_answer'] === 'C' ? 'correct' : ''); ?>">
                            C: <?php echo htmlspecialchars($question['option_c']); ?>
                            <?php if ($question['user_answer'] === 'C'): ?>
                                <span class="user-answer">(Your Answer)</span>
                            <?php endif; ?>
                            <?php if ($question['correct_answer'] === 'C'): ?>
                                <span class="correct-answer">(Correct Answer)</span>
                            <?php endif; ?>
                        </div>
                        <div class="option <?php echo $question['user_answer'] === 'D' ? ($question['correct_answer'] === 'D' ? 'correct' : 'incorrect') : ($question['correct_answer'] === 'D' ? 'correct' : ''); ?>">
                            D: <?php echo htmlspecialchars($question['option_d']); ?>
                            <?php if ($question['user_answer'] === 'D'): ?>
                                <span class="user-answer">(Your Answer)</span>
                            <?php endif; ?>
                            <?php if ($question['correct_answer'] === 'D'): ?>
                                <span class="correct-answer">(Correct Answer)</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php 
            $question_num++;
            endwhile; 
            ?>
        </div>
        
        <div class="action-buttons">
            <?php if ($result['percentage'] < 80): ?>
                <a href="assessment.php?module_id=<?php echo $module_id; ?>" class="btn retry-btn">Retry Assessment</a>
            <?php endif; ?>
            <a href="lobby.php" class="btn home-btn">Back to Modules</a>
        </div>
    </div>
</body>
</html>

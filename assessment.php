<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$conn = new mysqli("localhost", "root", "", "user_signup");

if (!isset($_SESSION['id']) || !isset($_GET['module_id'])) {
    header("Location: lobby.php");
    exit();
}

$user_id = $_SESSION['id'];
$module_id = $_GET['module_id'];

// Get module information
$module_sql = "SELECT subtopics FROM modules WHERE module_id = ?";
$module_stmt = $conn->prepare($module_sql);
$module_stmt->bind_param("i", $module_id);
$module_stmt->execute();
$module_result = $module_stmt->get_result();
$module = $module_result->fetch_assoc();

// Get random questions
$questions_sql = "SELECT * FROM questions WHERE module_id = ? ORDER BY RAND() LIMIT 10";
$questions_stmt = $conn->prepare($questions_sql);
$questions_stmt->bind_param("i", $module_id);
$questions_stmt->execute();
$questions_result = $questions_stmt->get_result();

if (isset($_POST['submit_assessment'])) {
    $score = 0;
    $total_questions = 10;

    foreach ($_POST['answers'] as $question_id => $answer) {
        $check_sql = "SELECT correct_answer FROM questions WHERE question_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $question_id);
        $check_stmt->execute();
        $correct = $check_stmt->get_result()->fetch_assoc();

        if ($answer === $correct['correct_answer']) {
            $score++;
        }
    }

    // Save result
    $save_result = "INSERT INTO assessment_results (user_id, module_id, score) VALUES (?, ?, ?)";
    $save_stmt = $conn->prepare($save_result);
    $save_stmt->bind_param("iii", $user_id, $module_id, $score);
    $save_stmt->execute();

    $_SESSION['assessment_result'] = [
        'score' => $score,
        'total' => $total_questions,
        'percentage' => ($score / $total_questions) * 100
    ];

    header("Location: assessment_result.php?module_id=" . $module_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Module Assessment</title>
    <link rel="stylesheet" href="assessment.css">
    <script src="assessment.js"></script>
</head>
<body>
    <div class="assessment-container">
        <h1><?php echo htmlspecialchars($module['subtopics']); ?> - Assessment</h1>
        
        <form method="POST" action="" id="assessmentForm">
            <?php 
            $question_num = 1;
            while ($question = $questions_result->fetch_assoc()): 
            ?>
                <div class="question-box" id="question<?php echo $question_num; ?>" <?php echo $question_num > 1 ? 'style="display:none;"' : ''; ?>>
                    <p class="question-number">Question <?php echo $question_num; ?> of 10</p>
                    <p class="question-text"><?php echo htmlspecialchars($question['question_text']); ?></p>
                    
                    <div class="options">
                        <label>
                            <input type="radio" name="answers[<?php echo $question['question_id']; ?>]" value="A" required>
                            <?php echo htmlspecialchars($question['option_a']); ?>
                        </label>
                        <label>
                            <input type="radio" name="answers[<?php echo $question['question_id']; ?>]" value="B">
                            <?php echo htmlspecialchars($question['option_b']); ?>
                        </label>
                        <label>
                            <input type="radio" name="answers[<?php echo $question['question_id']; ?>]" value="C">
                            <?php echo htmlspecialchars($question['option_c']); ?>
                        </label>
                        <label>
                            <input type="radio" name="answers[<?php echo $question['question_id']; ?>]" value="D">
                            <?php echo htmlspecialchars($question['option_d']); ?>
                        </label>
                    </div>

                    <div class="navigation-buttons">
                        <?php if ($question_num > 1): ?>
                            <button type="button" class="nav-btn prev-btn" onclick="showPreviousQuestion(<?php echo $question_num; ?>)">Previous</button>
                        <?php endif; ?>
                        
                        <?php if ($question_num < 10): ?>
                            <button type="button" class="nav-btn next-btn" onclick="showNextQuestion(<?php echo $question_num; ?>)">Next</button>
                        <?php else: ?>
                            <button type="submit" name="submit_assessment" class="submit-btn">Submit Assessment</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php 
            $question_num++;
            endwhile; 
            ?>
        </form>
    </div>
</body>
</html>

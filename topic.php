<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$conn = new mysqli("localhost", "root", "", "user_signup");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id']) && isset($_SESSION['id'])) {
    $subtopic_id = $_GET['id'];
    $user_id = $_SESSION['id'];
    
    // If content parameter is not set, check for saved progress
    if (!isset($_GET['content'])) {
        $progress_sql = "SELECT current_page FROM module_progress WHERE user_id = ? AND module_id = ?";
        $progress_stmt = $conn->prepare($progress_sql);
        $progress_stmt->bind_param("ii", $user_id, $subtopic_id);
        $progress_stmt->execute();
        $progress_result = $progress_stmt->get_result();
        
        if ($progress_result->num_rows > 0) {
            $progress = $progress_result->fetch_assoc();
            $current_content = $progress['current_page'];
        } else {
            $current_content = 1;
        }
    } else {
        $current_content = $_GET['content'];
    }
    
    $content_field = "content_" . $current_content;
    
    // Save progress
    $check_progress = "SELECT progress_id FROM module_progress WHERE user_id = ? AND module_id = ?";
    $check_stmt = $conn->prepare($check_progress);
    $check_stmt->bind_param("ii", $user_id, $subtopic_id);
    $check_stmt->execute();
    $existing_progress = $check_stmt->get_result();

    if ($existing_progress->num_rows > 0) {
        // Update existing progress
        $update_sql = "UPDATE module_progress SET current_page = ?, updated_at = CURRENT_TIMESTAMP WHERE user_id = ? AND module_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("iii", $current_content, $user_id, $subtopic_id);
        $update_stmt->execute();
    } else {
        // Create new progress record
        $insert_sql = "INSERT INTO module_progress (user_id, module_id, current_page) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("iii", $user_id, $subtopic_id, $current_content);
        $insert_stmt->execute();
    }

    // Get module content
    $sql = "SELECT module_id, topics, subtopics, description, content_1, content_2, content_3, content_4, content_5 
            FROM modules WHERE module_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $subtopic_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $topic = $result->fetch_assoc();
    
    // Mark as complete if reached last page
    if ($current_content == 5) {
        $complete_sql = "UPDATE module_progress SET is_complete = 1 WHERE user_id = ? AND module_id = ?";
        $complete_stmt = $conn->prepare($complete_sql);
        $complete_stmt->bind_param("ii", $user_id, $subtopic_id);
        $complete_stmt->execute();
    }
} else {
    header("Location: lobby.php#moduleAssessmentContent");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($topic['subtopics']) ? htmlspecialchars($topic['subtopics']) : 'Topic'; ?></title>
    <link rel="stylesheet" href="topic.css">
</head>
<body>
    <div class="navigation">
        <button class="back-btn" onclick="window.location.href='lobby.php#moduleAssessmentContent'">← Back to Learning Modules</button>
        <div class="topic-info">
            <span class="topic-category"><?php echo htmlspecialchars($topic['topics']); ?></span>
            <span class="progress-percentage"><?php echo $current_content * 20; ?>% Complete</span>
            <span class="page-indicator">Page <?php echo $current_content; ?> of 5</span>
        </div>
    </div>
    <div class="topic-container">
        <div class="content-box">
            <h1 class="topic-title"><?php echo isset($topic['subtopics']) ? htmlspecialchars($topic['subtopics']) : ''; ?></h1>
            
            <?php if ($current_content == 1): ?>
            <div class="description-section">
                <h2>Overview</h2>
                <p><?php echo isset($topic['description']) ? htmlspecialchars($topic['description']) : ''; ?></p>
            </div>
            <?php endif; ?>
            
            <div class="content-section">
                <div class="module-content">
                    <?php echo isset($topic[$content_field]) ? $topic[$content_field] : ''; ?>
                </div>
            </div>
        </div>
        
        <div class="navigation-bottom">
            <?php if ($current_content > 1): ?>
                <a href="?id=<?php echo $subtopic_id; ?>&content=<?php echo $current_content - 1; ?>" class="nav-btn prev-btn">← Previous</a>
            <?php endif; ?>
            
            <?php if ($current_content < 5 && !empty($topic['content_'.($current_content + 1)])): ?>
                <a href="?id=<?php echo $subtopic_id; ?>&content=<?php echo $current_content + 1; ?>" class="nav-btn next-btn">Next →</a>
            <?php elseif ($current_content == 5 || empty($topic['content_'.($current_content + 1)])): ?>
                <a href="assessment.php?module_id=<?php echo $subtopic_id; ?>" class="nav-btn assessment-btn">Take Assessment</a>
            <?php endif; ?>
        </div>
    </div>

    <script>
        const topicId = <?php echo $subtopic_id; ?>;
        const currentContent = <?php echo $current_content; ?>;
    </script>
    <script src="topic.js"></script>
</body>
</html>

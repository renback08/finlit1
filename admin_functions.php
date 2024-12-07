<?php
session_start();
include("connect.php");

// Check admin authentication
function checkAdminAuth() {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Unauthorized access']);
        exit();
    }
}

// Get user statistics
function getUserStats($conn) {
    $stats = [];
    
    // Total users
    $query = "SELECT COUNT(*) as total FROM users";
    $result = $conn->query($query);
    $stats['totalUsers'] = $result->fetch_assoc()['total'];
    
    // Active users (users who logged in last 30 days)
    $query = "SELECT COUNT(*) as active FROM users WHERE signup_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
    $result = $conn->query($query);
    $stats['activeUsers'] = $result->fetch_assoc()['active'];
    
    return $stats;
}

// Get module statistics
function getModuleStats($conn) {
    $stats = [];
    
    // Total modules
    $query = "SELECT COUNT(*) as total FROM modules";
    $result = $conn->query($query);
    $stats['totalModules'] = $result->fetch_assoc()['total'];
    
    // Module completion stats
    $query = "SELECT COUNT(*) as completed FROM module_progress WHERE is_complete = 1";
    $result = $conn->query($query);
    $stats['completedModules'] = $result->fetch_assoc()['completed'];
    
    return $stats;
}

  if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
      exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
  }

  $action = $_POST['action'] ?? $_GET['action'] ?? '';

  switch ($action) {
      case 'bulkDelete':
          $data = json_decode(file_get_contents('php://input'), true);
          $userIds = $data['userIds'];
          $ids = implode(',', array_map('intval', $userIds));
        
          $sql = "DELETE FROM users WHERE id IN ($ids)";
          $success = $conn->query($sql);
        
          echo json_encode(['success' => $success]);
          break;

      case 'exportUsers':
          $data = json_decode(file_get_contents('php://input'), true);
          $userIds = $data['userIds'];
          $ids = implode(',', array_map('intval', $userIds));
        
          $sql = "SELECT * FROM users WHERE id IN ($ids)";
          $result = $conn->query($sql);
        
          header('Content-Type: text/csv');
          header('Content-Disposition: attachment; filename="users_export.csv"');
        
          $output = fopen('php://output', 'w');
          fputcsv($output, ['ID', 'First Name', 'Last Name', 'Student ID', 'Email', 'Role', 'Signup Date']);
        
          while ($row = $result->fetch_assoc()) {
              fputcsv($output, $row);
          }
          break;

      case 'getUser':
          $id = (int)$_GET['id'];
          $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
          $stmt->bind_param('i', $id);
          $stmt->execute();
          $result = $stmt->get_result();
          echo json_encode($result->fetch_assoc());
          break;

      case 'getUserStats':
          echo json_encode(getUserStats($conn));
          break;
        
      case 'getModuleStats':
          echo json_encode(getModuleStats($conn));
          break;
        
      case 'createUser':
          $result = createUser($conn, $_POST);
          echo json_encode(['success' => $result]);
          break;

      case 'createModule':
          $result = createModule($conn, $_POST);
          echo json_encode(['success' => $result]);
          break;
        
      default:
          http_response_code(400);
          echo json_encode(['error' => 'Invalid action']);
  }
function createUser($conn, $data) {
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, student_id, role, password) VALUES (?, ?, ?, ?, ?, ?)");
    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
    $stmt->bind_param("ssssss", $data['first_name'], $data['last_name'], $data['email'], $data['student_id'], $data['role'], $hashedPassword);
    return $stmt->execute();
}

function createModule($conn, $data) {
    $stmt = $conn->prepare("INSERT INTO modules (topics, description, content_1) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $data['module_name'], $data['description'], $data['content']);
    return $stmt->execute();
}
function getCreationStats($conn) {
    $stats = [
        'users' => 0,
        'modules' => 0,
        'assessments' => 0
    ];

    // Get user count
    $query = "SELECT COUNT(*) as count FROM users";
    $result = $conn->query($query);
    $stats['users'] = $result->fetch_assoc()['count'];

    // Get module count
    $query = "SELECT COUNT(*) as count FROM modules";
    $result = $conn->query($query);
    $stats['modules'] = $result->fetch_assoc()['count'];

    return $stats;
}

// Add this to your existing POST handler
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'getStats') {
        echo json_encode(getCreationStats($conn));
    }
}
$conn->close();
function getRecords($conn, $type, $page = 1, $limit = 10, $search = '', $filter = '') {
    $offset = ($page - 1) * $limit;
    $records = [];
    
    switch($type) {
        case 'users':
            $query = "SELECT id, CONCAT(first_name, ' ', last_name) as name, email, role, signup_date 
                     FROM users 
                     WHERE (first_name LIKE ? OR last_name LIKE ? OR email LIKE ?)
                     ORDER BY signup_date DESC 
                     LIMIT ? OFFSET ?";
            $search = "%$search%";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssii", $search, $search, $search, $limit, $offset);
            break;
            
        case 'modules':
            $query = "SELECT module_id, topics, subtopics, created_at 
                     FROM modules 
                     WHERE topics LIKE ? 
                     ORDER BY created_at DESC 
                     LIMIT ? OFFSET ?";
            $search = "%$search%";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sii", $search, $limit, $offset);
            break;
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
        $records[] = $row;
    }
    
    return $records;
}

// Get single record
function getRecord($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Update record
function updateRecord($conn, $data) {
    $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, role = ? WHERE id = ?");
    $stmt->bind_param("ssssi", 
        $data['first_name'],
        $data['last_name'],
        $data['email'],
        $data['role'],
        $data['user_id']
    );
    return $stmt->execute();
}

// Handle requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'updateRecord') {
        $result = updateRecord($conn, $_POST);
        echo json_encode(['success' => $result]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($_GET['action'] === 'getRecord') {
        $record = getRecord($conn, $_GET['id']);
        echo json_encode($record);
    }
}

// Delete record
function deleteRecord($conn, $id, $softDelete = false) {
    if ($softDelete) {
        $stmt = $conn->prepare("UPDATE users SET is_active = 0 WHERE id = ?");
    } else {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    }
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// Bulk delete records
function bulkDelete($conn, $ids, $softDelete = false) {
    $ids = json_decode($ids);
    $success = true;
    
    foreach ($ids as $id) {
        $result = deleteRecord($conn, $id, $softDelete);
        if (!$result) {
            $success = false;
        }
    }
    
    return $success;
}

// Handle delete requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['action']) {
        case 'deleteRecord':
            $result = deleteRecord($conn, $_POST['id'], $_POST['softDelete'] === 'true');
            echo json_encode(['success' => $result]);
            break;
            
        case 'bulkDelete':
            $result = bulkDelete($conn, $_POST['ids'], $_POST['softDelete'] === 'true');
            echo json_encode(['success' => $result]);
            break;
    }
}

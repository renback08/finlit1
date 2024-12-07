<?php
/**
 * Includes the database connection file and starts a new session.
 */

include 'connect.php';
session_start();

// Function to handle signup
function handleSignup($conn) {
    if (isset($_POST['first_name'], $_POST['last_name'], $_POST['sex'], $_POST['student_id'], $_POST['email'], $_POST['password'])) {
        $email = validateInput($_POST['email']);
        
        // Check if email exists before query
        if (!empty($email)) {
            $emailCheck = "SELECT email FROM users WHERE email = ?";
            $stmt = $conn->prepare($emailCheck);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                return "<div class='alert alert-danger'>Email is already registered</div>";
            }
        } else {
            return "<div class='alert alert-danger'>Email is required</div>";
        }
        
        $first_name = validateInput($_POST['first_name']);
        $last_name = validateInput($_POST['last_name']);
        $sex = validateInput($_POST['sex']);
        $student_id = validateInput($_POST['student_id']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (first_name, last_name, sex, student_id, email, password) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $first_name, $last_name, $sex, $student_id, $email, $password);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            return "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
        }
    } else {
        return "<div class='alert alert-danger'>Please fill in all required fields.</div>";
    }
}
function handleLogin($conn) {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = trim($conn->real_escape_string($_POST['email']));
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Store user information in session
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['student_id'] = $user['student_id'];
                $_SESSION['sex'] = $user['sex'];
                $_SESSION['signup_date'] = $user['signup_date'];
                $_SESSION['id'] = $user['id'];
                $_SESSION['phone_number'] = $user['phone_number'];
                $_SESSION['profile_picture'] = $user['profile_picture'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['login_time'] = time();

                // Role-based redirect
                if ($user['role'] === 'admin') {
                    header("Location: admin.php");
                } else {
                    header("Location: lobby.php");
                }
                exit();
            }
        }
        echo "<p style='color: red;'>Invalid email or password</p>";
    }
}
// Add input validation
function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Add password strength validation
function validatePassword($password) {
    return strlen($password) >= 8 && 
           preg_match('/[A-Z]/', $password) && 
           preg_match('/[a-z]/', $password) && 
           preg_match('/[0-9]/', $password);
}

// Add student ID validation
function validateStudentID($student_id) {
    return preg_match('/^\d{4}-\d{5}$/', $student_id);
}

$emailCheck = "SELECT email FROM users WHERE email = '$email'";
$result = $conn->query($emailCheck);
if ($result->num_rows > 0) {
    echo "<p style='color: red;'>Email is already registered. Please use another email.</p>";
    return;
}

if (isset($_POST['rememberMe'])) {
    setcookie("email", $_POST['email'], time() + (86400 * 30), "/");
} else {
    if (isset($_COOKIE['email'])) {
        setcookie("email", "", time() - 3600, "/");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['signup'])) {
        handleSignup($conn);
    } elseif (isset($_POST['login'])) {
        handleLogin($conn);
    }
}

$conn->close();
?>
 
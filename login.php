<?php
session_start();
include("connect.php");
// Add brute force protection
function checkLoginAttempts($email) {
    global $conn;
    $timeWindow = 15 * 60; // 15 minutes
    $maxAttempts = 5;
    
    $sql = "SELECT COUNT(*) FROM login_attempts WHERE email = ? AND timestamp > (NOW() - INTERVAL ? SECOND)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $email, $timeWindow);
    $stmt->execute();
    $count = $stmt->get_result()->fetch_row()[0];
    
    return $count < $maxAttempts;
}

// Add session timeout
function checkSessionTimeout() {
    $timeout = 30 * 60; // 30 minutes
    if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > $timeout)) {
        session_destroy();
        header("Location: login.php?timeout=1");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

















    if (!isset($_POST['email']) || !isset($_POST['password'])) {
        $error = "<div class='alert alert-danger'>Please provide both email and password</div>";
    } else {
        $email = validateInput($_POST['email']);
        $password = $_POST['password'];
        
        if (empty($email) || empty($password)) {
            $error = "<div class='alert alert-danger'>Email and password cannot be empty</div>";
        } else {
            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            



            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['login_time'] = time();
                    
                    // Role-based redirect
                    if ($user['role'] === 'admin') {
                        header("Location: admin.php");
                    } else {
                        header("Location: lobby.php");
                    }
                    exit();
                } else {
                    $error = "<div class='alert alert-danger'>Invalid email or password</div>";
                }
            } else {

                $error = "<div class='alert alert-danger'>Invalid email or password</div>";
            }

        }

    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="wrapper">
        <div class="title-text">
            <div class="title login active">Login Form</div>
            <div class="title signup">Signup Form</div>
        </div>
        <div class="form-container">
            <div class="form-inner">
                <form action="register.php" method="POST" class="login">
    <input type="hidden" name="login" value="true">
    <div class="field-container">
        <div class="field">
            <input type="text" name="email" placeholder="Email Address" required id="loginEmail" value="<?php echo isset($_COOKIE['email']) ? $_COOKIE['email'] : ''; ?>">
            <small class="error-message" id="emailError"></small>
        </div>
    </div>
    
    <div class="field-container">
        <div class="field">
            <input type="password" name="password" placeholder="Password" required id="loginPassword">
            <small class="error-message" id="passwordError"></small>
        </div>
    </div>
    <div class="field remember-me">
        <input type="checkbox" name="rememberMe" id="rememberMe">
        <label for="rememberMe">Remember Me</label>
    </div>
    <div class="pass-link"><a href="#">Forgot password?</a></div>
    <div class="field">
        <input type="submit" value="Login">
    </div>
    <div class="signup-link">Not a Member? <a href="signup.php">Signup Now</a></div>
</form>
            </div>
        </div>
    </div>
    <script src="login.js"></script> <!-- Separated JS file -->
</body>
</html>


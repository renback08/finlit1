<?php
/**
 * This code is the main profile page for the Financial Information Management System (FINLIT) application. It handles the display of the user's profile information, including their name, email, student ID, sex, signup date, phone number, and login duration.
 * 
 * The code first checks if the user is logged in, and if not, redirects them to the login page. It then retrieves the user's session data and sets default values if any of the data is not available.
 * 
 * The profile page includes a form for uploading a profile picture, as well as a form for updating the user's phone number. The page also provides buttons for editing the user's profile and changing their password.
 * 
 * The code also includes JavaScript functionality to toggle the visibility of the profile content section, as well as the sidebar and settings dropdown menu.
 */

session_start();
include("connect.php");

if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

// Fetching session data and setting a default profile picture
$user_name = $_SESSION['user_name'];
$email = isset($_SESSION['email']) ? $_SESSION['email'] : 'Not set';
$student_id = isset($_SESSION['student_id']) ? $_SESSION['student_id'] : 'Not set';
$sex = isset($_SESSION['sex']) ? $_SESSION['sex'] : 'Not set';
$signup_date = isset($_SESSION['signup_date']) ? $_SESSION['signup_date'] : 'Not set';
$id = isset($_SESSION['id']) ? $_SESSION['id'] : 'Not set';
$phone_number = isset($_SESSION['phone_number']) ? $_SESSION['phone_number'] : 'Not set';

// Access profile picture or set to default
$profile_picture = isset($_SESSION['profile_picture']) && file_exists($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : 'path/to/default.jpg';

// Calculate login duration
$log_in_duration = isset($_SESSION['login_time']) ? time() - $_SESSION['login_time'] : 0; // Duration in seconds
$log_in_duration_formatted = gmdate("H:i:s", $log_in_duration); // Format to hours, minutes, seconds
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Information Management System</title>
    <link rel="stylesheet" href="lobby.css">
</head>
<body>
<header>
    <div class="header-left">
        <button id="menuButton" class="menu-btn">☰</button>
        <img src="logo.png.png" alt="Logo" class="logo">
        <span class="title">FINLIT PATH: FINANCIAL INFORMATION MANAGEMENT SYSTEM</span>
    </div>
    <div> <a href="lobby.php" class="home-link">Home</a></div>

    
    <div class="dropdown">
            <button id="settingsButton" class="icon-btn">⚙️</button>
            <div id="settingsDropdown" class="dropdown-content">
            <button id="profileButton" a href ="profile.php" class="icon-btn" >Profile</button>
                <button id="themeToggle">Light/Dark Mode</button>
                <button id="logoutButton">Log Out</button>
            </div>
        </div>
    <div class="header-right">
    <button id="profileButton" class="icon-btn profile-button" title="View Profile">
        <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="profile-pic">
    </button>
</a>
</div>

<div><span class="user-role">STUDENT
    <span class="welcome-message">Welcome, <?php echo htmlspecialchars($user_name); ?>!</span>
            </span>
        </div>
</header>
<main>
    <div class="sidebar">
        <button class="sidebar-item active">Dashboard</button>
        <button class="sidebar-item" id="expenseTrackerButton">Real-Time Expense Tracker</button>
        <button class="sidebar-item has-submenu" id="assessmentButton">Assessment</button>
        
        <div class="submenu">
            <button class="sidebar-item sub-item">Module 1</button>
            <button class="sidebar-item sub-item">Module 2</button>
        </div>
        
        <button class="sidebar-item">News</button>
    </div>
    <div class="main-content" id="contentArea">
    <section class="content" id="profileContent" style="display: none;">
    <h2>Profile Information</h2>
    <div id="upload-status" class="popup-message" style="display: none;"></div>
    <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="profile-pic-large">
    <form id="profile-form" action="upload.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="profile_picture" id="profile_picture" accept="image/jpeg, image/png" required>
        <button type="submit">Upload</button>
    </form>
    <div id="upload-status" style="display: none;"></div>   
    <p><strong>Name:</strong> <?php echo htmlspecialchars($user_name); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
    <p><strong>Student ID:</strong> <?php echo htmlspecialchars($student_id); ?></p>
    <p><strong>Sex:</strong> <?php echo htmlspecialchars($sex); ?></p>
    <p><strong>Signup Date:</strong> <?php echo htmlspecialchars($signup_date); ?></p>
    <p><strong>Phone Number:</strong> <span id="phone-number"><?php echo htmlspecialchars($phone_number); ?></span></p>
    
    
    <!-- Phone number update form -->
<form id="phone-number-form" action="update_phone.php" method="POST">
    <label for="phone_number">Phone Number:</label>
    <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>" required>
    <button type="submit">Update Phone Number</button>
</form>
<div id="phone-update-status" class="popup-message" style="display: none;"></div>

    
    <p><strong>Log in Duration:</strong> <?php echo htmlspecialchars($log_in_duration_formatted); ?></p>
    <button onclick="location.href='edit_profile.php'">Edit Profile</button>
    <button onclick="location.href='change_password.php'">Change Password</button>
</section>
    </div>
</main>
<footer>
   
</footer>
<script src="lobby.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show the profile content section on initial load
        document.getElementById('profileContent').style.display = 'block';
        
        // Hide the content area initially
        document.getElementById('contentArea').style.display = 'block'; // Ensure the content area is visible

        // Menu button functionality to toggle sidebar if required
        const menuButton = document.getElementById('menuButton');
        const sidebar = document.querySelector('.sidebar');

        menuButton.addEventListener('click', function() {
            sidebar.classList.toggle('active'); // Toggle the sidebar visibility
        });

        // Profile button click event to toggle profile content
        const profileButton = document.getElementById('profileButton');
        profileButton.addEventListener('click', function() {
            const profileContent = document.getElementById('profileContent');
            profileContent.style.display = profileContent.style.display === 'none' ? 'block' : 'none';
        });

        // Settings button dropdown functionality
        const settingsButton = document.getElementById('settingsButton');
        const settingsDropdown = document.getElementById('settingsDropdown');

        settingsButton.addEventListener('click', function() {
            settingsDropdown.style.display = settingsDropdown.style.display === 'block' ? 'none' : 'block';
        });

        // Logout button functionality
        const logoutButton = document.getElementById('logoutButton');
        logoutButton.addEventListener('click', function() {
            window.location.href = 'logout.php'; // Redirect to logout
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
    // Show the profile content section on initial load
    document.getElementById('profileContent').style.display = 'block';
    
    // Hide the content area initially
    document.getElementById('contentArea').style.display = 'block'; // Ensure the content area is visible

    // Menu button functionality to toggle sidebar if required
    const menuButton = document.getElementById('menuButton');
    const sidebar = document.querySelector('.sidebar');

    menuButton.addEventListener('click', function() {
        sidebar.classList.toggle('active'); // Toggle the sidebar visibility
    });

    // Profile button click event to toggle profile content
    const profileButton = document.getElementById('profileButton');
    profileButton.addEventListener('click', function() {
        const profileContent = document.getElementById('profileContent');
        profileContent.style.display = profileContent.style.display === 'none' ? 'block' : 'none';
    });

    // Settings button dropdown functionality
    const settingsButton = document.getElementById('settingsButton');
    const settingsDropdown = document.getElementById('settingsDropdown');

    settingsButton.addEventListener('click', function() {
        settingsDropdown.style.display = settingsDropdown.style.display === 'block' ? 'none' : 'block';
    });

    // Logout button functionality
    const logoutButton = document.getElementById('logoutButton');
    logoutButton.addEventListener('click', function() {
        window.location.href = 'logout.php'; // Redirect to logout
    });

    // Real-Time Expense Tracker button functionality
    const expenseTrackerButton = document.getElementById('expenseTrackerButton');
    expenseTrackerButton.addEventListener('click', function() {
        // Show expense tracker content in lobby.php
        const expenseTrackerContent = document.getElementById('expenseTrackerContent');
        expenseTrackerContent.style.display = 'block';

        // Scroll smoothly to the expense tracker section
        expenseTrackerContent.scrollIntoView({ behavior: 'smooth' });
    });
});
expenseTrackerButton.addEventListener('click', function() {
    // Redirect to lobby.php with a hash to the section
    window.location.href = 'lobby.php#expenseTrackerContent';
});

</script>
</body>
</html>

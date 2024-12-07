<?php
/**
 * Starts a new session or resumes an existing one.
 * This is the entry point for the application and ensures the user is logged in before proceeding.
 */
session_start();
$_SESSION['loggedin'] = true;
include("connect.php");

    if (!isset($_SESSION['user_name'])) {
        header("Location: login.php");
        exit();
    }

    // Fetching session data and setting a default profile picture
    $user_name = $_SESSION['user_name'];

    // Access profile picture or set to default
    $profile_picture = isset($_SESSION['profile_picture']) && file_exists($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : 'path/to/default.jpg';

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
            <span class="title">FINLIT PATH: FINANCIAL INFORMATION MANAGEMENT SYSTEM</span>
        </div>
        <div class="header-right">
        <span class="welcome-message">Welcome, <?php echo htmlspecialchars($user_name); ?>!</span>
        <a href="profile.php">
        <button id="profileButton" class="icon-btn profile-button" title="View Profile">
            <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="profile-pic">
        </button>
    </a>
    </div>

            <div class="dropdown">
                <button id="settingsButton" class="icon-btn">⚙️</button>
                <div id="settingsDropdown" class="dropdown-content">
                <button id="profileButton" a href ="profile.php" class="icon-btn" >Profile</button>
                    <button id="themeToggle">Light/Dark Mode</button>
                    <button id="logoutButton">Log Out</button>
                </div>
            </div>
            <span class="user-role">STUDENT</span>
        </div>
    </header>
    <main>
    <div class="sidebar">
        <button class="sidebar-item active">Dashboard</button>
        <button class="sidebar-item" onclick="location.href='expense_tracker.php'">Real-Time Expense Tracker</button>
        <button class="sidebar-item has-submenu" id="assessmentButton">Assessments</button>
        <div class="submenu">
            <button class="sidebar-item sub-item">Module 1</button>
            <button class="sidebar-item sub-item">Module 2</button>
        </div>
        <button class="sidebar-item">Libraries</button>
        <button class="sidebar-item">News and Updates</button>
    </div>
        <div class="main-content" id="contentArea">
            <section class="content" id="expenseTrackerContent" style="display: none;">
                <h2>Expense Tracker</h2>
                <div class="expense-tracker-container">
                    <div class="left-column">
                        <h4>Your Balance</h4>
                        <h1 id="balance">₱0.00</h1>
                        <div class="inc-exp-container">
                            <div>
                                <h4>Income</h4>
                                <p id="money-plus" class="money-plus">+₱0.00</p>
                            </div>
                            <div>
                                <h4>Expense</h4>
                                <p id="money-minus" class="money-minus">-₱0.00</p>
                            </div>
                        </div>
                        <h3>Add New Transaction</h3>
                        <form id="form">
                            <div class="form-control">
                                <label for="text">Text</label>
                                <input type="text" id="text" placeholder="Enter Text...." pattern="[A-Za-z\s]+" required />
                            </div>
                            <div class="form-control">
                                <label for="amount">Amount</label>
                                <input type="number" id="amount" placeholder="Enter amount..." step="0.01" min="0" required>
                                <div class="button-container">
                                    <button type="button" id="income-btn" class="type-btn income-btn">Income</button>
                                    <button type="button" id="expense-btn" class="type-btn expense-btn">Expense</button>
                                </div>
                            </div>
                            <button type="submit" class="btn">Add transaction</button>
                        </form>
                    </div>
                    <div class="right-column">
                        <h3>History</h3>
                        <div class="history-container">
                            <div class="history-column">
                                <h4>Income</h4>
                                <ul id="income-list" class="list"></ul>
                            </div>
                            <div class="history-column">
                                <h4>Expenses</h4>
                                <ul id="expense-list" class="list"></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <footer>
        <button class="footer-item">Home</button>
        <button class="footer-item">Status</button>
        <button class="footer-item">Notification</button>
        <button class="footer-item">More</button>
    </footer>
    <script src="lobby.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Hide all other content and show the expense tracker
        document.getElementById('expenseTrackerContent').style.display = 'block';
        // Optionally hide other sections if you have them
        // Example: document.getElementById('otherSectionId').style.display = 'none';
    });
</script>

    </body>
    </html>

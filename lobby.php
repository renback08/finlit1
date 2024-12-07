<?php

session_start();

include("connect.php");

if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}


// Secure the session by regenerating session ID after login
session_regenerate_id(true);

// Fetching session data and setting a default profile picture
$user_name = $_SESSION['user_name'];
$email = isset($_SESSION['email']) ? $_SESSION['email'] : 'Not set';
$student_id = isset($_SESSION['student_id']) ? $_SESSION['student_id'] : 'Not set';
$sex = isset($_SESSION['sex']) ? $_SESSION['sex'] : 'Not set';
$signup_date = isset($_SESSION['signup_date']) ? $_SESSION['signup_date'] : 'Not set';
$id = isset($_SESSION['id']) ? $_SESSION['id'] : 'Not set';
$phone_number = isset($_SESSION['phone_number']) ? $_SESSION['phone_number'] : 'Not set';

$profile_picture = isset($_SESSION['profile_picture']) && file_exists($_SESSION['profile_picture']) 
    ? $_SESSION['profile_picture'] 
    : 'path/to/default.jpg';

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
    <!-- Add this before closing head tag -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-zoom/1.2.1/chartjs-plugin-zoom.min.js"></script>
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
                <button form="profile-form" onclick="location.href='profile.php'">Profile</button>
                    <button id="themeToggle">Light/Dark Mode</button>
                    <button id="logoutButton">Log Out</button>
                </div>
            </div>
            

    <div class="header-right">
    <a href="profile.php">
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
        <button class="sidebar-item active" id="dashboardButton">Dashboard</button>
        <button class="sidebar-item" id="expenseTrackerButton">Expense Tracker</button>
        <button class="sidebar-item" id="moduleAssessmentButton">Assessment</button>
        <div class="sidebar-item" id="newsUpdatesButton">News </div>
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
    <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>" pattern="\d{10,11}" title="Enter a valid phone number (10-11 digits)" required>

    <button type="submit">Update Phone Number</button>
</form>
<div id="phone-update-status" class="popup-message" style="display: none;"></div>

    
    <p><strong>Log in Duration:</strong> <?php echo htmlspecialchars($log_in_duration_formatted); ?></p>
    <button onclick="location.href='edit_profile.php'">Edit Profile</button>
    <button onclick="location.href='change_password.php'">Change Password</button>
</section>

<div class="main-content" id="contentArea">
    <!-- Dashboard Section -->
    <section class="content" id="dashboardContent" style="display: none;">
        <h2>Dashboard</h2>
        <div class="dashboard-container">
    <div class="dashboard-top">
        <div class="overview">
            <h3>Overview</h3>
            <select id="balanceRangeSelector" onchange="updateBalanceView()">
                <option value="total" selected>Total</option>
                <option value="weekly">Weekly Average</option>
                <option value="monthly">Monthly Average</option>
                <option value="annually">Annual Average</option>
            </select>
            
            <div class="overview-grid">
                <div class="overview-item">
                    <h4>Total Balance</h4>
                    <p id="dashboard-balance">₱<?php
                        $sql = "SELECT SUM(amount) as total FROM expenses WHERE user_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $_SESSION['id']);
                        $stmt->execute();
                        $result = $stmt->get_result()->fetch_assoc();
                        echo number_format($result['total'] ?? 0, 2);
                    ?></p>
                </div>

                <div class="overview-item">
                    <h4>Total Savings</h4>
                    <p id="total-income">₱<?php
                        $sql = "SELECT SUM(amount) as income FROM expenses WHERE user_id = ? AND amount >= 0";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $_SESSION['id']);
                        $stmt->execute();
                        $result = $stmt->get_result()->fetch_assoc();
                        echo number_format($result['income'] ?? 0, 2);
                    ?></p>
                </div>

                <div class="overview-item">
                    <h4>Total Expense</h4>
                    <p id="total-expense">₱<?php
                        $sql = "SELECT ABS(SUM(amount)) as expense FROM expenses WHERE user_id = ? AND amount < 0";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $_SESSION['id']);
                        $stmt->execute();
                        $result = $stmt->get_result()->fetch_assoc();
                        echo number_format($result['expense'] ?? 0, 2);
                    ?></p>
                </div>

                

            </div>
        </div>
    </div>
</div>

              <!-- Statistics Section -->
              <div class="statistics">
    <h3>Financial Analytics Dashboard</h3>
    <div class="chart-controls">
        <select id="timeRangeSelector" class="time-range-select">
            <option value="daily">Last 7 Days</option>
            <option value="weekly">Last 4 Weeks</option>
         <option value="monthly">Last 12 Months</option>
            <option value="annually">Yearly Overview</option>
        </select>
        <div class="chart-legend">
            <div class="legend-item">
                <span class="legend-color income"></span>Savings
            </div>
            <div class="legend-item">
                <span class="legend-color expense"></span>Expense
            </div>
        </div>
    </div>
    <div class="chart-container">
        <canvas id="balance-trend-chart"></canvas>
    </div>
    <div class="statistics-summary">
        <div class="summary-card">
            <h4>Average Savings</h4>
            <p id="avg-income">₱0.00</p>
        </div>
        <div class="summary-card">
            <h4>Average Expense</h4>
            <p id="avg-expense">₱0.00</p>
        </div>
        <div class="summary-card">
            <h4>Net Growth</h4>
            <p id="net-growth">0%</p>
        </div>
    </div>
</div>
<div class="recent-activity">
    <div class="activity-grid">
    <h3>Recent-Activity</h3>
        <div class="activity-row">
            <?php
            $sql = "SELECT text, amount, transaction_date FROM expenses 
                    WHERE user_id = ? ORDER BY transaction_date DESC LIMIT 4";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $_SESSION['id']);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                echo '<div class="activity-card ' . ($row['amount'] >= 0 ? 'income' : 'expense') . '">
                    <span class="activity-text">' . htmlspecialchars($row['text']) . '</span>
                    <span class="activity-date">' . date('M d, Y', strtotime($row['transaction_date'])) . '</span>
                    <span class="activity-amount">₱' . number_format(abs($row['amount']), 2) . '</span>
                </div>';
            }
            ?>
        </div>
        <div class="activity-row">
            <?php
            $sql = "SELECT text, amount, transaction_date FROM expenses 
                    WHERE user_id = ? ORDER BY transaction_date DESC LIMIT 4 OFFSET 4";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $_SESSION['id']);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                echo '<div class="activity-card ' . ($row['amount'] >= 0 ? 'income' : 'expense') . '">
                    <span class="activity-text">' . htmlspecialchars($row['text']) . '</span>
                    <span class="activity-date">' . date('M d, Y', strtotime($row['transaction_date'])) . '</span>
                    <span class="activity-amount">₱' . number_format(abs($row['amount']), 2) . '</span>
                </div>';
            }
            ?>
        </div>
    </div>
</div>

    </section>
</div>
<div class="main-content" id="contentArea">
    
</div>
<div class="main-content" id="contentArea">
    <!-- News and Updates Section -->
    <section class="content" id="newsUpdatesContent" style="display: none;">
        
        <div class="news-updates-container">
        <div id="news-container"></div>
    
    <div class="headernews">
        <div class="logo">
        News and Updates
        </div>
        <span class="progress-indicator">
            <?php
            echo '<div class="progress-bar"><div class="progress-fill" style="width: 0%"></div></div>';
            echo '<span>0%</span>'; // This replaces "Start" with the percentage
            ?>
        </span>
        <nav>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Business</a></li>
                <li><a href="#">Technology</a></li>
                <li><a href="#">Politics</a></li>
            </ul>
        </nav>
    </div>

    <div class="topheadlines">
        <div class="left">
            <div class="title">
                BREAKING NEWS!
            </div>
            <div id="breakingNews" class="newsCards">
                <div class="img"></div>
                <div class="title">
                    <a href="#" target="_blank">Breaking News Title</a>
                </div>
            </div>
        </div>
        <div class="right">
            <h2>Top Headlines</h2>
            <div class="headline-item">
                <h3><a href="#">Financial Markets Update</a></h3>
                <p>Stock markets reach new heights as tech sector surges</p>
            </div>
            <div class="headline-item">
                <h3><a href="#">Cryptocurrency News</a></h3>
                <p>Bitcoin shows strong recovery in Q3</p>
            </div>
            <div class="headline-item">
                <h3><a href="#">Banking Sector</a></h3>
                <p>New digital banking solutions emerge</p>           
            </div>
            <div class="headline-item">
                <h3><a href="#">Global Economic Forum</a></h3>
                <p>Leaders gather to discuss economic policies</p>
            </div>
            <div class="headline-item">
                <h3><a href="#">Renewable Energy Growth</a></h3>
                <p>Investments in green energy reach record highs</p>
            </div>
            <div class="headline-item">
                <h3><a href="#">Mergers & Acquisitions</a></h3>
                <p>Tech giants set to merge for innovation boost</p>
            </div>
            <div class="headline-item">
                <h3><a href="#">Startup Investments</a></h3>
                <p>Funding increases for new tech and health startups</p>
            </div>
            <div class="headline-item">
                <h3><a href="#">Real Estate Markets</a></h3>
                <p>Urban housing prices stabilize amid new policies</p>
            </div>
            <div class="headline-item">
                <h3><a href="#">International Trade</a></h3>
                <p>New trade agreements bolster economic ties</p>
            </div>
            <div class="headline-item">
                <h3><a href="#">Financial Technology</a></h3>
                <p>Fintech companies release new digital services</p>
            </div>
        </div>
    </div>

    <div class="page2">
        <div class="news" id="business">
            <div class="title">
                <h2>Business News</h2>
            </div>
            <div class="newsBox">
                <div class="newsCards">
                    <div class="img"></div>
                    <div class="title">
                        <a href="#">BUSINESS INSIGHTS</a>
                    </div>
                </div>
                <div class="newsCards">
                    <div class="img"></div>
                    <div class="title">
                        <a href="#">MARKET ANALYSIS</a>
                    </div>
                </div>
                <div class="newsCards">
                    <div class="img"></div>
                    <div class="title">
                        <a href="#">FINANCIAL TRENDS</a>
                    </div>
                </div>
                <div class="newsCards">
                    <div class="img"></div>
                    <div class="title">
                        <a href="#">ECONOMIC UPDATE</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="news" id="technology">
            <div class="title">
                <h2>Technology News</h2>
            </div>
            <div class="newsBox">
                <div class="newsCards">
                    <div class="img"></div>
                    <div class="title">
                        <a href="#">AI BREAKTHROUGHS</a>
                    </div>
                </div>
                <div class="newsCards">
                    <div class="img"></div>
                    <div class="title">
                        <a href="#">CYBERSECURITY UPDATE</a>
                    </div>
                </div>
                <div class="newsCards">
                    <div class="img"></div>
                    <div class="title">
                        <a href="#">CLOUD COMPUTING</a>
                    </div>
                </div>
                <div class="newsCards">
                    <div class="img"></div>
                    <div class="title">
                        <a href="#">5G NETWORKS</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="news" id="politics">
            <div class="title">
                <h2>Politics News</h2>
            </div>
            <div class="newsBox">
                <div class="newsCards">
                    <div class="img"></div>
                    <div class="title">
                        <a href="#">GLOBAL SUMMIT</a>
                    </div>
                </div>
                <div class="newsCards">
                    <div class="img"></div>
                    <div class="title">
                        <a href="#">POLICY CHANGES</a>
                    </div>
                </div>
                <div class="newsCards">
                    <div class="img"></div>
                    <div class="title">
                        <a href="#">INTERNATIONAL RELATIONS</a>
                    </div>
                </div>
                <div class="newsCards">
                    <div class="img"></div>
                    <div class="title">
                        <a href="#">ECONOMIC POLICIES</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>
    </section>
</div>
<div class="main-content" id="contentArea">
  <section class="content" id="expenseTrackerContent" style="display: none;">
    <h2>Expense Tracker</h2>
    <div class="expense-tracker-container" data-user-id="<?php echo $_SESSION['id']; ?>">
      <div class="left-column">
        <h4>Your Balance</h4>
        <h1 id="balance">₱0.00</h1>
        <div class="inc-exp-container">
          <div>
            <h4>Savings</h4>
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
            <label for="text">Transaction</label>
            <input type="text" id="text" placeholder="Enter Transaction...." required />
          </div>
          <div class="form-control">
            <label for="transaction_date">Date</label>
            <input type="date" id="transaction_date" name="transaction_date" required />
          </div>
          <div class="form-control">
            <label for="amount">Amount</label>
            <input type="number" id="amount" placeholder="Enter amount..." step="0.01" required />
            <div class="button-container">
              <button type="button" id="income-btn" class="type-btn income-btn active">Savings</button>
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
            <h4>Savings</h4>
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

<section class="content" id="moduleAssessmentContent" style="display: none;">
    <h2>Learning Journey</h2>
    <div class="module-assessment-container">
        <div class="module-section">
            <?php
            $conn = new mysqli("localhost", "root", "", "user_signup");
            $sql = "SELECT DISTINCT topics FROM modules ORDER BY topics";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="topic-card">';
                    echo '<div class="topic-header">';
                    echo '<h3>📚 ' . htmlspecialchars($row['topics']) . '</h3>';
                    echo '<span class="expand-icon">▼</span>';
                    echo '</div>';

                    $subtopicsSql = "SELECT m.module_id, m.subtopics, m.description, COALESCE(mp.current_page * 20, 0) as progress 
                                   FROM modules m 
                                   LEFT JOIN module_progress mp ON m.module_id = mp.module_id AND mp.user_id = " . $_SESSION['id'] . "
                                   WHERE m.topics = '" . $conn->real_escape_string($row['topics']) . "'";
                    $subtopicsResult = $conn->query($subtopicsSql);
                    
                    echo '<div class="subtopics-container">';
                    while($subtopic = $subtopicsResult->fetch_assoc()) {
                        echo '<div class="subtopic-card">';
                        echo '<a href="topic.php?id=' . $subtopic['module_id'] . '" class="subtopic-link">';
                        echo '<div class="subtopic-header">';
                        echo '<h4>🔹 ' . htmlspecialchars($subtopic['subtopics']) . '</h4>';
                        echo '<span class="progress-indicator">';
                        echo '<div class="progress-bar"><div class="progress-fill" style="width: ' . $subtopic['progress'] . '%"></div></div>';
                        echo '<span>' . $subtopic['progress'] . '%</span>';
                        echo '</span>';
                        echo '</div>';
                        echo '</a>';
                        echo '</div>';
                    }
                    echo '</div>';
                    echo '</div>';
                }
            }
            $conn->close();
            ?>
        </div>
    </div>
</section>

</main>
<footer>
   
</footer>
<script src="lobby.js"></script><script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('balance-trend-chart').getContext('2d');

    const fetchData = (timeRange) => {
        fetch(`get_expenses.php?timeRange=${timeRange}`)
            .then(response => response.json())
            .then(data => {
                // Extract labels (dates) and values (expenses)
                const labels = data.map(item => item.period);
                const values = data.map(item => item.total_expense);

                // Update the chart
                updateChart(labels, values);
            });
    };

    const updateChart = (labels, values) => {
        // Destroy the old chart if it exists
        if (window.expenseChart) {
            window.expenseChart.destroy();
        }

        // Create the chart
        window.expenseChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Expenses',
                    data: values,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Time Period'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Total Expense'
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    };

    // Handle time range selection
    const timeRangeSelector = document.getElementById('timeRangeSelector');
    timeRangeSelector.addEventListener('change', () => {
        fetchData(timeRangeSelector.value);
    });

    // Load initial data
    fetchData('daily');
});

</script>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="module" src="javascript/news.js"></script>
</body>
</html>



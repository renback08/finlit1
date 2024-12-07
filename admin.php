<?php
session_start();
include("connect.php");

// Check if user is admin
if (!isset($_SESSION['user_name']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$user_name = $_SESSION['user_name'];
$profile_picture = isset($_SESSION['profile_picture']) && file_exists($_SESSION['profile_picture']) 
    ? $_SESSION['profile_picture'] 
    : 'path/to/default.jpg';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Information Management System</title>
    <link rel="stylesheet" href="admin.css">
    
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
                <button form="profile-form" onclick="location.href='profile.php'">Profile</button>
                    <button id="themeToggle">Light/Dark Mode</button>
                    <button id="logoutButton">Log Out</button>
                </div>
            </div>
            <span class="user-role">ADMIN</span>
        </div>
    
</header>
<main>
    <div class="sidebar">
        <button class="sidebar-item active" id="CREATEButton">CREATE</button>
        <button class="sidebar-item" id="READButton">READ</button>
        <button class="sidebar-item" id="UPDATEButton">UPDATE</button>
        <button class="sidebar-item" id="DELETEButton">DELETE</button>  
    </div>

    <div class="main-content" id="contentArea">
        <section class="content" id="CREATEContent">
            <h2>Create New Entry</h2>
            <div class="create-container">
                <div class="create-top">
                    <div class="overview">
                        <h3>Overview</h3>
                        <select id="createSelector" onchange="updateCreateView()">
                            <option value="user" selected>User Account</option>
                            <option value="module">Module Content</option>
                            <option value="assessment">Assessment</option>
                        </select>
                        
                        <div class="overview-grid">
                            <?php
                            // Get statistics from database
                            $userQuery = "SELECT COUNT(*) as count FROM users";
                            $moduleQuery = "SELECT COUNT(*) as count FROM modules";
                            
                            $userResult = $conn->query($userQuery);
                            $moduleResult = $conn->query($moduleQuery);
                            
                            $userCount = $userResult->fetch_assoc()['count'];
                            $moduleCount = $moduleResult->fetch_assoc()['count'];
                            ?>
                            
                            <div class="overview-item">
                                <h4>Total Users Created</h4>
                                <p id="total-users"><?php echo $userCount; ?></p>
                            </div>
                            <div class="overview-item">
                                <h4>Total Modules Created</h4>
                                <p id="total-modules"><?php echo $moduleCount; ?></p>
                            </div>
                            <div class="overview-item">
                                <h4>Total Assessments Created</h4>
                                <p id="total-assessments">0</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="create-form-container">
                    <!-- User Account Creation Form -->
                    <form id="userCreateForm" class="create-form">
                        <h3>Create New User Account</h3>
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="first_name" required>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="last_name" required>
                        </div>
                        <div class="form-group">
                            <label>Student ID</label>
                            <input type="text" name="student_id" pattern="^\d{4}-\d{5}$" title="Format: YYYY-XXXXX" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" required>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-create">Create User</button>
                    </form>

                    <!-- Module Creation Form -->
                    <form id="moduleCreateForm" class="create-form" style="display: none;">
                        <h3>Create New Module</h3>
                        <div class="form-group">
                            <label>Topic</label>
                            <input type="text" name="topics" required>
                        </div>
                        <div class="form-group">
                            <label>Subtopic</label>
                            <input type="text" name="subtopics" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Content Pages (1-5)</label>
                            <textarea name="content_1" placeholder="Page 1 Content"></textarea>
                            <textarea name="content_2" placeholder="Page 2 Content"></textarea>
                            <textarea name="content_3" placeholder="Page 3 Content"></textarea>
                            <textarea name="content_4" placeholder="Page 4 Content"></textarea>
                            <textarea name="content_5" placeholder="Page 5 Content"></textarea>
                        </div>
                        <button type="submit" class="btn-create">Create Module</button>
                    </form>
                    
                    <!-- Assessment Creation Form -->
                    <!-- Add this inside the create-form-container div after the moduleCreateForm -->

<!-- Assessment Creation Form -->
<form id="assessmentCreateForm" class="create-form" style="display: none;">
    <h3>Create New Assessment</h3>
    
    <div class="form-group">
        <label>Select Module</label>
        <select name="module_id" required>
            <?php
            $moduleQuery = "SELECT module_id, subtopics FROM modules ORDER BY module_id";
            $moduleResult = $conn->query($moduleQuery);
            while($module = $moduleResult->fetch_assoc()) {
                echo "<option value='" . $module['module_id'] . "'>" . htmlspecialchars($module['subtopics']) . "</option>";
            }
            ?>
        </select>
    </div>

    <div id="questionsContainer">
        <div class="question-block">
            <h4>Question 1</h4>
            <div class="form-group">
                <label>Question Text</label>
                <textarea name="questions[0][text]" required></textarea>
            </div>
            
            <div class="form-group">
                <label>Option A</label>
                <input type="text" name="questions[0][option_a]" required>
            </div>
            
            <div class="form-group">
                <label>Option B</label>
                <input type="text" name="questions[0][option_b]" required>
            </div>
            
            <div class="form-group">
                <label>Option C</label>
                <input type="text" name="questions[0][option_c]" required>
            </div>
            
            <div class="form-group">
                <label>Option D</label>
                <input type="text" name="questions[0][option_d]" required>
            </div>
            
            <div class="form-group">
                <label>Correct Answer</label>
                <select name="questions[0][correct_answer]" required>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>
            </div>
        </div>
    </div>
    
    <button type="button" class="btn-add-question">Add Another Question</button>
    <button type="submit" class="btn-create">Create Assessment</button>
</form>

                </div>
            </div>
        </section>
        
        <section class="content" id="READContent">
    <h2>System Records</h2>
    <div class="read-container">
        <div class="read-controls">
            <select id="readSelector" class="styled-select" onchange="handleTypeChange()">
                <option value="users">User Accounts</option>
                <option value="modules">Learning Modules</option>
                <option value="assessment">Assessment</option>
            
            </select>
            <div class="search-box">
                <input type="text" id="searchInput" class="styled-input" placeholder="Search records...">
            </div>
        </div>
        <!-- Users Table -->
        <div id="usersTable" class="table-responsive">
        <div class="table-responsive">
        <table class="users-table">
                <thead>
                    <tr>
                    <div class="form-group"><th>ID</th>
                        <th>Full Name</th>
                        <th>Student ID</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Signup Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="userRecordsBody"></tbody>
            </table>
        </div> 
        </div>
        
        <div id="userDetailsModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>User Details</h2>
        <div class="user-details-container">
            <div class="profile-section">
                <img id="userProfilePic" src="" alt="Profile Picture">
            </div>
            <div class="details-section">
                <p><strong>ID:</strong> <span id="userId"></span></p>
                <p><strong>Name:</strong> <span id="userName"></span></p>
                <p><strong>Student ID:</strong> <span id="userStudentId"></span></p>
                <p><strong>Email:</strong> <span id="userEmail"></span></p>
                <p><strong>Sex:</strong> <span id="userSex"></span></p>
                <p><strong>Role:</strong> <span id="userRole"></span></p>
                <p><strong>Phone:</strong> <span id="userPhone"></span></p>
                <p><strong>Signup Date:</strong> <span id="userSignupDate"></span></p>
            </div>
        </div>
    </div>
</div>


        <!-- Modules Table -->
        <div id="modulestable" class="table-responsive">
        <div class="table-responsive">
        <table class="modules-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Topic</th>
                        <th>Subtopic</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="moduleRecordsBody"></tbody>
            </table>
        </div>
        <div id="moduleDetailsModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Module Details</h2>
        <div class="module-details-container">
            <div class="details-section">
                <p><strong>ID:</strong> <span id="moduleId"></span></p>
                <p><strong>Topic:</strong> <span id="moduleTopic"></span></p>
                <p><strong>Subtopic:</strong> <span id="moduleSubtopic"></span></p>
                <p><strong>Description:</strong> <span id="moduleDescription"></span></p>
                
                <div class="content-pages">
                    <div class="content-page">
                        <h4>Page 1</h4>
                        <div class="content-text" id="moduleContent1"></div>
                    </div>
                    <div class="content-page">
                        <h4>Page 2</h4>
                        <div class="content-text" id="moduleContent2"></div>
                    </div>
                    <div class="content-page">
                        <h4>Page 3</h4>
                        <div class="content-text" id="moduleContent3"></div>
                    </div>
                    <div class="content-page">
                        <h4>Page 4</h4>
                        <div class="content-text" id="moduleContent4"></div>
                    </div>
                    <div class="content-page">
                        <h4>Page 5</h4>
                        <div class="content-text" id="moduleContent5"></div>
                    </div>
                </div>

                <p><strong>Created Date:</strong> <span id="moduleCreatedDate"></span></p>
            </div>
        </div>
    </div>
</div>
<!-- Assessments Table -->
 
<!-- Assessment Details Modal -->
<div id="assessmenttable" class="table-responsive">
        <div class="table-responsive">
        <table class="assessment-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Topic</th>
                        <th>Subtopic</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="assessmentRecordsBody"></tbody>
            </table>
        </div>
<div id="assessmentDetailsModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Assessment Details</h2>
        <div class="assessment-details-container">
            <div class="details-section">
                <h3>Question Details</h3>
                <table class="question-details-table">
                    <thead>
                        <tr>
                            <th>Question #</th>
                            <th>Module/Lesson</th>
                            <th>Question Text</th>
                            <th>Options</th>
                            <th>Correct Answer</th>
                        </tr>
                    </thead>
                    <tbody id="questionDetailsBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>



        <div class="pagination-controls">
            <button id="prevPage" class="page-btn">Previous</button>
            <span id="currentPage">Page 1</span>
            <button id="nextPage" class="page-btn">Next</button>
        </div>
    </div>
</section>
                <!-- Update Section -->
                <section class="content" id="UPDATEContent">
                    <h2>Update Records</h2>
                    <div class="update-container">
                        <div class="update-controls">
                            <select id="updateSelector">
                                <option value="users" selected>Users</option>
                                <option value="modules">Modules</option>
                            </select>
                            <div class="search-box">
                                <input type="text" id="updateSearchInput" placeholder="Search records...">
                            </div>
                        </div>

                        <div class="records-list">
                            <!-- Records will be populated here -->
                        </div>
                          <div class="update-form-container" style="display: none;">
                              <form id="userUpdateForm" class="update-form">
                                  <input type="hidden" name="user_id" id="userId">
                                  <div class="form-group">
                                      <label>First Name</label>
                                      <input type="text" name="first_name" id="updateFirstName" required>
                                  </div>
                                  <div class="form-group">
                                      <label>Last Name</label>
                                      <input type="text" name="last_name" id="updateLastName" required>
                                  </div>
                                  <div class="form-group">
                                      <label>Email</label>
                                      <input type="email" name="email" id="updateEmail" required>
                                  </div>
                                  <div class="form-group">
                                      <label>Role</label>
                                      <select name="role" id="updateRole">
                                          <option value="user">User</option>
                                          <option value="admin">Admin</option>
                                      </select>
                                  </div>
                                  <button type="submit" class="btn-update">Save Changes</button>
                                  <button type="button" class="btn-cancel" onclick="cancelUpdate()">Cancel</button>
                              </form>
                          </div>
                          <div class="update-form-container" style="display: none;">
                              <!-- Module Update Form -->
                              <form id="moduleUpdateForm" class="update-form">
                                  <input type="hidden" name="module_id" id="moduleId">
                                  <div class="form-group">
                                      <label>Topic</label>
                                      <input type="text" name="topics" id="updateTopic" required>
                                  </div>
                                  <div class="form-group">
                                      <label>Subtopic</label>
                                      <input type="text" name="subtopics" id="updateSubtopic" required>
                                  </div>
                                  <div class="form-group">
                                      <label>Description</label>
                                      <textarea name="description" id="updateDescription" rows="4"></textarea>
                                  </div>
                                  <div class="form-group">
                                      <label>Content Pages</label>
                                      <textarea name="content_1" id="updateContent1" placeholder="Page 1"></textarea>
                                      <textarea name="content_2" id="updateContent2" placeholder="Page 2"></textarea>
                                      <textarea name="content_3" id="updateContent3" placeholder="Page 3"></textarea>
                                      <textarea name="content_4" id="updateContent4" placeholder="Page 4"></textarea>
                                      <textarea name="content_5" id="updateContent5" placeholder="Page 5"></textarea>
                                  </div>
                                  <button type="submit" class="btn-update">Save Module</button>
                                  <button type="button" class="btn-cancel" onclick="cancelUpdate()">Cancel</button>
                              </form>
                          </div>
                      </div>
                </section>
                  <!-- Delete Section -->
          <section class="content" id="DELETEContent">
              <h2>Delete Management</h2>
              <div class="delete-container">
                  <div class="controls-wrapper">
                      <div class="search-filter">
                          <input type="text" id="userSearchInput" placeholder="Search users...">
                          <select id="userStatusFilter">
    <option value="accounts">Accounts</option>
    <option value="modules">Modules</option>
    <option value="assessments">Assessments</option>
</select>
                      </div>
                      <div class="bulk-actions">
                          <button class="btn-danger" onclick="deleteSelectedRecords()">Delete Selected</button>
                          <button class="btn-primary" onclick="exportSelectedUsers()">Export Selected</button>
                      </div>
                  </div>

                  <div class="table-responsive">
                      <table class="users-table">
                          <thead>
                              <tr>
                                  <th><input type="checkbox" id="selectAllUsers"></th>
                                  <th>ID</th>
                                  <th>Name</th>
                                  <th>Student ID</th>
                                  <th>Email</th>
                                  <th>Role</th>
                                  <th>Signup Date</th>
                                  <th>Actions</th>
                              </tr>
                          </thead>
                          <tbody id="userTableBody">
                              <!-- User data will be populated here via JavaScript -->
                          </tbody>
                      </table>
                  </div>
        <!-- Modules Table -->
        <div id="modulesTable" class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAllModules"></th>
                        <th>Module ID</th>
                        <th>Topic</th>
                        <th>Subtopic</th>
                        <th>Description</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="modulesTableBody"></tbody>
            </table>
        </div>

        <!-- Assessments Table -->
        <div id="assessmentsTable" class="table-responsive" style="display: none;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAllAssessments"></th>
                        <th>ID</th>
                        <th>Module</th>
                        <th>Questions</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="assessmentsTableBody"></tbody>
            </table>
        </div>
                  <div class="pagination">
                      <button id="prevPage">Previous</button>
                      <span id="currentPage">Page 1</span>
                      <button id="nextPage">Next</button>
                  </div>
              </div>
          </section>
              </div>
</main>
<script>
document.getElementById('userStatusFilter').addEventListener('change', function() {
    const selectedType = this.value;
    if (selectedType === 'modules') {
        document.getElementById('userSearchInput').placeholder = 'Search modules...';
        document.getElementById('modulesTable').style.display = 'block';
        loadContentTable('modules', 1);
    } else {
        document.getElementById('modulesTable').style.display = 'none';
    }
});

function deleteSelectedRecords() {
    const type = document.getElementById('userStatusFilter').value;
    const selectedIds = Array.from(document.querySelectorAll('.record-select:checked'))
        .map(checkbox => checkbox.value);
    
    if (selectedIds.length === 0) {
        showNotification('Warning', `Please select ${type} to delete`, 'warning');
        return;
    }

    if (confirm(`Delete ${selectedIds.length} selected ${type}?`)) {
        fetch(`bulk_delete_${type}.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ ids: selectedIds })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Success', `Selected ${type} deleted successfully`, 'success');
                loadContentTable(type, 1);
            }
        });
    }
}
</script>    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const createContent = document.getElementById('CreateContent');
        createContent.style.display = 'block';
        
        const createButton = document.getElementById('CREATEButton');
        createButton.classList.add('active');
    });

    function updateCreateView() {
        const selectedValue = document.getElementById('createSelector').value;
        // Add your view update logic here
    }
    </script>

</main>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="lobby.js"></script>
<script src="admin.js"></script>
</body>
</html>

document.addEventListener('DOMContentLoaded', function() {
    // Get all section buttons
    const sidebarItems = document.querySelectorAll('.sidebar-item');
    const contentSections = document.querySelectorAll('.content');

    // Function to toggle active state and show corresponding section
    function toggleActiveButton(buttonId) {
        sidebarItems.forEach(item => {
            item.classList.remove('active');
        });
        const activeButton = document.getElementById(buttonId);
        if (activeButton) {
            activeButton.classList.add('active');
        }
    }

    // Function to show the corresponding content
    function showSection(sectionId) {
        contentSections.forEach(section => {
            section.style.display = 'none';
        });
        const targetSection = document.getElementById(`${sectionId}Content`);
        if (targetSection) {
            targetSection.style.display = 'block';
        }
    }

    // Sidebar item click event listener
    sidebarItems.forEach(item => {
        item.addEventListener('click', () => {
            const sectionId = item.id.replace('Button', '');
            toggleActiveButton(item.id);
            showSection(sectionId);
            localStorage.setItem('activeSection', sectionId);
        });
    });

    // Initialize: Display the saved section or default to CREATE
    const savedSection = localStorage.getItem('activeSection') || 'CREATE';
    toggleActiveButton(`${savedSection}Button`);
    showSection(savedSection);
});

function initializeCharts() {
    const ctx = document.getElementById('creation-trend-chart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7'],
            datasets: [{
                label: 'Users Created',
                data: [12, 19, 3, 5, 2, 3, 7],
                borderColor: '#3498db',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
}
function initializeCreateSection() {
    const createSelector = document.getElementById('createSelector');
    const userForm = document.getElementById('userCreateForm');
    const moduleForm = document.getElementById('moduleCreateForm');

    createSelector.addEventListener('change', function() {
        const selectedValue = this.value;
        userForm.style.display = selectedValue === 'user' ? 'block' : 'none';
        moduleForm.style.display = selectedValue === 'module' ? 'block' : 'none';
    });

    async function handleFormSubmit(form, type) {
        const formData = new FormData(form);
        formData.append('type', type);

        try {
            const response = await fetch('create_handler.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            
            if (result.success) {
                showNotification('Success', result.message, 'success');
                form.reset();
                updateStatistics();
            } else {
                showNotification('Error', result.message, 'error');
            }
        } catch (error) {
            showNotification('Error', 'Failed to submit form', 'error');
        }
    }

    userForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        await handleFormSubmit(this, 'user');
    });

    moduleForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        await handleFormSubmit(this, 'module');
    });
}
function updateCreateView() {
    const selectedValue = document.getElementById('createSelector').value;
    loadStatistics(selectedValue);
    updateCharts(selectedValue);
}
function showNotification(title, message, type) {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <h4>${title}</h4>
        <p>${message}</p>
    `;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}

document.addEventListener('DOMContentLoaded', initializeCreateSection);
async function loadStatistics(type = 'all') {
    try {
        const response = await fetch(`admin_functions.php?action=getStats&type=${type}`);
        const stats = await response.json();
        
        document.getElementById('total-users').textContent = stats.users;
        document.getElementById('total-modules').textContent = stats.modules;
        document.getElementById('total-assessments').textContent = stats.assessments;
    } catch (error) {
        console.error('Error loading statistics:', error);
    }
}

function updateCharts(type) {
    const ctx = document.getElementById('creation-trend-chart').getContext('2d');
    if (window.creationChart) {
        window.creationChart.destroy();
    }
    
    fetch(`admin_functions.php?action=getChartData&type=${type}`)
        .then(response => response.json())
        .then(data => {
            window.creationChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: `${type.charAt(0).toUpperCase() + type.slice(1)} Created`,
                        data: data.values,
                        borderColor: '#3498db',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    }
                }
            });
        });
}

// Settings and Theme Management
function initializeSettings() {
    const themeToggle = document.getElementById('themeToggle');
    themeToggle?.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        localStorage.setItem('theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light');
    });

    // Load saved theme
    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark-mode');
    }
}
// Assessment form handling
let questionCount = 1;

document.querySelector('.btn-add-question').addEventListener('click', function() {
    const container = document.getElementById('questionsContainer');
    const newQuestion = document.createElement('div');
    newQuestion.className = 'question-block';
    newQuestion.innerHTML = `
        <h4>Question ${questionCount + 1}</h4>
        <div class="form-group">
            <label>Question Text</label>
            <textarea name="questions[${questionCount}][text]" required></textarea>
        </div>
        
        <div class="form-group">
            <label>Option A</label>
            <input type="text" name="questions[${questionCount}][option_a]" required>
        </div>
        
        <div class="form-group">
            <label>Option B</label>
            <input type="text" name="questions[${questionCount}][option_b]" required>
        </div>
        
        <div class="form-group">
            <label>Option C</label>
            <input type="text" name="questions[${questionCount}][option_c]" required>
        </div>
        
        <div class="form-group">
            <label>Option D</label>
            <input type="text" name="questions[${questionCount}][option_d]" required>
        </div>
        
        <div class="form-group">
            <label>Correct Answer</label>
            <select name="questions[${questionCount}][correct_answer]" required>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
            </select>
        </div>
    `;
    container.appendChild(newQuestion);
    questionCount++;
});

document.getElementById('assessmentCreateForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('create_assessment.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            showNotification('Success', 'Assessment created successfully!', 'success');
            this.reset();
            // Refresh the assessments count
            updateStatistics();
        } else {
            showNotification('Error', data.message || 'Error creating assessment', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error', 'Failed to create assessment', 'error');
    });
});
// Assessment table handling
function loadAssessmentRecords(page = 1) {
    fetch(`get_records.php?type=assessments&page=${page}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('assessmentRecordsBody');
            tbody.innerHTML = '';
            
            data.records.forEach(record => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><input type="checkbox" class="record-select" value="${record.module_id}"></td>
                    <td>${record.module_id}</td>
                    <td>${record.topics} - ${record.subtopics}</td>
                    <td>${record.question_count}</td>
                    <td>${record.created_date}</td>
                    <td>
                        <button onclick="viewAssessmentDetails(${record.module_id})">View</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
            
            updatePagination(data.currentPage, data.totalPages);
        });
}
function viewAssessmentDetails(moduleId) {
    fetch(`get_question_details.php?module_id=${moduleId}`)
        .then(response => response.json())
        .then(questions => {
            const tbody = document.getElementById('questionDetailsBody');
            tbody.innerHTML = '';
            
            questions.forEach((question, index) => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${question.topics} - ${question.subtopics}</td>
                    <td>${question.question_text}</td>
                    <td>
                        A: ${question.option_a}<br>
                        B: ${question.option_b}<br>
                        C: ${question.option_c}<br>
                        D: ${question.option_d}
                    </td>
                    <td>${question.correct_answer}</td>
                `;
                tbody.appendChild(tr);
            });
            
            document.getElementById('assessmentDetailsModal').style.display = 'block';
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error', 'Failed to load question details', 'error');
        });
}

// Assessment creation handler
function initAssessmentCreation() {
    const createSelector = document.getElementById('createSelector');
    const assessmentForm = document.getElementById('assessmentCreateForm');
    
    createSelector.addEventListener('change', function() {
        if (this.value === 'assessment') {
            document.querySelectorAll('.create-form').forEach(form => form.style.display = 'none');
            assessmentForm.style.display = 'block';
            loadModules();
        }
    });
}
function loadModules() {
    fetch('get_modules.php')
        .then(response => response.json())
        .then(modules => {
            const moduleSelect = document.querySelector('[name="module_id"]');
            moduleSelect.innerHTML = modules.map(module => 
                `<option value="${module.module_id}">${module.subtopics}</option>`
            ).join('');
        });
}
// Initialize assessment creation when document loads
document.addEventListener('DOMContentLoaded', function() {
    initAssessmentCreation();
});
// User Management Functions
async function createUser(formData) {
    try {
        const response = await fetch('admin_functions.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.success) {
            loadStatistics();
            return true;
        }
        return false;
    } catch (error) {
        console.error('Error creating user:', error);
        return false;
    }
}

// Module Management Functions
async function createModule(formData) {
    try {
        const response = await fetch('admin_functions.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.success) {
            loadStatistics();
            return true;
        }
        return false;
    } catch (error) {
        console.error('Error creating module:', error);
        return false;
    }
}
function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        fetch('delete_user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `userId=${userId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Success', 'User deleted successfully', 'success');
                loadUserTable(); // Refresh the table
            } else {
                showNotification('Error', data.message, 'error');
            }
        });
    }
}

let currentPage = 1;
const recordsPerPage = 10;
let currentRecordType = 'users';

function handleTypeChange() {
    currentRecordType = document.getElementById('readSelector').value;
    document.getElementById('usersTable').style.display = currentRecordType === 'users' ? 'block' : 'none';
    document.getElementById('modulesTable').style.display = currentRecordType === 'modules' ? 'block' : 'none';
    loadRecords(1);
}

function loadRecords(page = 1) {
    const search = document.getElementById('searchInput').value;
    fetch(`get_records.php?type=${currentRecordType}&page=${page}&search=${search}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById(currentRecordType === 'users' ? 'userRecordsBody' : 'moduleRecordsBody');
            tbody.innerHTML = '';

            if (currentRecordType === 'users') {
                data.records.forEach(record => {
                    tbody.innerHTML += `
                        <tr>
                        <td>${record.id}</td>
                        <td>${record.name}</td>
                        <td>${record.student_id}</td>
                        <td>${record.email}</td>
                        <td><span class="role-badge ${record.role}">${record.role}</span></td>
                        <td>${new Date(record.signup_date).toLocaleDateString()}</td>
                        <td class="actions">
                            <button onclick="viewUserDetails(${record.id})" class="action-btn view">View</button>
                        </td>
                    </tr>
                `;
                });
                updatePagination(data.currentPage, data.totalPages);
       
            } else {
                data.records.forEach(record => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${record.id}</td>
                            <td>${record.topic}</td>
                            <td>${record.subtopic}</td>
                            <td>${record.created_date}</td>
                            <td class="actions">
                                <button onclick="viewDetails(${record.id}, 'module')" class="action-btn view">View</button>
                                
                            </td>
                        </tr>
                    `;
                });
            }

            updatePagination(data.currentPage, data.totalPages);
        });
}
function viewDetails(id, type) {
    if (type === 'module') {
        const modal = document.getElementById('moduleDetailsModal');
        
        fetch(`get_record_details.php?id=${id}&type=modules`)
            .then(response => response.json())
            .then(data => {
                // Basic details
                document.getElementById('moduleId').textContent = data.module_id;
                document.getElementById('moduleTopic').textContent = data.topics;
                document.getElementById('moduleSubtopic').textContent = data.subtopics;
                document.getElementById('moduleDescription').textContent = data.description;
                
                // Content pages
                for (let i = 1; i <= 5; i++) {
                    const contentElement = document.getElementById(`moduleContent${i}`);
                    contentElement.textContent = data[`content_${i}`] || 'No content available';
                }
                
                // Created date
                const createdDate = new Date(data.created_at).toLocaleString();
                document.getElementById('moduleCreatedDate').textContent = createdDate;
                
                modal.style.display = 'block';
            })
            .catch(error => {
                console.error('Error fetching module details:', error);
            });

        // Close modal handlers
        const closeBtn = modal.querySelector('.close');
        closeBtn.onclick = () => modal.style.display = 'none';
        
        window.onclick = (event) => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        };
    }
}


function updatePagination(currentPage, totalPages) {
    document.getElementById('currentPage').textContent = `Page ${currentPage} of ${totalPages}`;
    document.getElementById('prevPage').disabled = currentPage === 1;
    document.getElementById('nextPage').disabled = currentPage === totalPages;
}

// Event Listeners
document.getElementById('searchInput').addEventListener('input', debounce(() => loadRecords(1), 300));

document.getElementById('prevPage').addEventListener('click', () => {
    const currentPage = parseInt(document.getElementById('currentPage').textContent.split(' ')[1]);
    if (currentPage > 1) loadRecords(currentPage - 1);
});

document.getElementById('nextPage').addEventListener('click', () => {
    const currentPage = parseInt(document.getElementById('currentPage').textContent.split(' ')[1]);
    loadRecords(currentPage + 1);
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => loadRecords());

// Debounce function for search input
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Initialize modal and event listeners
document.addEventListener('DOMContentLoaded', () => {
    initializeModal();
    initializeTableControls();
    loadRecords();
    initializeCharts();
    initializeSettings();
    loadStatistics();
    
    // Show/hide appropriate form based on selector
    document.getElementById('createSelector').addEventListener('change', function() {
        const userForm = document.getElementById('userCreateForm');
        const moduleForm = document.getElementById('moduleCreateForm');
        
        if (this.value === 'user') {
            userForm.style.display = 'block';
            moduleForm.style.display = 'none';
        } else if (this.value === 'module') {
            userForm.style.display = 'none';
            moduleForm.style.display = 'block';
        }
    });

    // Handle form submissions
    ['userCreateForm', 'moduleCreateForm'].forEach(formId => {
        document.getElementById(formId).addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('type', formId === 'userCreateForm' ? 'user' : 'module');

            try {
                const response = await fetch('create_handler.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                
                if (data.success) {
                    alert('Created successfully!');
                    this.reset();
                    updateStatistics(); // Function to refresh the overview counts
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                alert('Error submitting form: ' + error.message);
            }
        });
    });
});
function updateStatistics() {
    fetch('admin_functions.php?action=getStats')
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-users').textContent = data.users;
            document.getElementById('total-modules').textContent = data.modules;
        });
}
// Enhanced update section functionality
function initializeUpdateSection() {
    loadUpdateRecords();
    setupUpdateEventListeners();
}

function setupUpdateEventListeners() {
    // Search functionality
    document.getElementById('updateSearchInput').addEventListener('input', 
        debounce(() => loadUpdateRecords(), 300)
    );

    // Form submission
    document.getElementById('userUpdateForm').addEventListener('submit', 
        handleUpdateFormSubmit
    );

    // Type selector
    document.getElementById('updateSelector').addEventListener('change', 
        () => {
            loadUpdateRecords();
            resetUpdateForm();
        }
    );
}
document.addEventListener('DOMContentLoaded', function() {
    // Close button handler
    document.querySelector('#assessmentDetailsModal .close').addEventListener('click', function() {
        document.getElementById('assessmentDetailsModal').style.display = 'none';
        clearAssessmentModal();
    });

    // Click outside modal handler
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('assessmentDetailsModal');
        if (event.target === modal) {
            modal.style.display = 'none';
            clearAssessmentModal();
        }
    });
});

function loadUpdateRecords() {
    const type = document.getElementById('updateSelector').value;
    const search = document.getElementById('updateSearchInput').value;
    
    fetch(`get_records.php?type=${type}&search=${search}`)
        .then(response => response.json())
        .then(data => {
            const recordsList = document.querySelector('.records-list');
            recordsList.innerHTML = data.records.map(record => `
                <div class="record-item">
                    <div class="record-info">
                        <h4>${record.name || record.topic}</h4>
                        <p>${record.email || record.subtopic}</p>
                    </div>
                    <button onclick="showUpdateForm(${record.id}, '${type}')" class="btn-edit">Edit</button>
                </div>
            `).join('');
        });
}

function handleUpdateFormSubmit(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    
    fetch('update_record.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Success', 'Record updated successfully', 'success');
            loadUpdateRecords();
            resetUpdateForm();
        } else {
            showNotification('Error', data.message, 'error');
        }
    })
    .catch(handleUpdateError);
}

function showUpdateForm(id, type) {
    fetch(`get_record_details.php?id=${id}&type=${type}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('userId').value = data.id;
            document.getElementById('updateFirstName').value = data.first_name;
            document.getElementById('updateLastName').value = data.last_name;
            document.getElementById('updateEmail').value = data.email;
            document.getElementById('updateRole').value = data.role;
            document.querySelector('.update-form-container').style.display = 'block';
        });
}

function showModuleUpdateForm(id) {
    fetch(`get_record_details.php?id=${id}&type=modules`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('moduleId').value = data.module_id;
            document.getElementById('updateTopic').value = data.topics;
            document.getElementById('updateSubtopic').value = data.subtopics;
            document.getElementById('updateDescription').value = data.description;
            document.getElementById('updateContent1').value = data.content_1;
            document.getElementById('updateContent2').value = data.content_2;
            document.getElementById('updateContent3').value = data.content_3;
            document.getElementById('updateContent4').value = data.content_4;
            document.getElementById('updateContent5').value = data.content_5;
            
            document.querySelector('.update-form-container').style.display = 'block';
        });
}

function cancelUpdate() {
    document.querySelector('.update-form-container').style.display = 'none';
    document.getElementById('userUpdateForm').reset();
}

function validateUpdateForm(formData) {
    const email = formData.get('email');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (!emailRegex.test(email)) {
        showNotification('Error', 'Please enter a valid email address', 'error');
        return false;
    }
    return true;
}

function resetUpdateForm() {
    document.getElementById('userUpdateForm').reset();
    document.querySelector('.update-form-container').style.display = 'none';
}

function toggleUpdateForm(show) {
    const formContainer = document.querySelector('.update-form-container');
    formContainer.style.display = show ? 'block' : 'none';
}

function handleModuleUpdate(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    
    fetch('update_module.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Success', 'Module updated successfully', 'success');
            loadUpdateRecords();
            document.querySelector('.update-form-container').style.display = 'none';
        } else {
            showNotification('Error', data.message, 'error');
        }
    });
}

document.addEventListener('DOMContentLoaded', initializeUpdateSection);
function loadUserTable(page = 1, search = '', status = 'all') {
    fetch(`get_users.php?page=${page}&search=${search}&status=${status}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('userTableBody');
            tbody.innerHTML = '';
            
            data.users.forEach(user => {
                tbody.innerHTML += `
                    <tr>
                        <td><input type="checkbox" class="user-select" value="${user.id}"></td>
                        <td>${user.id}</td>
                        <td>${user.first_name} ${user.last_name}</td>
                        <td>${user.student_id}</td>
                        <td>${user.email}</td>
                        <td><span class="role-badge ${user.role}">${user.role}</span></td>
                        <td>${new Date(user.signup_date).toLocaleDateString()}</td>
                        <td>
                            <button onclick="viewUser(${user.id})" class="btn-view">View</button>
                            <button onclick="confirmDeleteUser(${user.id})" class="btn-delete">Delete</button>
                        </td>
                    </tr>
                `;
            });
            
            updatePagination(data.currentPage, data.totalPages);
        });
}
document.addEventListener('DOMContentLoaded', () => {
    const deleteSection = document.getElementById('DELETEContent');
    if (deleteSection) {
        loadUserTable();
        
        document.getElementById('userSearchInput').addEventListener('input', 
            debounce(() => loadUserTable(1, document.getElementById('userSearchInput').value), 300)
        );
        
        document.getElementById('userStatusFilter').addEventListener('change', 
            () => loadUserTable(1, document.getElementById('userSearchInput').value, 
                document.getElementById('userStatusFilter').value)
        );
        
        document.getElementById('selectAllUsers').addEventListener('change', e => {
            document.querySelectorAll('.user-select').forEach(checkbox => {
                checkbox.checked = e.target.checked;
            });
        });
    }
});
function viewUserDetails(userId) {
    const modal = document.getElementById('userDetailsModal');
    fetch(`get_user_details.php?id=${userId}`)
        .then(response => response.json())
        .then(user => {
            document.getElementById('userProfilePic').src = user.profile_picture;
            document.getElementById('userId').textContent = user.id;
            document.getElementById('userName').textContent = `${user.first_name} ${user.last_name}`;
            document.getElementById('userStudentId').textContent = user.student_id;
            document.getElementById('userEmail').textContent = user.email;
            document.getElementById('userSex').textContent = user.sex;
            document.getElementById('userRole').textContent = user.role;
            document.getElementById('userPhone').textContent = user.phone_number || 'Not set';
            document.getElementById('userSignupDate').textContent = new Date(user.signup_date).toLocaleDateString();
             
           
            // Show modal
            modal.style.display = 'block';
            
            // Close button click handler
            closeBtn.onclick = () => {
                modal.style.display = 'none';
            };
            
            // Click outside modal to close
            window.onclick = (event) => {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
                
            };
        });
}

function loadDeleteContent(type = 'accounts') {
    const endpoint = {
        'accounts': 'get_users.php',
        'modules': 'get_modules.php',
        'assessments': 'get_assessments.php'
    }[type];

    fetch(`${endpoint}?page=${currentPage}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('userTableBody');
            tbody.innerHTML = '';
            
            if (type === 'accounts') {
                renderAccountsTable(data.records);
            } else if (type === 'modules') {
                renderModulesTable(data.records);
            } else {
                renderAssessmentsTable(data.records);
            }
            
            updatePagination(data.currentPage, data.totalPages);
        });
}
function renderAccountsTable(accounts) {
    const tbody = document.getElementById('userTableBody');
    accounts.forEach(account => {
        tbody.innerHTML += `
            <tr>
                <td><input type="checkbox" class="record-select" value="${account.id}"></td>
                <td>${account.id}</td>
                <td>${account.first_name} ${account.last_name}</td>
                <td>${account.student_id}</td>
                <td>${account.email}</td>
                <td><span class="role-badge ${account.role}">${account.role}</span></td>
                <td>${new Date(account.signup_date).toLocaleDateString()}</td>
                <td>
                    <button onclick="viewRecord('account', ${account.id})" class="btn-view">View</button>
                    <button onclick="deleteRecord('account', ${account.id})" class="btn-delete">Delete</button>
                </td>
            </tr>
        `;
    });
}
function deleteRecord(type, id) {
    const endpoints = {
        'account': 'delete_user.php',
        'module': 'delete_module.php',
        'assessment': 'delete_assessment.php'
    };

    if (confirm(`Are you sure you want to delete this ${type}?`)) {
        fetch(endpoints[type], {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Success', `${type} deleted successfully`, 'success');
                loadDeleteContent(type + 's');
            } else {
                showNotification('Error', data.message, 'error');
            }
        });
    }
}
function bulkDelete() {
    const selectedIds = Array.from(document.querySelectorAll('.record-select:checked'))
        .map(checkbox => checkbox.value);
    
    const type = document.getElementById('userStatusFilter').value;
    
    if (selectedIds.length === 0) {
        showNotification('Warning', 'Please select items to delete', 'warning');
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
                loadDeleteContent(type);
            } else {
                showNotification('Error', data.message, 'error');
            }
        });
    }
}
document.getElementById('userStatusFilter').addEventListener('change', function() {
    loadDeleteContent(this.value);
});



function loadContentTable(type = 'accounts', page = 1, search = '') {
    const endpoints = {
        'accounts': 'get_users.php',
        'modules': 'get_modules.php',
        'assessments': 'get_assessments.php'
    };

    fetch(`${endpoints[type]}?page=${page}&search=${search}`)
        .then(response => response.json())
        .then(data => {
            hideAllTables();
            document.getElementById(`${type}Table`).style.display = 'block';
            
            const tbody = document.getElementById(`${type}TableBody`);
            tbody.innerHTML = '';
            
            data.records.forEach(record => {
                tbody.innerHTML += generateTableRow(type, record);
            });
            
            updatePagination(data.currentPage, data.totalPages);
        });
}
function getCurrentPage() {
    return parseInt(document.getElementById('currentPage').textContent.split(' ')[1]);
}

function showNotification(title, message, type) {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <h4>${title}</h4>
        <p>${message}</p>
    `;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}

function updateModalContent(user) {
    const fields = {
        'ProfilePic': 'src',
        'Id': 'textContent',
        'Name': 'textContent',
        'StudentId': 'textContent',
        'Email': 'textContent',
        'Sex': 'textContent',
        'Role': 'textContent',
        'Phone': 'textContent',
        'SignupDate': 'textContent'
    };

    Object.entries(fields).forEach(([field, property]) => {
        const element = document.getElementById(`user${field}`);
        if (element) {
            if (field === 'Name') {
                element[property] = `${user.first_name} ${user.last_name}`;
            } else if (field === 'SignupDate') {
                element[property] = new Date(user.signup_date).toLocaleDateString();
            } else if (field === 'ProfilePic') {
                element[property] = user.profile_picture;
            } else {
                const key = field.toLowerCase();
                element[property] = user[key] || 'Not set';
            }
        }
    });
}

function confirmDeleteUser(userId) {
    const modal = document.createElement('div');
    modal.className = 'confirmation-modal';
    modal.innerHTML = `
        <div class="modal-content">
            <h3>Confirm Deletion</h3>
            <p>Are you sure you want to delete this user? This action cannot be undone.</p>
            <div class="modal-actions">
                <button onclick="deleteUser(${userId})" class="btn-danger">Delete</button>
                <button onclick="this.closest('.confirmation-modal').remove()" class="btn-cancel">Cancel</button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}

function deleteUser(userId) {
    fetch('admin_functions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'deleteUser',
            userId: userId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Success', 'User deleted successfully', 'success');
            loadUserTable();
            document.querySelector('.confirmation-modal')?.remove();
        } else {
            showNotification('Error', data.message, 'error');
        }
    });
}

// Initialize delete section
document.addEventListener('DOMContentLoaded', () => {
    const deleteSection = document.getElementById('DELETEContent');
    if (deleteSection) {
        loadUserTable();
        
        // Search functionality
        document.getElementById('userSearchInput').addEventListener('input', 
            debounce(() => loadUserTable(1, document.getElementById('userSearchInput').value), 300)
        );
        
        // Status filter
        document.getElementById('userStatusFilter').addEventListener('change', 
            () => loadUserTable(1, document.getElementById('userSearchInput').value, 
                document.getElementById('userStatusFilter').value)
        );
        
        // Select all functionality
        document.getElementById('selectAllUsers').addEventListener('change', e => {
            document.querySelectorAll('.user-select').forEach(checkbox => {
                checkbox.checked = e.target.checked;
            });
        });
    }
});function confirmDelete(id) {
    const isSoftDelete = document.getElementById('softDeleteToggle').checked;
    const message = isSoftDelete ? 
        'Are you sure you want to mark this record as inactive?' : 
        'Are you sure you want to permanently delete this record?';

    if (confirm(message)) {
        deleteRecord(id, isSoftDelete);
    }
}

function deleteRecord(id, isSoftDelete = false) {
    const formData = new FormData();
    formData.append('action', 'deleteRecord');
    formData.append('id', id);
    formData.append('softDelete', isSoftDelete);

    fetch('admin_functions.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadUserAccountInfo();
        }
    });
}

function restoreRecord(id) {
    if (confirm('Are you sure you want to restore this record?')) {
        const formData = new FormData();
        formData.append('action', 'restoreRecord');
        formData.append('id', id);

        fetch('admin_functions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Success', 'Record restored successfully', 'success');
                loadUserAccountInfo();
            } else {
                showNotification('Error', data.message, 'error');
            }
        });
    }
}

function deleteSelectedUsers() {
    const selectedUsers = Array.from(document.querySelectorAll('.user-select:checked'))
        .map(checkbox => checkbox.value);
    
    if (selectedUsers.length === 0) {
        showNotification('Warning', 'Please select users to delete', 'warning');
        return;
    }

    if (confirm(`Are you sure you want to delete ${selectedUsers.length} selected users? This action cannot be undone.`)) {
        fetch('delete_user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `userId=${selectedUsers.join(',')}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Success', 'Selected users deleted successfully', 'success');
                loadUserTable(); 
            } else {
                showNotification('Error', data.message, 'error');
            }
        });
    }
}

function exportSelectedUsers() {
    const selectedUsers = Array.from(document.querySelectorAll('.user-select:checked'))
        .map(checkbox => checkbox.value);
    
    if (selectedUsers.length === 0) {
        showNotification('Warning', 'Please select users to export', 'warning');
        return;
    }

    fetch('admin_functions.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            action: 'exportUsers',
            userIds: selectedUsers
        })
    })
    .then(response => response.blob())
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'users_export.csv';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
    });
}

function viewUser(userId) {
    fetch(`admin_functions.php?action=getUser&id=${userId}`)
        .then(response => response.json())
        .then(data => {
            const modal = createUserModal(data);
            document.body.appendChild(modal);
        });
}

function createUserModal(user) {
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.innerHTML = `
        <div class="modal-content">
            <span class="close">×</span>
            <h2>User Details</h2>
            <div class="user-details">
                <p><strong>Name:</strong> ${user.first_name} ${user.last_name}</p>
                <p><strong>Student ID:</strong> ${user.student_id}</p>
                <p><strong>Email:</strong> ${user.email}</p>
                <p><strong>Role:</strong> ${user.role}</p>
                <p><strong>Signup Date:</strong> ${new Date(user.signup_date).toLocaleDateString()}</p>
            </div>
        </div>
    `;
    
    modal.querySelector('.close').onclick = () => modal.remove();
    return modal;
}

// Add this to your existing admin.js file
document.addEventListener('DOMContentLoaded', function() {
    const moduleSelector = document.getElementById('moduleSelector');
    const updateForm = document.getElementById('moduleQuickUpdateForm');

    if (moduleSelector) {
        moduleSelector.addEventListener('change', function() {
            fetchModuleContent(this.value);
        });
    }

    if (updateForm) {
        updateForm.addEventListener('submit', function(e) {
            e.preventDefault();
            updateModuleContent(new FormData(this));
        });
    }
});

function fetchModuleContent(moduleId) {
    fetch(`get_module_content.php?id=${moduleId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('topic').value = data.topics;
            document.getElementById('subtopic').value = data.subtopics;
            document.getElementById('description').value = data.description;
            
            // Update content pages
            for(let i = 1; i <= 5; i++) {
                document.getElementById(`content_${i}`).value = data[`content_${i}`] || '';
            }
        });
}

function updateModuleContent(formData) {
    fetch('update_module.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Success', 'Module updated successfully', 'success');
        } else {
            showNotification('Error', data.message, 'error');
        }
    });
}

function resetForm() {
    document.getElementById('moduleQuickUpdateForm').reset();
    showNotification('Info', 'Form has been reset', 'info');
}
document.addEventListener('DOMContentLoaded', () => {
    // Initialize modal functionality
    const modal = document.getElementById('userDetailsModal');
    const closeBtn = modal?.querySelector('.close');
    
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });
    }

    // Close modal when clicking outside
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Initialize table functionality
    loadRecords();
    
    // Add search input listener
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(() => loadRecords(1), 300));
    }

    // Add pagination listeners
    const prevButton = document.getElementById('prevPage');
    const nextButton = document.getElementById('nextPage');
    
    if (prevButton && nextButton) {
        prevButton.addEventListener('click', () => {
            const currentPage = parseInt(document.getElementById('currentPage').textContent.split(' ')[1]);
            if (currentPage > 1) loadRecords(currentPage - 1);
        });

        nextButton.addEventListener('click', () => {
            const currentPage = parseInt(document.getElementById('currentPage').textContent.split(' ')[1]);
            loadRecords(currentPage + 1);
        });
    }
});

function initializeModal() {
    const modal = document.getElementById('userDetailsModal');
    const closeBtn = modal?.querySelector('.close');
    
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });
    }

    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
}

function initializeTableControls() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(() => loadRecords(1), 300));
    }

    setupPagination();
}

function setupPagination() {
    const prevButton = document.getElementById('prevPage');
    const nextButton = document.getElementById('nextPage');
    
    if (prevButton && nextButton) {
        prevButton.addEventListener('click', () => {
            const currentPage = getCurrentPage();
            if (currentPage > 1) loadRecords(currentPage - 1);
        });

        nextButton.addEventListener('click', () => {
            const currentPage = getCurrentPage();
            loadRecords(currentPage + 1);
        });
    }
}

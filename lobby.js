/**
 * This code handles the functionality of the lobby page, including:
 * - Sidebar and section handling
 * - Toggling the active state of sidebar buttons and showing the corresponding content sections
 * - Saving and restoring the active section from localStorage
 * - Toggling the sidebar and main content on menu button click
 * - Dropdown functionality for the settings button
 * - Logout button functionality
 * - Light/dark mode toggle
 * - Welcome message for logged-in users
 * - Expense tracker functionality, including input validation, transaction handling, and updating the UI and localStorage
 * - Profile picture upload validation
 /**
 * This code handles the functionality of the lobby page, including:
 * - Sidebar and section handling
 * - Toggling the active state of sidebar buttons and showing the corresponding content sections
 * - Saving and restoring the active section from localStorage
 * - Toggling the sidebar and main content on menu button click
 * - Dropdown functionality for the settings button
 * - Logout button functionality
 * - Light/dark mode toggle
 * - Welcome message for logged-in users
 * - Expense tracker functionality, including input validation, transaction handling, and updating the UI and localStorage
 * - Profile picture upload validation
 */
 function initializeNewFeatures() {
    addSearchFunctionality();
    addDateFilter();
    addCategoryManagement();
    addExportFunction();
    addStatistics();
    addBudgetManagement();
}


function initializeCharts() {
    const ctx = document.getElementById('balance-trend-chart').getContext('2d');
    let chart;

    function updateChart(timeRange) {
        fetch(`get_expenses.php?range=${timeRange}`)
            .then(response => response.json())
            .then(data => {
                const chartData = {
                    labels: data.map(item => new Date(item.transaction_date).toLocaleDateString()),
                    datasets: [{
                        label: 'Income',
                        data: data.filter(item => item.amount >= 0).map(item => item.amount),
                        borderColor: '#2ecc71',
                        tension: 0.4,
                        fill: false
                    }, {
                        label: 'Expenses',
                        data: data.filter(item => item.amount < 0).map(item => Math.abs(item.amount)),
                        borderColor: '#e74c3c',
                        tension: 0.4,
                        fill: false
                    }]
                };

                if (chart) {
                    chart.destroy();
                }

                chart = new Chart(ctx, {
                    type: 'line',
                    data: chartData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                display: true,
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    maxRotation: 45,
                                    minRotation: 45
                                },
                                scrollbar: {
                                    enabled: true
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: value => '₱' + value.toLocaleString()
                                }
                            }
                        },
                        plugins: {
                            zoom: {
                                pan: {
                                    enabled: true,
                                    mode: 'x'
                                },
                                zoom: {
                                    wheel: {
                                        enabled: true
                                    },
                                    pinch: {
                                        enabled: true
                                    },
                                    mode: 'x'
                                }
                            }
                        }
                    }
                });
            });
    }
    function updateStatisticsSummary(data) {
        const avgIncome = calculateAverage(data.filter(item => item.amount >= 0).map(item => item.amount));
        const avgExpense = calculateAverage(data.filter(item => item.amount < 0).map(item => Math.abs(item.amount)));
        const growth = calculateGrowth(data);

        document.getElementById('avg-income').textContent = '₱' + avgIncome.toLocaleString(undefined, {maximumFractionDigits: 2});
        document.getElementById('avg-expense').textContent = '₱' + avgExpense.toLocaleString(undefined, {maximumFractionDigits: 2});
        
        const netGrowthElement = document.getElementById('net-growth');
        netGrowthElement.textContent = growth.toFixed(1) + '%';
        netGrowthElement.className = growth >= 0 ? 'positive' : 'negative';
    }

    document.getElementById('timeRangeSelector').addEventListener('change', (e) => {
        updateChart(e.target.value);
    });

    updateChart('daily');
}

// Add to DOMContentLoaded
document.addEventListener('DOMContentLoaded', () => {
    initializeCharts();
});

function calculateStatistics(data) {
    const totalIncome = data
        .filter(item => item.amount >= 0)
        .reduce((sum, item) => sum + parseFloat(item.amount), 0);
    
    const totalExpense = Math.abs(data
        .filter(item => item.amount < 0)
        .reduce((sum, item) => sum + parseFloat(item.amount), 0));
    
    const netBalance = totalIncome - totalExpense;
    const savingsRate = (totalIncome > 0) ? ((totalIncome - totalExpense) / totalIncome * 100) : 0;
    
    return {
        totalIncome,
        totalExpense,
        netBalance,
        savingsRate,
        averageIncome: totalIncome / (data.filter(item => item.amount >= 0).length || 1),
        averageExpense: totalExpense / (data.filter(item => item.amount < 0).length || 1),
        transactionCount: data.length
    };
}
function initializeBudgetGoals() {
    fetch('get_budget_goals.php')
        .then(response => response.json())
        .then(data => {
            updateGoalsUI(data);
            setupGoalNotifications(data);
        });
}

function initializeRecentActivity() {
    const activityList = document.getElementById('recent-activity-list');
    
    function fetchRecentActivity() {
        fetch('get_recent_activity.php')
            .then(response => response.json())
            .then(activities => {
                activityList.innerHTML = activities.map(activity => `
                    <li class="activity-item ${activity.amount >= 0 ? 'income' : 'expense'}">
                        <div class="activity-content">
                            <span class="activity-text">${activity.text}</span>
                            <span class="activity-date">${new Date(activity.transaction_date).toLocaleDateString()}</span>
                            <span class="activity-amount">₱${Math.abs(activity.amount).toFixed(2)}</span>
                        </div>
                    </li>
                `).join('');
            });
    }

    // Initial load
    fetchRecentActivity();
    
    // Refresh every 30 seconds
    setInterval(fetchRecentActivity, 30000);
}
document.addEventListener('DOMContentLoaded', () => {
    initializeRecentActivity();
});
function updateStatisticsDisplay(stats) {
    const formatter = new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP'
    });

    document.getElementById('avg-income').textContent = formatter.format(stats.averageIncome);
    document.getElementById('avg-expense').textContent = formatter.format(stats.averageExpense);
    
    const netGrowthElement = document.getElementById('net-growth');
    netGrowthElement.textContent = `${stats.savingsRate.toFixed(1)}%`;
    netGrowthElement.className = stats.savingsRate >= 0 ? 'positive' : 'negative';
}

function initializeStatistics() {
    const timeRangeSelector = document.getElementById('timeRangeSelector');
    
    timeRangeSelector.addEventListener('change', () => {
        fetch(`get_expenses.php?range=${timeRangeSelector.value}`)
            .then(response => response.json())
            .then(data => {
                const stats = calculateStatistics(data);
                updateStatisticsDisplay(stats);
                updateChart(data);
            });
    });
}

// Add to your existing DOMContentLoaded event listener
document.addEventListener('DOMContentLoaded', () => {
    initializeStatistics();
    // Trigger initial statistics load
    fetch('get_expenses.php?range=daily')
        .then(response => response.json())
        .then(data => {
            const stats = calculateStatistics(data);
            updateStatisticsDisplay(stats);
        });
});



function initializeAnalytics() {
    const ctx = document.getElementById('balance-trend-chart').getContext('2d');
    let balanceChart;
    
    function updateChart(timeRange) {
        fetch(`get_expenses.php?range=${timeRange}`)
            .then(response => response.json())
            .then(data => {
                const chartData = processChartData(data, timeRange);
                createBalanceChart(ctx, chartData, timeRange);
            });
    }

    document.getElementById('timeRangeSelector').addEventListener('change', (e) => {
        updateChart(e.target.value);
    });

    // Initial load with daily view
    updateChart('daily');
}
function createBalanceChart(ctx, chartData, timeRange) {
    const maxValue = Math.max(...chartData.incomes, ...chartData.expenses);
    const stepSize = Math.ceil(maxValue / 5);

    if (window.balanceChart) {
        window.balanceChart.destroy();
    }

    window.balanceChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [
                {
                    label: 'Income',
                    data: chartData.incomes,
                    borderColor: '#2ecc71',
                    backgroundColor: 'rgba(46, 204, 113, 0.1)',
                    tension: 0.4,
                    fill: false
                },
                {
                    label: 'Expenses',
                    data: chartData.expenses,
                    borderColor: '#e74c3c',
                    backgroundColor: 'rgba(231, 76, 60, 0.1)',
                    tension: 0.4,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => '₱' + value.toLocaleString(),
                        stepSize: stepSize
                    },
                    title: {
                        display: true,
                        text: 'Amount (₱)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ₱' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            }
        }
    });
}




function getTimeRangeLabel(timeRange) {
    const labels = {
        'daily': 'Last 30 Days',
        'weekly': 'Last 12 Weeks',
        'monthly': 'Last 12 Months',
        'annually': 'Last 5 Years'
    };
    return labels[timeRange] || timeRange;
}
function processChartData(transactions, timeRange) {
    const labels = [];
    const incomes = [];
    const expenses = [];
    
    transactions.forEach(transaction => {
        const date = new Date(transaction.transaction_date);
        const amount = parseFloat(transaction.amount);
        
        if (amount >= 0) {
            incomes.push(amount);
            expenses.push(0);
        } else {
            incomes.push(0);
            expenses.push(Math.abs(amount));
        }
        
        labels.push(date.toLocaleDateString());
    });
    
    return { labels, incomes, expenses };
}
function initializeExpenseTracker() {
    // ... existing code ...

    function updateDashboardOverview(transactions) {
        let totalIncome = 0;
        let totalExpense = 0;
        
        transactions.forEach(transaction => {
            const amount = parseFloat(transaction.amount);
            if (amount >= 0) {
                totalIncome += amount;
            } else {
                totalExpense += Math.abs(amount);
            }
        });

        const totalBalance = totalIncome - totalExpense;

        document.getElementById('dashboard-balance').textContent = `₱${totalBalance.toFixed(2)}`;
        document.getElementById('total-income').textContent = `₱${totalIncome.toFixed(2)}`;
        document.getElementById('total-expense').textContent = `₱${totalExpense.toFixed(2)}`;
    }
}

// Add this to your existing initialization
document.addEventListener('DOMContentLoaded', () => {
    
    initializeAnalytics();
    initializeNewFeatures();
});
document.addEventListener("DOMContentLoaded", () => {
    initializeSidebar();
    initializeSettings();
    
    loadTransactions();
});

// Sidebar and section handling
const sidebarItems = document.querySelectorAll('.sidebar-item');
const contentSections = document.querySelectorAll('.content');
const profileButton = document.getElementById('profileButton');
const dashboardButton = document.getElementById('dashboardButton');
const settingsButton = document.getElementById("settingsButton");
const settingsDropdown = document.getElementById("settingsDropdown");
const logoutButton = document.getElementById("logoutButton");
const themeToggle = document.getElementById("themeToggle");
const menuButton = document.getElementById("menuButton");
const sidebar = document.querySelector('.sidebar');
const mainContent = document.querySelector('.main-content');

function initializeSidebar() {
    // Function to toggle active state and show corresponding section
    function toggleActiveButton(buttonId) {
        sidebarItems.forEach(item => {
            item.classList.remove("active");
        });
        const activeButton = document.getElementById(buttonId);
        if (activeButton) {
            activeButton.classList.add("active");
        }
    }

    // Function to show the corresponding content based on the clicked sidebar item
    function showSection(sectionId) {
        contentSections.forEach(section => {
            section.style.display = "none";
        });
        const targetSection = document.getElementById(`${sectionId}Content`);
        if (targetSection) {
            targetSection.style.display = "block";
        }
    }

    // Sidebar item click event listener
    sidebarItems.forEach(item => {
        item.addEventListener("click", () => {
            const sectionId = item.id.replace('Button', '');  // Get section ID from button ID
            toggleActiveButton(item.id);  // Toggle active state on the sidebar button
            showSection(sectionId);  // Show the corresponding section
            localStorage.setItem("activeSection", sectionId);  // Save the active section to localStorage
        });
    });

    // Initialize the state: Display the saved section and highlight the corresponding button
    const savedSection = localStorage.getItem("activeSection") || "dashboard";  // Default to 'dashboard' if no saved section
    toggleActiveButton(`${savedSection}Button`);
    showSection(savedSection);

    // Toggle sidebar on menu button click
    menuButton?.addEventListener('click', () => {
        sidebar.classList.toggle('open');
        mainContent.classList.toggle('shifted');
        sidebar.classList.toggle('active');
    });

    // Welcome message if logged in
    const isLoggedIn = '<?php echo isset($_SESSION["loggedin"]) ? "true" : "false"; ?>';
    if (isLoggedIn === 'true') {
        const welcomeMessage = document.createElement("div");
        welcomeMessage.className = "popup-message";
        welcomeMessage.innerText = "Welcome to the Lobby! You have successfully logged in.";
        document.body.prepend(welcomeMessage);
        setTimeout(() => welcomeMessage.remove(), 3000);
    }
}

function initializeSettings() {
    // Dropdown functionality for settings button
    settingsButton?.addEventListener("click", (e) => {
        e.stopPropagation();
        settingsDropdown.classList.toggle("show");
    });

    // Close the dropdown if clicked outside
    window.addEventListener("click", (event) => {
        if (!event.target.closest(".dropdown")) {
            settingsDropdown.classList.remove("show");
        }
    });

    // Logout button functionality
    logoutButton?.addEventListener("click", () => {
        window.location.href = "logout.php";  // Redirect to logout page
    });

    // Light/Dark mode toggle
    themeToggle?.addEventListener("click", () => {
        document.body.classList.toggle("dark-mode");
    });

    // Profile picture upload validation
    const profileForm = document.getElementById('profile-form');
    profileForm?.addEventListener('submit', function(e) {
        const fileInput = document.getElementById('profile_picture');
        const file = fileInput?.files[0];

        if (file) {
            const allowedTypes = ['image/jpeg', 'image/png'];
            const maxSize = 2 * 1024 * 1024;

            if (!allowedTypes.includes(file.type)) {
                e.preventDefault();
                document.getElementById('upload-status').textContent = 'Invalid file type. Please upload a JPG or PNG image.';
            } else if (file.size > maxSize) {
                e.preventDefault();
                document.getElementById('upload-status').textContent = 'File too large. Maximum size is 2MB.';
            }
        }
    });
}

function initializeExpenseTracker() {
    const balance = document.getElementById("balance");
    const moneyPlus = document.getElementById("money-plus");
    const moneyMinus = document.getElementById("money-minus");
    const form = document.getElementById("form");
    const text = document.getElementById("text");
    const amount = document.getElementById("amount");
    const incomeBtn = document.getElementById('income-btn');
    const expenseBtn = document.getElementById('expense-btn');
    const incomeList = document.getElementById('income-list');
    const expenseList = document.getElementById('expense-list');

    let transactionType = 'income';

    // Transaction type toggle with visual feedback
    incomeBtn?.addEventListener('click', () => {
        transactionType = 'income';
        incomeBtn.classList.add('active');
        expenseBtn.classList.remove('active');
        amount.classList.remove('expense-input');
        amount.classList.add('income-input');
    });

    expenseBtn?.addEventListener('click', () => {
        transactionType = 'expense';
        expenseBtn.classList.add('active');
        incomeBtn.classList.remove('active');
        amount.classList.remove('income-input');
        amount.classList.add('expense-input');
    });

    form?.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (!validateForm()) return;
        
        const formData = new FormData();
        formData.append('text', text.value.trim());
        formData.append('amount', Math.abs(parseFloat(amount.value)));
        formData.append('type', transactionType);
        formData.append('transaction_date', document.getElementById('transaction_date').value);
    
        try {
            const response = await fetch('save_expense.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                resetForm();
                loadTransactions(); // This will now update both UI and dashboard
                showFeedback('Transaction saved successfully!', 'success');
            }
        } catch (error) {
            showFeedback('Transaction error occurred');
        }
    });
    

    function validateForm() {
        if (!text.value.trim()) {
            showFeedback('Please enter a description');
            text.focus();
            return false;
        }
        if (!amount.value || parseFloat(amount.value) <= 0) {
            showFeedback('Please enter a valid amount');
            amount.focus();
            return false;
        }
        return true;
    }

    function resetForm() {
        text.value = '';
        amount.value = '';
        text.focus();
    }

    function showFeedback(message, type = 'error') {
        const feedback = document.createElement('div');
        feedback.className = `feedback ${type}`;
        feedback.textContent = message;
        form.insertAdjacentElement('beforebegin', feedback);
        setTimeout(() => feedback.remove(), 3000);
    }

    function loadTransactions() {
        console.log('Loading transactions...');
        fetch('get_expenses.php')
            .then(response => response.json())
            .then(transactions => {
                console.log('Transactions loaded:', transactions);
                updateUI(transactions);
                updateDashboardOverview(transactions);
            })
            .catch(error => {
                console.error('Failed to load transactions:', error);
            });
    }
    
    // Add this inside initializeExpenseTracker()
function removeTransaction(id, element) {
    if (confirm('Are you sure you want to delete this transaction?')) {
        fetch('delete_expense.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${id}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                element.remove();
                loadTransactions(); // Refresh balances
            }
        });
    }
}
function updateBalanceView() {
    const timeRange = document.getElementById('balanceRangeSelector').value;
    fetch(`get_balance_overview.php?range=${timeRange}`)
        .then(response => response.json())
        .then(data => {
            const formatter = new Intl.NumberFormat('en-PH', {
                style: 'currency',
                currency: 'PHP',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            let balance, income, expense;
            
            switch(timeRange) {
                case 'weekly':
                    balance = data.weekly_balance;
                    income = data.weekly_income;
                    expense = data.weekly_expense;
                    break;
                case 'monthly':
                    balance = data.monthly_balance;
                    income = data.monthly_income;
                    expense = data.monthly_expense;
                    break;
                case 'annually':
                    balance = data.yearly_balance;
                    income = data.yearly_income;
                    expense = data.yearly_expense;
                    break;
                default:
                    balance = data.total_balance;
                    income = data.total_income;
                    expense = data.total_expense;
            }

            document.getElementById('dashboard-balance').textContent = formatter.format(balance || 0);
            document.getElementById('total-income').textContent = formatter.format(income || 0);
            document.getElementById('total-expense').textContent = formatter.format(expense || 0);
        });
}

// Add event listener for the select element
document.getElementById('balanceRangeSelector').addEventListener('change', updateBalanceView);

// Initial load with total view
document.addEventListener('DOMContentLoaded', () => {
    updateBalanceView();
});


function updateUI(transactions) {
    let totalIncome = 0;
    let totalExpense = 0;
    
    incomeList.innerHTML = '';
    expenseList.innerHTML = '';

    transactions.forEach(transaction => {
        const amount = parseFloat(transaction.amount);
        if (amount >= 0) {
            totalIncome += amount;
            incomeList.appendChild(createTransactionElement(transaction));
        } else {
            totalExpense += Math.abs(amount);
            expenseList.appendChild(createTransactionElement(transaction));
        }
    });

    const totalBalance = totalIncome - totalExpense;
    
    balance.textContent = `₱${totalBalance.toFixed(2)}`;
    moneyPlus.textContent = `₱${totalIncome.toFixed(2)}`;
    moneyMinus.textContent = `₱${totalExpense.toFixed(2)}`;
}

function createTransactionElement(transaction) {
    const li = document.createElement('li');
    li.dataset.id = transaction.id;
    li.className = parseFloat(transaction.amount) >= 0 ? 'income' : 'expense';
    
    // Format the transaction date using the transaction_date field
    const date = new Date(transaction.transaction_date);
    const formattedDate = date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
    
    li.innerHTML = `
        <span class="transaction-text">${transaction.text}</span>
        <span class="transaction-date">${formattedDate}</span>
        <span class="transaction-amount">₱${Math.abs(parseFloat(transaction.amount)).toFixed(2)}</span>
        <button class="delete-btn" data-id="${transaction.id}">×</button>
    `;
    
    li.querySelector('.delete-btn').addEventListener('click', function() {
        removeTransaction(transaction.id, li);
    });
    
    return li;
}


    // Initial load
    loadTransactions();

    // Add these new functions to your existing initializeExpenseTracker()

    // Search and filter transactions
    function addSearchFunctionality() {
        const searchInput = document.createElement('input');
        searchInput.type = 'text';
        searchInput.placeholder = 'Search transactions...';
        searchInput.className = 'search-transactions';
        
        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            filterTransactions(searchTerm);
        });
        
        form.insertBefore(searchInput, form.firstChild);
    }

    function filterTransactions(searchTerm) {
        const transactionItems = document.querySelectorAll('.list li');
        transactionItems.forEach(item => {
            const text = item.querySelector('.transaction-text').textContent.toLowerCase();
            item.style.display = text.includes(searchTerm) ? 'flex' : 'none';
        });
    }

    // Date range filter
    function addDateFilter() {
        const dateFilter = document.createElement('div');
        dateFilter.className = 'date-filter';
        dateFilter.innerHTML = `
            <input type="date" id="startDate">
            <input type="date" id="endDate">
            <button onclick="filterByDate()">Filter by Date</button>
        `;
        form.insertBefore(dateFilter, form.firstChild);
    }

    function filterByDate() {
        const startDate = new Date(document.getElementById('startDate').value);
        const endDate = new Date(document.getElementById('endDate').value);
        
        fetch(`get_expenses.php?start=${startDate.toISOString()}&end=${endDate.toISOString()}`)
            .then(response => response.json())
            .then(transactions => updateUI(transactions));
    }

    // Category management
    function addCategoryManagement() {
        const categories = ['Food', 'Transport', 'Bills', 'Entertainment', 'Others'];
        const categorySelect = document.createElement('select');
        categorySelect.id = 'category';
        categorySelect.innerHTML = categories.map(cat => 
            `<option value="${cat.toLowerCase()}">${cat}</option>`
        ).join('');
        
        form.insertBefore(categorySelect, amount.parentElement);
    }

    // Export transactions
    function addExportFunction() {
        const exportBtn = document.createElement('button');
        exportBtn.textContent = 'Export to CSV';
        exportBtn.onclick = exportTransactions;
        form.appendChild(exportBtn);
    }

    function exportTransactions() {
        fetch('get_expenses.php')
            .then(response => response.json())
            .then(transactions => {
                const csv = convertToCSV(transactions);
                downloadCSV(csv, 'transactions.csv');
            });
    }

    function convertToCSV(transactions) {
        const headers = ['Date', 'Description', 'Amount', 'Type'];
        const rows = transactions.map(t => [
            new Date(t.date).toLocaleDateString(),
            t.text,
            t.amount,
            t.type
        ]);
        return [headers, ...rows].map(row => row.join(',')).join('\n');
    }

    function downloadCSV(csv, filename) {
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        a.click();
        window.URL.revokeObjectURL(url);
    }

    // Statistics and Analytics
    function addStatistics() {
        const statsContainer = document.createElement('div');
        statsContainer.className = 'statistics';
        statsContainer.innerHTML = `
            <h3>Statistics</h3>
            <div id="monthly-summary"></div>
            <div id="category-breakdown"></div>
            <div id="spending-trends"></div>
        `;
        document.querySelector('.expense-tracker-container').appendChild(statsContainer);
    }

    function updateStatistics(transactions) {
        // Monthly summary
        const monthlySummary = calculateMonthlySummary(transactions);
        displayMonthlySummary(monthlySummary);
        
        // Category breakdown
        const categoryBreakdown = calculateCategoryBreakdown(transactions);
        displayCategoryBreakdown(categoryBreakdown);
        
        // Spending trends
        displaySpendingTrends(transactions);
    }

    // Budget Management
    function addBudgetManagement() {
        const budgetSection = document.createElement('div');
        budgetSection.className = 'budget-management';
        budgetSection.innerHTML = `
            <h3>Budget Management</h3>
            <input type="number" id="monthlyBudget" placeholder="Set monthly budget">
            <div id="budgetProgress"></div>
            <div id="budgetAlerts"></div>
        `;
        document.querySelector('.expense-tracker-container').appendChild(budgetSection);
    }

    // Initialize all new features
    function initializeNewFeatures() {
        addSearchFunctionality();
        addDateFilter();
        addCategoryManagement();
        addExportFunction();
        addStatistics();
        addBudgetManagement();
    }

    // Add this to your existing initialization
    document.addEventListener('DOMContentLoaded', () => {
        initializeExpenseTracker();
        initializeNewFeatures();
    });
}
// Global delete function
window.removeTransaction = function(id) {
    if (confirm('Are you sure you want to delete this transaction?')) {
        const formData = new FormData();
        formData.append('id', id);

        fetch('delete_expense.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadTransactions();
                showFeedback('Transaction deleted successfully', 'success');
            }
        })


        
        .catch(error => {
            console.error('Delete error:', error);
            showFeedback('Failed to delete transaction');
        });
    }
};document.addEventListener("DOMContentLoaded", () => {
    const sidebarItems = document.querySelectorAll('.sidebar-item');
    const contentSections = document.querySelectorAll('.content');
    
    // Function to toggle active state and show the corresponding section
    function toggleActiveButton(buttonId) {
        sidebarItems.forEach(item => {
            item.classList.remove("active");
        });
        const activeButton = document.getElementById(buttonId);
        if (activeButton) {
            activeButton.classList.add("active");
        }
    }

    // Function to show the corresponding content based on the clicked sidebar item
    function showSection(sectionId) {
        contentSections.forEach(section => {
            section.style.display = "none";
        });

        const targetSection = document.getElementById(`${sectionId}Content`);
        if (targetSection) {
            targetSection.style.display = "block";
        }
    }

    // Sidebar item click event listener
    sidebarItems.forEach(item => {
        item.addEventListener("click", () => {
            const sectionId = item.id.replace('Button', '');
            toggleActiveButton(item.id);
            showSection(sectionId);
            // Save the active section to localStorage
            localStorage.setItem("activeSection", sectionId);
        });
    });

    // Initialize the state: Display the dashboard section and highlight the Dashboard button
    const savedSection = localStorage.getItem("activeSection") || "dashboard";
    toggleActiveButton(`${savedSection}Button`);
    showSection(savedSection);
});
document.addEventListener("DOMContentLoaded", () => {
    const sidebarItems = document.querySelectorAll('.sidebar-item');
    const contentSections = document.querySelectorAll('.content');
    
    // Function to toggle active state and show the corresponding section
    function toggleActiveButton(buttonId) {
        sidebarItems.forEach(item => {
            item.classList.remove("active");
        });
        const activeButton = document.getElementById(buttonId);
        if (activeButton) {
            activeButton.classList.add("active");
        }
    }
    document.getElementById("expense-form").addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent the default form submission
    
        // Get the form data
        let formData = new FormData(this);
    
        // Send AJAX request to PHP script
        fetch("path_to_your_php_script.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Expense added successfully!");
            } else {
                alert("Failed to add expense: " + (data.error || "Unknown error"));
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    });
    // Function to show the corresponding content based on the clicked sidebar item
    function showSection(sectionId) {
        contentSections.forEach(section => {
            section.style.display = "none";
        });

        const targetSection = document.getElementById(`${sectionId}Content`);
        if (targetSection) {
            targetSection.style.display = "block";
        }
    }

    // Sidebar item click event listener
    sidebarItems.forEach(item => {
        item.addEventListener("click", () => {
            const sectionId = item.id.replace('Button', '');  // Get section ID from button ID
            toggleActiveButton(item.id);  // Toggle active state on the sidebar button
            showSection(sectionId);  // Show the corresponding section

            // Save the active section to localStorage
            localStorage.setItem("activeSection", sectionId);
        });
    });

    // Initialize the state: Display the saved section and highlight the corresponding button
    const savedSection = localStorage.getItem("activeSection") || "dashboard";  // Default to 'dashboard' if no saved section
    toggleActiveButton(`${savedSection}Button`);
    showSection(savedSection);
});document.addEventListener("DOMContentLoaded", () => {
    const sidebarItems = document.querySelectorAll('.sidebar-item');
    const contentSections = document.querySelectorAll('.content');
    
    // Function to toggle active state and show the corresponding section
    function toggleActiveButton(buttonId) {
        sidebarItems.forEach(item => {
            item.classList.remove("active");
        });
        const activeButton = document.getElementById(buttonId);
        if (activeButton) {
            activeButton.classList.add("active");
        }
    }

    // Function to show the corresponding content based on the clicked sidebar item
    function showSection(sectionId) {
        contentSections.forEach(section => {
            section.style.display = "none";
        });

        const targetSection = document.getElementById(`${sectionId}Content`);
        if (targetSection) {
            targetSection.style.display = "block";
        }
    }

    // Sidebar item click event listener
    sidebarItems.forEach(item => {
        item.addEventListener("click", () => {
            const sectionId = item.id.replace('Button', '');  // Get section ID from button ID
            toggleActiveButton(item.id);  // Toggle active state on the sidebar button
            showSection(sectionId);  // Show the corresponding section

            // Save the active section to localStorage
            localStorage.setItem("activeSection", sectionId);
        });
    });

    // Initialize the state: Display the saved section and highlight the corresponding button
    const savedSection = localStorage.getItem("activeSection") || "dashboard";  // Default to 'dashboard' if no saved section
    toggleActiveButton(`${savedSection}Button`);
    showSection(savedSection);
});
document.addEventListener("DOMContentLoaded", () => {
    const settingsButton = document.getElementById("settingsButton");
    const settingsDropdown = document.getElementById("settingsDropdown");

    // Toggle the dropdown visibility when the settings button is clicked
    settingsButton.addEventListener("click", (event) => {
        // Prevent the click event from propagating to other elements
        event.stopPropagation();
        // Toggle the "show" class on the dropdown
        settingsDropdown.classList.toggle("show");
    });

    // Close the dropdown if clicked outside of it
    window.addEventListener("click", (event) => {
        if (!event.target.closest(".dropdown")) {
            settingsDropdown.classList.remove("show");
        }
    });


    // Light/Dark mode toggle functionality
    const themeToggle = document.getElementById("themeToggle");
    themeToggle.addEventListener("click", () => {
        document.body.classList.toggle("dark-mode");
    });
  
    // Logout button functionality
    const logoutButton = document.getElementById("logoutButton");
    logoutButton.addEventListener("click", () => {
        window.location.href = "logout.php";  // Redirect to logout page
    });
});

// Add this to initializeExpenseTracker()
amount?.addEventListener('input', (e) => {
    const value = e.target.value;
    if (value && !isNaN(value) && parseFloat(value) < 0) {
        e.target.value = Math.abs(value);
    }
});
function initializeAnalytics() {
    const balanceCtx = document.getElementById('balance-trend-chart').getContext('2d');
    const incomeExpenseCtx = document.getElementById('income-vs-expense-chart').getContext('2d');
    let charts = {};

    function updateCharts(timeRange) {
        fetch(`get_expenses.php?range=${timeRange}`)
            .then(response => response.json())
            .then(data => {
                const processedData = processChartData(data);
                
                // Balance Trend Chart
                if (charts.balance) charts.balance.destroy();
                charts.balance = new Chart(balanceCtx, {
                    type: 'line',
                    data: {
                        labels: processedData.dates,
                        datasets: [{
                            label: 'Balance Trend',
                            data: processedData.balances,
                            borderColor: '#2ecc71',
                            backgroundColor: 'rgba(46, 204, 113, 0.1)',
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Balance Over Time'
                            }
                        }
                    }
                });

                // Income vs Expense Chart
                if (charts.incomeExpense) charts.incomeExpense.destroy();
                charts.incomeExpense = new Chart(incomeExpenseCtx, {
                    type: 'bar',
                    data: {
                        labels: processedData.dates,
                        datasets: [
                            {
                                label: 'Income',
                                data: processedData.incomes,
                                backgroundColor: '#2ecc71'
                            },
                            {
                                label: 'Expenses',
                                data: processedData.expenses,
                                backgroundColor: '#e74c3c'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            });
    }

    // Initialize charts with daily view
    updateCharts('daily');

    // Add event listener for time range selector
    document.getElementById('timeRangeSelector').addEventListener('change', (e) => {
        updateCharts(e.target.value);
    });
}
document.addEventListener('DOMContentLoaded', () => {
    initializeExpenseTracker();
    loadTransactions(); // This will update both UI and dashboard on page load
});
// Add to your existing DOMContentLoaded event
document.addEventListener('DOMContentLoaded', () => {
    
    initializeAnalytics();
});
function initializeModules() {
    document.querySelectorAll('.topic-header').forEach(header => {
        header.addEventListener('click', () => {
            const container = header.nextElementSibling;
            const icon = header.querySelector('.expand-icon');
            
            container.style.display = container.style.display === 'none' ? 'block' : 'none';
            icon.textContent = container.style.display === 'none' ? '▼' : '▲';
            
            // Animate the progress bars when visible
            if (container.style.display === 'block') {
                container.querySelectorAll('.progress-fill').forEach(fill => {
                    setTimeout(() => {
                        fill.style.width = '0%';
                        requestAnimationFrame(() => {
                            fill.style.width = Math.random() * 100 + '%';
                        });
                    }, 100);
                });
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', initializeModules);

function updateModuleProgress() {
    const moduleId = topicId;
    const currentPage = currentContent;
    let progress = JSON.parse(localStorage.getItem('moduleProgress')) || {};
    
    // Calculate progress percentage based on current page (20% per page)
    const newProgress = Math.min(currentPage * 20, 100);
    
    // Update progress in localStorage
    progress[moduleId] = newProgress;
    localStorage.setItem('moduleProgress', JSON.stringify(progress));
}

// Initialize progress tracking on page load
document.addEventListener('DOMContentLoaded', () => {
    updateModuleProgress();
});

// Add event listeners for navigation buttons
document.querySelectorAll('.nav-btn').forEach(button => {
    button.addEventListener('click', () => {
        // Progress will update automatically when page reloads
        // due to the DOMContentLoaded event
    });
});

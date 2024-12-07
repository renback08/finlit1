-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2024 at 02:14 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `user_signup`
--

-- --------------------------------------------------------

--
-- Table structure for table `assessment_results`
--

CREATE TABLE `assessment_results` (
  `result_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `completion_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assessment_results`
--

INSERT INTO `assessment_results` (`result_id`, `user_id`, `module_id`, `score`, `completion_date`) VALUES
(1, 7, 12, 0, '2024-12-05 06:34:55'),
(2, 7, 12, 0, '2024-12-05 06:35:03'),
(3, 7, 12, 0, '2024-12-05 06:36:49'),
(4, 7, 12, 1, '2024-12-05 07:11:29'),
(5, 7, 12, 1, '2024-12-05 07:14:09'),
(6, 7, 12, 1, '2024-12-05 07:14:12'),
(7, 7, 12, 0, '2024-12-05 07:14:17'),
(8, 7, 12, 10, '2024-12-05 07:30:08'),
(9, 7, 12, 0, '2024-12-05 07:30:45'),
(10, 7, 12, 2, '2024-12-05 07:32:24'),
(11, 7, 12, 2, '2024-12-05 07:40:30'),
(12, 7, 12, 2, '2024-12-05 07:43:20'),
(13, 7, 12, 2, '2024-12-05 07:45:00'),
(14, 7, 12, 2, '2024-12-05 08:01:10'),
(15, 7, 12, 2, '2024-12-05 08:01:37'),
(16, 3, 12, 5, '2024-12-05 20:12:19'),
(17, 5, 12, 10, '2024-12-05 21:35:16'),
(18, 5, 12, 2, '2024-12-05 22:09:59'),
(19, 5, 12, 7, '2024-12-05 22:22:07'),
(20, 3, 12, 3, '2024-12-06 01:51:01'),
(21, 3, 12, 5, '2024-12-06 01:54:19');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `user_id` int(6) UNSIGNED NOT NULL,
  `text` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` enum('income','expense') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `transaction_date` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `user_id`, `text`, `amount`, `type`, `created_at`, `transaction_date`) VALUES
(1, 4, '0', 4412.00, 'income', '2024-11-27 16:00:44', '2024-11-28'),
(2, 4, 'f', -1231.00, 'expense', '2024-11-27 18:27:48', '2024-11-28'),
(6, 5, 'money', 4213.00, 'income', '2024-11-27 18:38:23', '2024-11-28'),
(12, 5, 'dd', -1234.00, 'expense', '2024-11-28 14:23:00', '2024-11-28'),
(13, 5, 'dd', 4124.00, 'income', '2024-11-28 14:52:36', '2024-11-28'),
(14, 5, 'dd', 4124.00, 'income', '2024-11-28 14:52:36', '2024-11-28'),
(15, 5, 'dd', 4124.00, 'income', '2024-11-28 14:52:36', '2024-11-28'),
(16, 5, 'd', -124.00, 'expense', '2024-11-28 14:52:51', '2024-11-28'),
(17, 5, 'd', -124.00, 'expense', '2024-11-28 14:52:51', '2024-11-28'),
(18, 5, 'd', -124.00, 'expense', '2024-11-28 14:52:51', '2024-11-28'),
(19, 5, 'money', -13.00, 'expense', '2024-11-28 14:57:38', '2024-11-27'),
(20, 5, 'money', -13.00, 'expense', '2024-11-28 14:57:39', '2024-11-27'),
(21, 5, 'money', -13.00, 'expense', '2024-11-28 14:57:39', '2024-11-27'),
(22, 5, 'money', -123.00, 'expense', '2024-11-28 15:21:18', '2024-11-21'),
(23, 5, 'money', -123.00, 'expense', '2024-11-28 15:21:18', '2024-11-21'),
(24, 5, 'money', -123.00, 'expense', '2024-11-28 15:21:18', '2024-11-21'),
(25, 5, 'money', 12.00, 'income', '2024-11-28 15:21:33', '2024-11-21'),
(26, 5, 'money', 12.00, 'income', '2024-11-28 15:21:33', '2024-11-21'),
(27, 5, 'money', 12.00, 'income', '2024-11-28 15:21:33', '2024-11-21'),
(28, 5, 'asd', 4.00, 'income', '2024-11-28 15:25:51', '2024-11-27'),
(29, 5, 'asd', 4.00, 'income', '2024-11-28 15:25:51', '2024-11-27'),
(30, 5, 'aafff', 412.00, 'income', '2024-11-28 15:26:15', '2024-11-27'),
(31, 5, 'aafff', 412.00, 'income', '2024-11-28 15:26:15', '2024-11-27'),
(34, 4, 'money', 1.00, 'income', '2024-11-28 15:57:35', '2024-11-14'),
(35, 4, '1', 12.00, 'income', '2024-11-28 15:57:57', '2024-11-12'),
(36, 4, 'asd', -123.00, 'expense', '2024-11-29 23:34:27', '2024-12-06'),
(37, 4, '123', 214.00, 'income', '2024-11-29 23:34:41', '2025-01-01'),
(38, 5, 'money', 23564.00, 'income', '2024-11-30 01:39:59', '2024-05-16'),
(39, 4, 'asd', 34.00, 'income', '2024-11-30 03:28:24', '2024-11-06'),
(40, 4, 'hotdog', -444213.00, 'expense', '2024-11-30 15:10:34', '2024-11-13'),
(47, 3, 'money', 24.00, 'income', '2024-12-05 20:29:07', '2024-11-11'),
(50, 12, 'Food', -50.00, 'expense', '2024-12-07 04:10:48', '2024-12-07');

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `module_id` int(11) NOT NULL,
  `topics` varchar(255) DEFAULT NULL,
  `subtopics` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `content_1` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `content_2` text DEFAULT NULL,
  `content_3` text DEFAULT NULL,
  `content_4` text DEFAULT NULL,
  `content_5` text DEFAULT NULL,
  `has_assessment` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`module_id`, `topics`, `subtopics`, `description`, `content_1`, `created_at`, `content_2`, `content_3`, `content_4`, `content_5`, `has_assessment`) VALUES
(2, 'Financial Management', 'Introduction to Financial Management', '', '', '2024-11-29 06:44:38', '', '', '', '', 1),
(9, 'Savings', 'The Importance of Saving', NULL, NULL, '2024-11-30 07:32:28', NULL, NULL, NULL, NULL, 1),
(12, 'Budgeting ', 'Basic Concepts of Budgeting', '', '<h1>What is Budgeting?</h1>\r\n<p>Budgeting is the process of developing a financial plan that allows you to allocate your income effectively. It’s not about cutting back on spending but making intentional financial decisions that align with your values and goals.</p>\r\n\r\n<h2>Key Concepts of Budgeting</h2>\r\n<ul>\r\n    <li><strong>Awareness:</strong> Understand where your money comes from and where it’s going.</li>\r\n    <li><strong>Purpose:</strong> Use money as a tool to achieve your life goals.</li>\r\n    <li><strong>Flexibility:</strong> Adapt your budget as your financial situation changes.</li>\r\n</ul>\r\n\r\n<h2>Advantages of Budgeting</h2>\r\n<ul>\r\n    <li><strong>Improving Financial Health:</strong> Break the debt cycle and meet payments on time.</li>\r\n    <li><strong>Saving for Goals:</strong> Direct your income toward future savings and aspirations.</li>\r\n    <li><strong>Readiness:</strong> Build an emergency fund for unexpected situations, such as medical or car repair bills.</li>\r\n</ul>\r\n', '2024-11-30 08:52:02', '<h1>Understanding the Elements of a Budget</h1>\r\n\r\n<h2>1. Income</h2>\r\n<p>Your income is the sum of money you earn from various sources, such as:</p>\r\n<ul>\r\n    <li><strong>Primary Income:</strong> Examples include salary or freelance work.</li>\r\n    <li><strong>Secondary Income:</strong> Examples include investments or side jobs.</li>\r\n</ul>\r\n<p><strong>Tip:</strong> Always use your net income (after taxes) when creating a budget to ensure accuracy.</p>\r\n\r\n<h2>2. Expenses</h2>\r\n<p>Expenses are categorized into three main types:</p>\r\n<ul>\r\n    <li><strong>Fixed Expenses:</strong> Consistent costs like rent, subscriptions, or loan payments.</li>\r\n    <li><strong>Variable Expenses:</strong> Changing costs such as utilities, groceries, or gas.</li>\r\n    <li><strong>Periodic Expenses:</strong> Infrequent costs like insurance premiums or annual school fees.</li>\r\n</ul>\r\n\r\n<h2>3. Savings</h2>\r\n<p>Savings involve setting aside a portion of your income for future needs. Key savings categories include:</p>\r\n<ul>\r\n    <li><strong>Emergency Fund:</strong> Aim for at least 3–6 months’ worth of expenses.</li>\r\n    <li><strong>Retirement Savings:</strong> Contribute to pension funds or investment accounts for your future.</li>\r\n    <li><strong>Specific Goals:</strong> Save for targeted purposes, such as vacations, education, or big purchases.</li>\r\n</ul>\r\n\r\n<h2>4. Financial Goals</h2>\r\n<p>Financial goals help shape your budget and guide your financial decisions. They can be categorized as:</p>\r\n<ul>\r\n    <li><strong>Short-Term Goals:</strong> For example, saving for a smartphone within 3 months.</li>\r\n    <li><strong>Medium-Term Goals:</strong> For example, building a ₱100,000 emergency fund within 2 years.</li>\r\n    <li><strong>Long-Term Goals:</strong> For example, owning a house or ensuring a comfortable retirement.</li>\r\n</ul>\r\n', '<h2>Step-by-Step Budgeting</h2>\r\n\r\n<h2>Step 1: Calculate Your Income</h2>\r\n<p>List all sources of income. If your income varies, use the average of the last six months.</p>\r\n\r\n<h2>Step 2: Track Your Spending</h2>\r\n<p>For a month, write down each expense. You can record this:</p>\r\n<p>• Apps (such as Mint or You Need A Budget).</p>\r\n<p>• Spreadsheets or budgeting journals.</p>\r\n<p><strong>Example:</strong> If you spend ₱2,500 on coffee every month, consider brewing in your own place.</p>\r\n\r\n<h2>Step 3: Prioritize Needs Over Wants</h2>\r\n<p>Identify the difference between \"must-haves\" and \"nice-to-haves.\"</p>\r\n<p><strong>Needs:</strong> Shelter, utilities, food.</p>\r\n<p><strong>Wants:</strong> Subscriptions, dining out, entertainment.</p>\r\n\r\n<h2>Step 4: Budget</h2>\r\n<p>Use the 50/30/20 Rule:</p>\r\n<p>• 50% for needs: Rent, food, bills.</p>\r\n<p>• 30% for wants: Hobbies, dining out.</p>\r\n<p>• 20% for savings/debt repayment: Emergency funds, investments.</p>\r\n<p><strong>Example:</strong></p>\r\n<p>If your net income is ₱20,000:</p>\r\n<p>₱10,000 goes to needs.</p>\r\n<p>₱6,000 to wants.</p>\r\n<p>₱4,000 to savings and debt.</p>\r\n\r\n<h2>Step 5: Track and Revise</h2>\r\n<p>• Review your budget monthly.</p>\r\n<p>• Adjust for new expenses or income changes.</p>\r\n', '<h1>Step-by-Step Budgeting</h1>\r\n\r\n<h2>Step 1: Calculate Your Income</h2>\r\n<p>Start by listing all sources of income. If your income varies, use the average of the last six months to get an accurate estimate.</p>\r\n\r\n<h2>Step 2: Track Your Spending</h2>\r\n<p>For one month, record all your expenses. You can do this using:</p>\r\n<ul>\r\n    <li>Apps (e.g., Mint or You Need A Budget).</li>\r\n    <li>Spreadsheets or budgeting journals.</li>\r\n</ul>\r\n<p><strong>Example:</strong> If you spend ₱2,500 on coffee every month, consider brewing coffee at home to save money.</p>\r\n\r\n<h2>Step 3: Prioritize Needs Over Wants</h2>\r\n<p>Understand the difference between essential and non-essential expenses:</p>\r\n<ul>\r\n    <li><strong>Needs:</strong> Shelter, utilities, and food.</li>\r\n    <li><strong>Wants:</strong> Subscriptions, dining out, and entertainment.</li>\r\n</ul>\r\n\r\n<h2>Step 4: Create a Budget</h2>\r\n<p>Use the 50/30/20 Rule to allocate your income:</p>\r\n<ul>\r\n    <li>50% for needs: Rent, food, and bills.</li>\r\n    <li>30% for wants: Hobbies, dining out, and leisure.</li>\r\n    <li>20% for savings or debt repayment: Emergency funds, investments, and loans.</li>\r\n</ul>\r\n<p><strong>Example:</strong> If your net income is ₱20,000:</p>\r\n<ul>\r\n    <li>₱10,000 goes to needs.</li>\r\n    <li>₱6,000 to wants.</li>\r\n    <li>₱4,000 to savings and debt repayment.</li>\r\n</ul>\r\n\r\n<h2>Step 5: Track and Revise</h2>\r\n<p>Regularly monitor your budget:</p>\r\n<ul>\r\n    <li>Review your budget monthly.</li>\r\n    <li>Adjust for any changes in expenses or income.</li>\r\n</ul>\r\n', '<h1>Overcoming Obstacles and Staying Motivated</h1>\r\n\r\n<h2>Challenges in Budgeting</h2>\r\n<ul>\r\n    <li><strong>Irregular Income:</strong> For freelancers or business owners, plan your budget around the lowest expected income to ensure stability.</li>\r\n    <li><strong>Unexpected Expenses:</strong> Prepare for the unexpected by maintaining a robust emergency fund.</li>\r\n    <li><strong>Staying Disciplined:</strong> Stay on track with tools like reminders, automated savings, or accountability partners.</li>\r\n</ul>\r\n\r\n<h2>Tips for Success</h2>\r\n<ul>\r\n    <li><strong>Automate Savings:</strong> Set up automatic transfers to save effortlessly and consistently.</li>\r\n    <li><strong>Reward Milestones:</strong> Celebrate achievements, such as reaching a savings goal, to stay motivated.</li>\r\n    <li><strong>Monthly Reviews:</strong> Regularly review and adjust your budget to reflect changes in your income or expenses.</li>\r\n</ul>\r\n\r\n<h2>Inspiration to Keep Going</h2>\r\n<ul>\r\n    <li>\"A budget is more than numbers on a page; it\'s a plan for your life.\" – <em>Dave Ramsey</em></li>\r\n    <li>\"Do not save what is left after spending but spend what is left after saving.\" – <em>Warren Buffett</em></li>\r\n</ul>\r\n', 0),
(13, 'Budgeting', 'Steps in Creating a Budget', '', '\r\n<h1>Understanding Your Income</h1>\r\n\r\n<p>Your budget starts with your income, the money you regularly earn. Always know exactly how much money you bring in before deciding anything else.</p>\r\n\r\n<h2>1. Net Income</h2>\r\n<p>Always work with your <strong>net income</strong> (after taxes), not your gross income (before taxes). This will give you a more accurate view of what you can realistically spend.</p>\r\n<ul>\r\n    <li><strong>Example:</strong> If you have ₱25,000 monthly before taxes and your deductions are ₱5,000, your net income will be ₱20,000.</li>\r\n</ul>\r\n\r\n<h2>2. Multiple Income Sources</h2>\r\n<p>If you have more than one source of income, such as a second job, freelance work, or rental income, add up the amounts from all sources to get your total income.</p>\r\n<ul>\r\n    <li><strong>Example:</strong>\r\n        <ul>\r\n            <li>Primary job: ₱20,000</li>\r\n            <li>Freelance work: ₱5,000</li>\r\n            <li><strong>Total monthly income:</strong> ₱25,000</li>\r\n        </ul>\r\n    </li>\r\n</ul>\r\n\r\n<h2>3. Irregular Income</h2>\r\n<p>If your income varies (e.g., freelancers, entrepreneurs), calculate the average monthly income over several months to come up with a stable figure.</p>\r\n<ul>\r\n    <li><strong>Example:</strong> If your income varies between ₱15,000 and ₱30,000, calculate the average monthly income over the past three months:</li>\r\n    <ul>\r\n        <li>(₱15,000 + ₱30,000 + ₱20,000) / 3 = ₱21,667</li>\r\n    </ul>\r\n</ul>\r\n', '2024-12-03 04:28:00', '<h1>Track and Categorize Your Expenses</h1>\r\n\r\n<p>Now that you know your income, it\'s time to understand your spending habits. By tracking your expenses, you can identify where your money is going, how much you\'re spending, and whether you\'re overspending in any category.</p>\r\n\r\n<h2>1. Fixed Expenses</h2>\r\n<p>Fixed expenses are regular, unchanging costs that occur every month. These typically include:</p>\r\n<ul>\r\n    <li>Rent or mortgage</li>\r\n    <li>Utility bills (electricity, water, internet)</li>\r\n    <li>Loan repayments</li>\r\n    <li>Insurance premiums</li>\r\n    <li>Subscriptions (Netflix, Spotify, etc.)</li>\r\n</ul>\r\n<p><strong>Example:</strong> If you spend ₱10,000 on rent, ₱2,000 on utilities, and ₱1,000 on insurance, your fixed expenses total ₱13,000.</p>\r\n\r\n<h2>2. Variable Expenses</h2>\r\n<p>Variable expenses fluctuate month-to-month and can be harder to predict. They include:</p>\r\n<ul>\r\n    <li>Groceries</li>\r\n    <li>Transportation (gas, public transport)</li>\r\n    <li>Dining out</li>\r\n    <li>Entertainment (movies, shopping)</li>\r\n</ul>\r\n<p><strong>Example:</strong> If you usually spend ₱3,000 on groceries, ₱1,500 on transportation, and ₱1,000 on eating out, your variable expenses total ₱5,500.</p>\r\n\r\n<h2>3. Periodic Expenses</h2>\r\n<p>Periodic expenses are less frequent but can accumulate over time. They include:</p>\r\n<ul>\r\n    <li>Annual insurance premiums</li>\r\n    <li>Vehicle registration or maintenance</li>\r\n    <li>Holiday gifts or travel expenses</li>\r\n</ul>\r\n<p><strong>Example:</strong> If you spend ₱5,000 a year on insurance, that’s ₱417 per month to account for in your budget.</p>\r\n\r\n<h2>Tracking Tip</h2>\r\n<p>Use apps like Mint, YNAB (You Need A Budget), or a simple spreadsheet to categorize and track your spending easily.</p>\r\n', '<h1>Make Your Budget Plan</h1>\r\n\r\n<p>With all that you have learned about your income, expenses, and financial goals, it\'s now time to create your budget. This involves allocating a specific amount of your income to each category.</p>\r\n\r\n<h2>1. The 50/30/20 Rule</h2>\r\n<p>This rule divides your income into three categories:</p>\r\n<ul>\r\n    <li><strong>50% Needs:</strong> Necessary expenditures like rent, utilities, food, insurance, etc.</li>\r\n    <li><strong>30% Wants:</strong> Luxuries such as dining out, entertainment, shopping, etc.</li>\r\n    <li><strong>20% Savings/Debt Repayment:</strong> Use this portion for emergency funds, long-term savings, or paying off debt.</li>\r\n</ul>\r\n<p><strong>Example:</strong> If your monthly income is ₱20,000:</p>\r\n<ul>\r\n    <li><strong>Needs (50%):</strong> ₱10,000</li>\r\n    <li><strong>Wants (30%):</strong> ₱6,000</li>\r\n    <li><strong>Savings/Debt (20%):</strong> ₱4,000</li>\r\n</ul>\r\n\r\n<h2>2. Zero-Based Budgeting</h2>\r\n<p>In this method, every peso is assigned a specific purpose. After covering all expenses, savings, and debt payments, your budget should \"balance\" to zero.</p>\r\n<p><strong>Example:</strong></p>\r\n<ul>\r\n    <li><strong>Income:</strong> ₱20,000</li>\r\n    <li><strong>Fixed expenses:</strong> ₱10,000</li>\r\n    <li><strong>Variable expenses:</strong> ₱5,000</li>\r\n    <li><strong>Savings:</strong> ₱3,000</li>\r\n    <li><strong>Debt repayment:</strong> ₱2,000</li>\r\n    <li><strong>Total:</strong> ₱20,000</li>\r\n</ul>\r\n\r\n<h2>3. Envelope System</h2>\r\n<p>This method involves using cash for various categories. When the money in an envelope runs out, you can’t spend any more in that category for the month.</p>\r\n<p><strong>Example:</strong> You put ₱2,000 for groceries in an envelope. Once it\'s used up, no more grocery shopping for the month.</p>\r\n', '<h1>Set Your Financial Goals</h1>\r\n\r\n<p>Setting financial goals gives your budget purpose and direction. Clear goals make it easier to prioritize where your money should go and help keep you motivated. Without goals, it can be difficult to stay on track.</p>\r\n\r\n<h2>1. Short-Term Goals</h2>\r\n<p>Short-term goals are those you hope to achieve within 1 year. They are typically smaller and more immediate.</p>\r\n<ul>\r\n    <li><strong>Examples:</strong></li>\r\n    <ul>\r\n        <li>Saving ₱10,000 for a vacation in 6 months.</li>\r\n        <li>Paying off ₱5,000 in credit card debt.</li>\r\n    </ul>\r\n</ul>\r\n\r\n<h2>2. Short and Medium-Term Goals</h2>\r\n<p>These goals are generally accomplished within a time frame of 1–5 years and are often related to bigger financial needs.</p>\r\n<ul>\r\n    <li><strong>Examples:</strong></li>\r\n    <ul>\r\n        <li>Having a ₱50,000 emergency fund within two years.</li>\r\n        <li>Building up savings for a down payment on a car or a house.</li>\r\n    </ul>\r\n</ul>\r\n\r\n<h2>3. Long-Term Goals</h2>\r\n<p>Long-term goals are those that take more than 5 years to achieve and often require significant financial planning and saving.</p>\r\n<ul>\r\n    <li><strong>Examples:</strong></li>\r\n    <ul>\r\n        <li>Setting up for retirement with over a million pesos.</li>\r\n        <li>Planning for a child\'s college education.</li>\r\n    </ul>\r\n</ul>\r\n\r\n<h2>SMART Goal Setting</h2>\r\n<p>Use the SMART framework when setting financial goals. This ensures your goals are clear and achievable:</p>\r\n<ul>\r\n    <li><strong>Specific:</strong> Clearly define your goal.</li>\r\n    <li><strong>Measurable:</strong> Make sure you can track your progress.</li>\r\n    <li><strong>Achievable:</strong> Set realistic goals based on your situation.</li>\r\n    <li><strong>Relevant:</strong> Ensure your goal aligns with your long-term objectives.</li>\r\n    <li><strong>Time-bound:</strong> Set a deadline for achieving your goal.</li>\r\n</ul>\r\n\r\n<h3>Example:</h3>\r\n<p><strong>SMART Goal:</strong> Save ₱50,000 as an emergency fund in 12 months by saving ₱4,167 monthly.</p>\r\n', '<h1>Monitor, Adjust, and Review</h1>\r\n\r\n<p>Your budget is a living document. Life changes, and so should your budget. Regularly review your progress, compare actual spending to your budgeted amounts, and make adjustments as necessary.</p>\r\n\r\n<h2>1. Track Spending</h2>\r\n<p>Keep track of every expense, so you can see where your money is going. You can use various tools to help you stay on top of your spending:</p>\r\n<ul>\r\n    <li>Apps like Mint or You Need A Budget (YNAB).</li>\r\n    <li>Spreadsheets for detailed tracking.</li>\r\n    <li>A simple notebook for jotting down expenses.</li>\r\n</ul>\r\n\r\n<h2>2. Make Adjustments</h2>\r\n<p>If you’re overspending in one category, it’s important to adjust your budget accordingly:</p>\r\n<ul>\r\n    <li>For example, if you’re spending too much on dining out, reduce that amount and reallocate it to savings or debt repayment.</li>\r\n    <li>Revisit your budget every month to ensure that you are staying within your financial limits.</li>\r\n</ul>\r\n\r\n<h2>3. Stay Motivated</h2>\r\n<p>It can be easy to lose focus, so use these strategies to stay on track:</p>\r\n<ul>\r\n    <li>Set up reminders for your monthly budget reviews.</li>\r\n    <li>Celebrate milestones, such as reaching a savings goal or paying off a debt.</li>\r\n</ul>\r\n', 0),
(14, 'Budgeting', 'Techniques and Tools for Budgeting', NULL, '<h1>Introduction to Budgeting Tools</h1>\r\n\r\n<p>Budgeting can be simple or complex, depending on your financial needs and goals. Fortunately, there are many tools and techniques—both digital and traditional—that can help you organize your finances. These tools streamline the budgeting process, making it easier to track your spending and save for your goals.</p>\r\n\r\n<h2>Popular Budgeting Tools and Techniques</h2>\r\n<p>Below, we explore some of the most popular tools and techniques for budgeting:</p>\r\n<ul>\r\n    <li><strong>Digital Tools:</strong> Apps and software that automate tracking and offer detailed analysis of your spending.</li>\r\n    <li><strong>Traditional Methods:</strong> Manual tracking using spreadsheets, journals, or the envelope system for cash budgeting.</li>\r\n</ul>\r\n<p>These tools can help you create a budget that aligns with your financial goals, ensuring you stay on track towards achieving them.</p>\r\n', '2024-12-03 04:42:55', '<h2>Digital Budgeting Tools</h2>\r\n\r\n<h3>1. Budgeting Apps and Software</h3>\r\n\r\n<h4>• Mint</h4>\r\n<p><strong>Overview:</strong> Mint is a free app that tracks spending, categorizes transactions, and provides insights into your finances.</p>\r\n<p><strong>Key Features:</strong></p>\r\n<ul>\r\n    <li>Real-time tracking</li>\r\n    <li>Bill reminders</li>\r\n    <li>Credit score monitoring</li>\r\n</ul>\r\n<p><strong>How it Helps:</strong> Ideal for beginners and those who want an all-in-one solution to monitor finances and avoid overspending.</p>\r\n\r\n<h4>• YNAB (You Need A Budget)</h4>\r\n<p><strong>Overview:</strong> A paid app that focuses on zero-based budgeting, wherein every dollar has a job.</p>\r\n<p><strong>Key Features:</strong></p>\r\n<ul>\r\n    <li>Goal setting</li>\r\n    <li>Expense forecasting</li>\r\n</ul>\r\n<p><strong>How it Helps:</strong> Ideal for those seeking structured and disciplined budgeting.</p>\r\n\r\n<h4>• PocketGuard</h4>\r\n<p><strong>Overview:</strong> Makes tracking budgets simpler by showing the disposable income you have left after accounting for essential expenses.</p>\r\n<p><strong>Key Features:</strong></p>\r\n<ul>\r\n    <li>Automatic categorization</li>\r\n    <li>Easy-to-read spending insights</li>\r\n</ul>\r\n<p><strong>How it Helps:</strong> Great for users who want to avoid overspending without dealing with complicated tracking.</p>\r\n\r\n<h4>• GoodBudget</h4>\r\n<p><strong>Overview:</strong> A virtual envelope system for cash-based budgeting.</p>\r\n<p><strong>Key Features:</strong></p>\r\n<ul>\r\n    <li>Envelope-style budgeting</li>\r\n    <li>Debt tracking</li>\r\n</ul>\r\n<p><strong>How it Helps:</strong> Great for those who prefer traditional methods like envelope budgeting but want a digital solution.</p>\r\n\r\n<h3>2. Spreadsheets</h3>\r\n<h4>• Google Sheets & Excel</h4>\r\n<p><strong>Overview:</strong> Both are very customizable tools for manual budgeting. They offer a variety of templates or enable you to customize your own.</p>\r\n<p><strong>Key Features:</strong></p>\r\n<ul>\r\n    <li>Custom categories</li>\r\n    <li>Automatic calculation</li>\r\n    <li>Data sharing for joint accounts</li>\r\n</ul>\r\n<p><strong>How it Helps:</strong> Suited for users who like to manually work on their budget and are used to tracking things manually.</p>\r\n<p><strong>Tip:</strong> There are tons of free templates available, so you don\'t necessarily have to start from scratch.</p>\r\n\r\n<h3>3. Internet Banking Tools</h3>\r\n<h4>• Banking Apps</h4>\r\n<p><strong>Overview:</strong> All the big banks offer direct budgeting tools in-app that automatically categorize their transactions.</p>\r\n<p><strong>Key Features:</strong></p>\r\n<ul>\r\n    <li>Expense categorization</li>\r\n    <li>Bill payment reminders</li>\r\n</ul>\r\n<p><strong>How it Helps:</strong> Suitable for those who want straightforward budget tracking that is integrated directly with their bank account.</p>\r\n', '<h2>Traditional Budgeting Methods</h2>\r\n\r\n<h3>1. Envelope System</h3>\r\n<p>The Envelope System is a cash-based, old-school approach where you divide your monthly income into separate envelopes, each earmarked for a category of expenses (groceries, entertainment, etc.).</p>\r\n<p><strong>How it Works:</strong></p>\r\n<ul>\r\n    <li>Give yourself a set amount of money for each category of spending. When the money runs out, you can no longer spend in that area until the next month.</li>\r\n</ul>\r\n<p><strong>Advantages:</strong> It does a great job of taming discretionary spending and prevents overspending.</p>\r\n<p><strong>Tip:</strong> If you want a digital version, try apps like GoodBudget, which mimics this system.</p>\r\n\r\n<h3>2. Zero-Based Budgeting</h3>\r\n<p>In Zero-Based Budgeting, you assign every peso of your income to an expense, savings, or debt repayment, so your income minus expenses equals zero.</p>\r\n<p><strong>How it Works:</strong></p>\r\n<ul>\r\n    <li>For instance, if you earn ₱30,000, assign specific amounts for rent, utilities, savings, and debt. The total should equal exactly ₱30,000.</li>\r\n</ul>\r\n<p><strong>Benefits:</strong> You make sure that you do not waste a single peso.</p>\r\n<p><strong>Tip:</strong> Suitable for those who like to monitor every single penny and ensure that money is allocated for a specific use.</p>\r\n\r\n<h3>3. 50/30/20 Rule</h3>\r\n<p>The 50/30/20 Rule is very simple in that you divide your income into three buckets: Needs, Wants, and Savings/Debt.</p>\r\n<p><strong>How it Works:</strong></p>\r\n<ul>\r\n    <li>50% for Needs (rent, utilities, food)</li>\r\n    <li>30% for Wants (entertainment, dining out)</li>\r\n    <li>20% for Savings and Debt</li>\r\n</ul>\r\n<p><strong>Benefits:</strong> Simple, easy to follow, and does not require detailed tracking.</p>\r\n<p><strong>Tip:</strong> Ideal for those who would like a balance between living comfortably today while also saving for the future.</p>\r\n', '<h2>Combining Tools and Techniques</h2>\r\n<p>Most people find success by combining digital tools with traditional methods. For instance, you can use an app like Mint to track your overall spending, but for some categories, you may prefer using the Envelope System or Zero-Based Budgeting.</p>\r\n<p><strong>Tip:</strong> Experiment until you find a system that suits your lifestyle. Whether you use digital apps, spreadsheets, or traditional cash envelopes, the key is consistency and regularly reviewing your budget.</p>\r\n', '<h2>Successful Strategies for Putting Your Budget to Work</h2>\r\n<p>Having a budget is only the first step—making it work and sticking to it are where the real work occurs. To help ensure that your budgeting efforts lead to success, here are some effective strategies to consider:</p>\r\n\r\n<h3>1. Set Realistic Goals</h3>\r\n<p>One of the most important steps in budgeting is setting clear, realistic financial goals. These goals provide direction and purpose for your budget. Whether you\'re saving for a vacation, a down payment on a house, or simply building an emergency fund, having a target to work toward makes budgeting easier and more motivating.</p>\r\n<ul>\r\n  <li><strong>Short-Term Goals:</strong> These are goals you aim to achieve in the next few months, such as saving for a new gadget, paying off a small debt, or building a small emergency fund.</li>\r\n  <li><strong>Long-Term Goals:</strong> These include larger financial targets like saving for retirement, purchasing a home, or achieving financial independence. Long-term goals should be broken down into smaller, actionable steps.</li>\r\n</ul>\r\n<p><strong>Tip:</strong> Break down your long-term goals into smaller monthly or yearly targets so they feel more achievable.</p>\r\n\r\n<h3>2. Prioritize Your Spending</h3>\r\n<p>In any budget, there are essential expenses (needs) and discretionary expenses (wants). When funds are limited, it is important to prioritize essentials, such as housing, utilities, food, and health care. After covering necessities, discretionary spending should occur only if money is remaining.</p>\r\n<ul>\r\n  <li><strong>Needs:</strong> These are the most basic, non-discretionary expenses, including rent, food, and transportation.</li>\r\n  <li><strong>Wants:</strong> These are less essential, discretionary expenditures, for example, entertainment, dining out, etc. They may add life to your living but shouldn\'t take priority over basic needs.</li>\r\n</ul>\r\n<p><strong>Tip:</strong> Check your discretionary spending periodically. Are there any areas where you can cut back to help you meet your goals? You won’t miss the little luxuries when you see how much they’re affecting your finances.</p>\r\n\r\n<h3>3. Establish an Emergency Fund</h3>\r\n<p>Unexpected expenses—such as medical bills, car repairs, or job loss—can derail your budget. Having an emergency fund in place will help protect you from the financial stress that comes with unexpected costs.</p>\r\n<ul>\r\n  <li><strong>How Much to Save:</strong> A good rule of thumb is to aim for three to six months\' worth of living expenses in an emergency fund. This will give you a cushion in case of sudden income loss or significant unexpected costs.</li>\r\n</ul>\r\n<p><strong>Tip:</strong> Start small and build up gradually. Even saving a small percentage of your income each month can help you reach your emergency fund goal over time.</p>\r\n\r\n<h3>4. Automate Your Budgeting</h3>\r\n<p>One of the best ways to make sure that your budget remains constant is by automation. This involves setting up automatic transfers for savings, debt payments, or even bill payments so that you eliminate the temptation to skip payments or use money that should be saved.</p>\r\n<ul>\r\n  <li><strong>Automatic Savings:</strong> Create an automatic transfer from a checking account to a savings account or retirement fund each month.</li>\r\n  <li><strong>Bill Payments:</strong> Make sure all your recurring bills (such as utilities, loan payments) are paid automatically so that you do not miss any due dates.</li>\r\n</ul>\r\n<p><strong>Suggestion:</strong> Once you have the transfers set up automatically, you will not have to think about them, and you will make sure that your savings and bills are covered before any discretionary spending.</p>\r\n\r\n<h3>5. Track Your Spending Periodically</h3>\r\n<p>You have to track your spending to stay on top of your budget. It\'s easy to lose track of where your money is going, especially if you\'re using cash or multiple bank accounts.</p>\r\n<ul>\r\n  <li><strong>Check-In Monthly:</strong> Review your budget regularly to check how you are doing in your actual spending against your planned budget. Are you overspending in areas? Are you reaching your savings goals?</li>\r\n  <li><strong>Adjust as Needed:</strong> If you find areas of consistent overspending or if you have changed your goals, adjust your budget. Flexibility is key to maintaining a successful budget.</li>\r\n</ul>\r\n<p><strong>Tip:</strong> Schedule time each month (for example, the last weekend of each month) to review your budget and make any necessary adjustments.</p>\r\n\r\n<h3>6. Stay Motivated with Rewards</h3>\r\n<p>Budgeting doesn\'t have to feel like a burden. One of the best ways to stay motivated is to celebrate small wins along the way. Reward yourself when you reach a milestone, such as hitting a savings goal, paying off a debt, or sticking to your budget for a full month.</p>\r\n<ul>\r\n  <li><strong>Small Rewards:</strong> These could be something you\'ve been wanting but within your budget. For example, a small treat, a night out, or a modest shopping spree after meeting your savings goal.</li>\r\n  <li><strong>Long-Term Rewards:</strong> These could be bigger goals, such as taking that vacation you\'ve been saving for, buying something special, or paying off a major debt.</li>\r\n</ul>\r\n<p><strong>Tip:</strong> Ensure that your rewards fall within your budget. Success can be celebrated and keep you on track and motivated.</p>\r\n\r\n<h3>7. Learn from Mistakes</h3>\r\n<p>No one\'s budget is perfect initially, and there will always be times when you overspend or fall short of the set goals. Instead of thinking that these moments are failures, view them as opportunities to learn and improve.</p>\r\n<ul>\r\n  <li><strong>Reflect:</strong> Take time to reflect on what went wrong. Did you miscalculate an expense? Was a category too tight? Understanding why something didn\'t work out will help you refine your budget in the future.</li>\r\n  <li><strong>Adjust:</strong> If you make a mistake, don\'t be discouraged. Adjust your budget and keep moving forward. Every mistake is a learning experience that can help you become a more skilled budgeter over time.</li>\r\n</ul>\r\n<p><strong>Tip:</strong> Don\'t let your mistakes discourage you; instead, use them as stepping stones for building stronger financial habits.</p>\r\n', 0),
(15, 'Budgeting', 'Budgeting Challenges and Solutions', NULL, '<h2>Overcoming Budgeting Challenges</h2>\r\n<p>Budgeting can be a powerful tool for controlling your finances, but life with money management is not always smooth. Everyone faces setbacks at some point. From surprise expenses to difficulty sticking to a budget, these hurdles can make it hard to stay on track. However, every challenge can be overcome with the right strategies. Below, we’ll explore some of the most common budgeting challenges and the practical solutions to help you tackle them.</p>\r\n', '2024-12-03 04:43:30', '<h2>Challenge: Unexpected Expenses</h2>\r\n<p>Life is full of surprises, and unexpected expenses can derail your budget. These may include emergency medical bills, car repairs, home maintenance, or sudden travel costs.</p>\r\n\r\n<h3>How It Impacts Your Budget:</h3>\r\n<p>Unplanned expenses can lead you to overspend in some categories, and you might need to borrow money or dip into savings that were set aside for other purposes.</p>\r\n\r\n<h3>Solutions:</h3>\r\n\r\n<ul>\r\n  <li>\r\n    <h4>Solution 1: Create an Emergency Fund</h4>\r\n    <p><strong>How It Helps:</strong> An emergency fund acts as a financial safety net that lets you cover unexpected costs without disrupting your regular budget. Ideally, this fund should cover three to six months of living expenses.</p>\r\n    <p><strong>Action Plan:</strong> Start by setting aside a fixed amount each month into your emergency fund. Begin small if necessary—saving ₱1,000 a month can help you create a cushion for the future. Over time, gradually increase the amount as your budget allows.</p>\r\n  </li>\r\n\r\n  <li>\r\n    <h4>Solution 2: Put Contingency in Your Budget</h4>\r\n    <p><strong>How It Helps:</strong> Most people forget to plan for emergencies when they create their budgets. By adding a small contingency fund (around 5-10% of your monthly income), you can avoid overspending on unexpected expenses.</p>\r\n    <p><strong>Action Plan:</strong> When preparing your monthly budget, allocate a portion of your income for unforeseen expenses or \"miscellaneous\" spending. This will help you manage surprise costs without derailing your overall budget.</p>\r\n  </li>\r\n</ul>\r\n\r\n<p><strong>Tip:</strong> If you can\'t start with a large emergency fund, don’t worry. Even small, consistent contributions over time can have a big impact, and you\'ll be surprised by the snowball effect of saving regularly.</p>\r\n', '<h3>Challenge: Sticking to Your Budget</h3>\r\n<p>Sticking to a budget can be challenging, especially when temptations arise, such as a sale, an impromptu trip, or a night out with friends. It\'s easy to justify overspending in the moment, only to regret it later.</p>\r\n\r\n<h4>How It Affects Your Budget:</h4>\r\n<p>Overspending in one category can throw off your entire budget and make you feel like budgeting isn\'t working. This leads to frustration and sometimes even abandoning the budget entirely.</p>\r\n\r\n<h4>Solutions:</h4>\r\n\r\n<ul>\r\n  <li>\r\n    <h4>Solution 1: Setting Realistic Limits</h4>\r\n    <p><strong>How It Helps:</strong> To prevent overspending, it\'s important to set realistic, achievable limits for each spending category. If your limits are too strict, you\'ll feel deprived, which may lead to burnout.</p>\r\n    <p><strong>Action Plan:</strong> Be realistic about how much you actually spend in each category. Start with what feels achievable, and make gradual adjustments as needed. Leave room for occasional treats so you don\'t feel restricted.</p>\r\n  </li>\r\n\r\n  <li>\r\n    <h4>Solution 2: Track Your Spending Regularly</h4>\r\n    <p><strong>How It Helps:</strong> Tracking your spending keeps you on top of your budget in real time. The more aware you are of your spending habits, the easier it is to recognize when you\'re veering off course.</p>\r\n    <p><strong>Action Plan:</strong> Use apps or budgeting software to track your expenses daily. Regularly compare your actual spending with your budget to make sure you\'re staying on track.</p>\r\n  </li>\r\n\r\n  <li>\r\n    <h4>Solution 3: Use the 50/30/20 Rule</h4>\r\n    <p><strong>How It Works:</strong> The 50/30/20 Rule makes budgeting easier by dividing your income into three categories: needs, wants, and savings/debt repayment. This system helps prevent overspending in one area.</p>\r\n    <p><strong>Action Plan:</strong> Stick to the 50/30/20 rule, and make adjustments as needed. If you find yourself overspending in the \"wants\" category, temporarily reduce spending in that area or shift funds from \"savings\" or \"needs\" to stay balanced.</p>\r\n  </li>\r\n</ul>\r\n\r\n<p><strong>Tip:</strong> Avoid temptation by setting up personal challenges, such as \"no-spend days,\" where you commit to not buying anything unnecessary for a certain period of time.</p>\r\n', '<h3>Challenge: Debt Repayment</h3>\r\n<p>Many people struggle with budgeting when they\'re trying to pay off significant debt. Balancing monthly expenses while making debt payments can feel overwhelming, especially when interest rates are high.</p>\r\n\r\n<h4>How It Affects Your Budget:</h4>\r\n<p>Debt payments often require a large portion of your income, leaving you with limited funds for other expenses and savings. This can lead to feelings of frustration and helplessness, making it harder to maintain a budget.</p>\r\n\r\n<h4>Solutions:</h4>\r\n\r\n<ul>\r\n  <li>\r\n    <h4>Solution 1: Prioritize Debt Repayment</h4>\r\n    <p><strong>How It Helps:</strong> Prioritizing debt repayment allows you to save more money in interest payments over time, freeing up funds for other financial goals.</p>\r\n    <p><strong>Action Plan:</strong> Use either the Debt Snowball or Debt Avalanche method. The Debt Snowball method focuses on paying off the smallest balance first, while the Debt Avalanche method targets the highest-interest debts first.</p>\r\n  </li>\r\n\r\n  <li>\r\n    <h4>Solution 2: Modify Your Budget to Include Debt Repayment</h4>\r\n    <p><strong>How It Helps:</strong> If your debt repayment requires more money each month, you may need to adjust other budget categories to stay within your budget.</p>\r\n    <p><strong>Action Plan:</strong> Identify areas where you can temporarily cut back to free up funds for debt repayment. Consider reducing expenses on dining out or entertainment. If needed, increase your income through part-time work or side gigs.</p>\r\n  </li>\r\n\r\n  <li>\r\n    <h4>Solution 3: Consolidate or Refinance Debt</h4>\r\n    <p><strong>How It Helps:</strong> Consolidating or refinancing debt into a single, lower-interest loan can reduce your monthly payments and make your debt more manageable.</p>\r\n    <p><strong>Action Plan:</strong> Research options for consolidating high-interest debts or refinancing loans to obtain better rates and terms.</p>\r\n  </li>\r\n</ul>\r\n\r\n<p><strong>Tip:</strong> Celebrate small victories along the way, such as paying off one debt. This will help maintain motivation during the repayment process.</p>\r\n', '<h3>Challenge: Seasonal Expenses</h3>\r\n<p>Certain seasons can bring seasonal costs such as holidays, school fees, vacations, or weather-related costs (like air conditioning or heating).</p>\r\n\r\n<h4>How It Affects Your Budget:</h4>\r\n<p>These seasonal costs create short-term spending spikes, pushing you to overspend or spend down your savings.</p>\r\n\r\n<h4>Solutions:</h4>\r\n\r\n<ul>\r\n  <li>\r\n    <h4>Solution 1: Plan for Seasonal Expenses Ahead of Time</h4>\r\n    <p><strong>How It Helps:</strong> Planning ahead ensures you\'re not scrambling for money at the last minute. By saving a little each month for these expenses, you can spread the costs over time.</p>\r\n    <p><strong>Action Plan:</strong> List all known seasonal expenses (holidays, birthdays, vacations) and set up a separate savings account or envelope system for them. For example, save ₱500 a month for holiday expenses.</p>\r\n  </li>\r\n\r\n  <li>\r\n    <h4>Solution 2: Create a Separate Category for Variable Expenses</h4>\r\n    <p><strong>How It Helps:</strong> Having a flexible category in your budget for variable expenses helps absorb fluctuations without disturbing the entire budget.</p>\r\n    <p><strong>Action Plan:</strong> Allocate a specific percentage of your income to seasonal expenses, or adjust other categories when these expenses occur to avoid overspending.</p>\r\n  </li>\r\n</ul>\r\n\r\n<p><strong>Tip:</strong> Don’t wait until the month of the expense to start saving for it. Begin early and adjust your budget to accommodate for the increase in spending.</p>\r\n', 0),
(16, 'Budgeting', '\r\nPractical Applications and Real-Life Scenarios', '', '<h3>Budgeting for a Single Person</h3>\r\n<p><strong>Scenario:</strong> Managing Monthly Income and Expenses</p>\r\n<p>As a single individual, your financial obligations may consist of basic needs (rent, utilities, groceries) and discretionary spending (entertainment, dining out, hobbies). Budgeting is critical to ensuring that you live within your means while saving for your goals.</p>\r\n\r\n<h4>Step 1: Identify Your Income</h4>\r\n<p>Determine your total monthly income, including your salary, side gigs, or passive income sources.</p>\r\n\r\n<h4>Step 2: List Your Fixed and Variable Expenses</h4>\r\n<ul>\r\n  <li><strong>Fixed expenses:</strong> These include rent, utilities, subscriptions, and loan payments.</li>\r\n  <li><strong>Variable expenses:</strong> These include groceries, entertainment, transportation, and personal care.</li>\r\n</ul>\r\n\r\n<h4>Step 3: Prioritize Savings</h4>\r\n<p>Allocate 20% of your income (or more if possible) towards savings, even if it\'s just a small amount. Set specific goals like an emergency fund, retirement savings, or future investments.</p>\r\n\r\n<h4>Step 4: Implement the 50/30/20 Rule</h4>\r\n<ul>\r\n  <li><strong>50% Needs:</strong> Rent, utilities, food.</li>\r\n  <li><strong>30% Wants:</strong> Dining out, entertainment, hobbies.</li>\r\n  <li><strong>20% Savings/Debt Repayment:</strong> Emergency fund, retirement, loan payments.</li>\r\n</ul>\r\n\r\n<h4>Application in Real Life:</h4>\r\n<p>For example, Maria is taking home ₱30,000 every month. Her budget will then allocate ₱15,000 for her needs, ₱9,000 for wants, and ₱6,000 for savings and debt repayment. She could save on discretionary expenses if she cuts out non-essential purchases, such as dining out and membership services, thus increasing her savings and gradually building an emergency fund.</p>\r\n', '2024-12-03 04:44:15', '<h3>Budgeting for a Family</h3>\r\n<p><strong>Case:</strong> Managing a Household with Dependents</p>\r\n<p>Budgeting for a family is more complex because it deals with more people, multiple sources of income, and additional responsibilities such as children\'s education, healthcare, and other family-specific needs.</p>\r\n\r\n<h4>Step 1: List Family Income Sources</h4>\r\n<p>Gather total income from all working members of the household. This may include salaries, part-time work, allowances, etc.</p>\r\n\r\n<h4>Step 2: Identify and Categorize Expenses</h4>\r\n<ul>\r\n  <li><strong>Fixed expenses:</strong> Mortgage/rent, utility bills, insurance premiums, school fees.</li>\r\n  <li><strong>Variable expenses:</strong> Groceries, transportation, healthcare, entertainment, family outings.</li>\r\n</ul>\r\n\r\n<h4>Step 3: Set Family Financial Goals</h4>\r\n<p>Prioritize goals like buying a family car, saving for your children\'s education, or preparing for retirement. Create a savings plan to achieve these goals.</p>\r\n\r\n<h4>Step 4: Build an Emergency Fund</h4>\r\n<p>Make sure the emergency fund covers unexpected medical bills, sudden car repairs, or job loss.</p>\r\n\r\n<h4>Step 5: Utilize the Envelope System for Some Expenses</h4>\r\n<p>For discretionary expenses, such as grocery shopping or dining out, apply the cash envelope technique to constrain how much you can spend during a given month.</p>\r\n\r\n<h4>Real Life Application:</h4>\r\n<p>The Santos family earns ₱50,000 per month. After deducting the needs (₱30,000 for mortgage, groceries, and utilities), they have ₱10,000 for savings, ₱5,000 for the children\'s education, and ₱5,000 for discretionary spending like vacations and dining out. They reserve 10% of their income every month to save for future family expenses, such as buying a new car and covering family healthcare costs.</p>\r\n', '<h3>Budgeting to Pay Off Debt</h3>\r\n<p><strong>Example:</strong> Paying Off Credit Cards or Loans</p>\r\n<p>You can work to pay down debt while still covering everyday expenses, as budgeting can help organize your financial priorities and make the process manageable.</p>\r\n\r\n<h4>Step 1: Add Up Your Total Debt</h4>\r\n<p>List all debts, including credit cards, personal loans, student loans, and other liabilities. Record the total amount owed and the interest rate for each.</p>\r\n\r\n<h4>Step 2: Pay High-Interest Debt First</h4>\r\n<p>Pay off high-interest debt first to reduce the cost of debt. Use either the Debt Snowball (pay off smallest debts first) or Debt Avalanche (pay off highest-interest debts first) method.</p>\r\n\r\n<h4>Step 3: Allocate Income for Debt Repayment</h4>\r\n<p>Set aside money in your budget for the payment of debt, prioritizing paying off high-interest credit card balances.</p>\r\n\r\n<h4>Step 4: Cut Down on Non-Essential Expenses</h4>\r\n<p>Reduce discretionary spending (entertainment, dining out, etc.) to free up more funds for debt repayment.</p>\r\n\r\n<h4>Example:</h4>\r\n<p>John has ₱40,000 in credit card debt and ₱10,000 in personal loan debt. Using the Debt Avalanche approach, he pays off the credit card debt first because of the higher interest rate but makes only the minimum payment on the personal loan. He reduces his dining out and entertainment budget to direct ₱5,000 a month toward debt repayment. His goal is to be debt-free in a year.</p>\r\n', '<h3>Budgeting for Special Events (Weddings, Vacations, etc.)</h3>\r\n<p><strong>Scenario:</strong> Budgeting for a Wedding or Vacation</p>\r\n<p>Preparation for special events like a wedding or vacation usually takes much more effort, since such events are likely to include huge, one-time costs that can easily knock off a regular monthly budget.</p>\r\n\r\n<h4>Step 1: Establish a Realistic Budget</h4>\r\n<p>Research and estimate the total costs of the event. Ensure to include all possible costs (venue, food, travel, attire, etc.).</p>\r\n\r\n<h4>Step 2: Save in Advance</h4>\r\n<p>Start saving for the event early. Create a different savings account for the event and start saving a fixed amount each month.</p>\r\n\r\n<h4>Step 3: Find Ways to Save Money</h4>\r\n<p>Look for areas to reduce costs for aspects of the event. For a wedding, a smaller guest list can help. For a vacation, finding a cheap travel offer can make a big difference.</p>\r\n\r\n<h4>Step 4: Review and Make Adjustments</h4>\r\n<p>Review your budget periodically as you approach the date. Make adjustments if actual costs are running over what you had budgeted.</p>\r\n\r\n<h4>Real-Life Example:</h4>\r\n<p>Lisa and Mark are planning to get married next year. Lisa and Mark estimate the overall cost to be ₱300,000. They break down the total amount into monthly savings goals and decide to save ₱25,000 monthly for the next year. They adjust their budget by reducing the size of their guest list and choosing more affordable options for catering and photography to stay within budget.</p>\r\n', '<h3>Budgeting for Retirement Planning</h3>\r\n<p><strong>Scenario:</strong> Creating a Retirement Account</p>\r\n<p>Planning for retirement is a long-term goal that depends on steady contributions and precise budgeting to gain retirement security later in life.</p>\r\n\r\n<h4>Step 1: Evaluate Your Retirement Needs</h4>\r\n<p>Project how much you will require to live on during retirement based on lifestyle choices, healthcare needs, and expected living expenses.</p>\r\n\r\n<h4>Step 2: Save Early</h4>\r\n<p>The sooner you start saving for retirement, the more you can take advantage of compound interest. Target saving at least 10-15% of your income for retirement.</p>\r\n\r\n<h4>Step 3: Take Advantage of Employer Contributions</h4>\r\n<p>If your employer has a retirement plan match, you should take full advantage of it. This is essentially \"free money\" that should be part of your retirement strategy.</p>\r\n\r\n<h4>Step 4: Diversify Investments</h4>\r\n<p>Invest in a combination of stocks, bonds, and other assets to maximize growth. Review your portfolio regularly so that it is in tandem with your retirement goals.</p>\r\n\r\n<h4>Real-Life Example:</h4>\r\n<p>Robert, 35, earns ₱50,000 per month. He puts away 15% of his income (₱7,500) for retirement savings through a company pension plan. As the years pass, Robert ups his contributions as his salary increases and he diversifies his investments between stocks and bonds to ensure his retirement savings are strong.</p>\r\n', 0),
(20, 'Debt Management', 'Tools and Resources for Debt Management', NULL, NULL, '2024-12-03 04:47:27', NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `module_progress`
--

CREATE TABLE `module_progress` (
  `progress_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `current_page` int(11) DEFAULT 1,
  `is_complete` tinyint(1) DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `module_progress`
--

INSERT INTO `module_progress` (`progress_id`, `user_id`, `module_id`, `current_page`, `is_complete`, `updated_at`) VALUES
(1, 7, 2, 5, 1, '2024-12-03 04:00:15'),
(2, NULL, NULL, NULL, 0, '2024-12-01 05:50:53'),
(3, 8, 2, 1, 1, '2024-12-02 06:20:10'),
(4, 7, 12, 5, 1, '2024-12-03 08:45:38'),
(5, 7, 13, 5, 1, '2024-12-03 08:45:43'),
(6, 7, 14, 5, 1, '2024-12-03 08:45:46'),
(7, 7, 15, 5, 1, '2024-12-03 08:45:50'),
(8, 7, 16, 5, 1, '2024-12-03 08:45:53'),
(9, 7, 11, 5, 1, '2024-12-03 07:34:17'),
(10, 7, 17, 4, 1, '2024-12-03 07:48:46'),
(11, 4, 12, 5, 1, '2024-12-07 13:11:29'),
(12, 5, 13, 5, 1, '2024-12-06 01:53:02'),
(13, 5, 29, 2, 1, '2024-12-04 23:58:04'),
(14, 3, 12, 5, 1, '2024-12-06 01:54:29'),
(15, 5, 12, 5, 1, '2024-12-05 22:07:01'),
(16, 5, 14, 5, 1, '2024-12-06 01:41:30'),
(17, 5, 15, 5, 1, '2024-12-06 01:41:46'),
(18, 3, 13, 5, 1, '2024-12-06 01:43:24'),
(19, 3, 14, 5, 1, '2024-12-06 01:50:20'),
(20, 12, 12, 1, 0, '2024-12-06 14:29:23'),
(21, 4, 20, 1, 0, '2024-12-07 13:13:55');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `option_a` text NOT NULL,
  `option_b` text NOT NULL,
  `option_c` text NOT NULL,
  `option_d` text NOT NULL,
  `correct_answer` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`question_id`, `module_id`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`) VALUES
(1, 12, 'What is budgeting primarily about?', 'Limiting spending at all costs', 'Making conscious financial decisions aligned with your goals', 'Avoiding all unnecessary expenses', 'Tracking only major financial transactions', 'B'),
(2, 12, 'Which of the following is NOT a key concept of budgeting?', 'Awareness of income and expenses', 'Purpose-driven financial planning', 'Ignoring minor expenses', 'Flexibility to adjust to changes', 'C'),
(3, 12, 'How does budgeting help achieve financial health?', 'By encouraging overspending on wants', 'By ensuring timely payments and avoiding debt', 'By eliminating all savings for emergencies', 'By focusing solely on short-term goals', 'B'),
(4, 12, 'How much should an emergency fund typically cover?', '1–2 months’ worth of expenses', '3–6 months’ worth of expenses', 'Exactly 1 year’s salary', 'Only unexpected small bills', 'B'),
(5, 12, 'What is the first step in creating a budget?', 'Prioritize wants over needs', 'Assess your income sources', 'Set up a savings account', 'Allocate funds using the 50/30/20 rule', 'B'),
(6, 12, 'Your rent has increased by 15%, but your net income hasn’t changed. You currently allocate 50% to needs, 30% to wants, and 20% to savings.\r\nQuestion:\r\nWhat is the best way to adjust your budget to manage the rent increase?\r\n', 'Reduce the \"wants\" category to accommodate the increased rent', 'Decrease your savings allocation temporarily', 'Take out a loan to cover the increased rent', 'Ignore the rent increase and hope to adjust later', 'A'),
(7, 12, 'You’re saving for a ₱25,000 vacation in 5 months while building a ₱60,000 emergency fund. You currently save ₱10,000 per month.\r\nQuestion:\r\nWhat is the best approach to meet both goals?\r\n', 'Split your savings equally between the two goals each month', 'Focus on the vacation first, then start saving for the emergency fund', 'Allocate ₱5,000 to the vacation and ₱5,000 to the emergency fund monthly', 'Delay the vacation and focus entirely on the emergency fund', 'C'),
(8, 12, 'You have ₱2,000 left after covering your monthly needs. You want to save ₱1,500 and spend the rest on dining out with friends.\r\nQuestion:\r\nWhat is the best approach to balance savings and social activities?\r\n', 'Save the full ₱2,000 and skip dining out', 'Save ₱1,500 and allocate ₱500 for dining out', 'Spend all ₱2,000 on dining out to enjoy with friends', 'Borrow money to dine out and maintain your savings', 'B'),
(9, 12, 'You receive a monthly allowance of ₱5,000. You spend ₱3,000 on fixed expenses like rent and transportation, and ₱1,200 on variable expenses like food and school supplies. The rest is saved.\r\nQuestion:\r\nHow much can you save each month, and what could you do to increase your savings?\r\n', 'Save ₱800 by cutting back on unnecessary purchases', 'Save ₱800 and try to earn extra income', 'Save ₱500 and use the rest for entertainment', 'Save ₱1,000 by skipping fixed expenses', 'B'),
(10, 12, 'You earn money tutoring classmates, making ₱1,500 one month and ₱3,000 another month. You want to save at least ₱1,000 monthly for a school trip.\r\nQuestion:\r\nHow should you plan your budget to meet your savings goal?\r\n', 'Save ₱1,000 every month regardless of income', 'Save half of your earnings each month', 'Spend freely and save only during high-income months', 'Save all earnings from low-income months', 'A'),
(12, 9, 'ano?', 'a', 'b', 'c', 'd', 'A'),
(13, 2, 'sad', 'asd', 'ads', 'asd', 'asd', 'A'),
(14, 20, 'qwe', 'qwe', 'qwe', 'qwe', 'qwe', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(6) UNSIGNED NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `sex` varchar(10) NOT NULL,
  `student_id` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `signup_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone_number` varchar(15) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT 'default.jpg',
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `sex`, `student_id`, `email`, `password`, `signup_date`, `phone_number`, `profile_picture`, `role`) VALUES
(1, 'kyrone', 'jules', 'male', '2022-10694', 'kyronemagsino@gmail.com', '$2y$10$zUyaIzb9rYwK/rkISzIMnudXSAw9qgyTPxmx.tMngoGZsOYxyLW26', '2024-10-19 13:39:17', NULL, 'default.jpg', 'user'),
(2, 'asd', 'sad', 'female', '2022-23234', 'k@gmail.com', '$2y$10$.DDYNYPG1hgWSvRRaWRJiuAfeu.I.fNjM8LFQ/8o6BJWXPpuCHvs6', '2024-10-19 14:38:02', NULL, 'default.jpg', 'user'),
(3, 'sa', 'sa', 'female', '2022-10101', 't@gmail.com', '$2y$10$bN8zSH7XO349hBPzf4wuCeCiOLwaTTkke7TuIZ/FJQvEEPqf0/u.S', '2024-10-21 04:45:45', NULL, 'default.jpg', 'user'),
(4, 'aa', 'aa', 'male', '2022-23122', 'k1@gmail.com', '$2y$10$aRDGuKbPBKJAiFNMATdYtOgd1l0T1y1KS6fzweQcEdxhmxzwxwJSy', '2024-10-26 04:28:19', NULL, 'uploads/gimp layout.jpg', 'admin'),
(5, 'asd', 'asfdas', 'male', '2022-23112', 'km@gmail.com', '$2y$10$PruOgIQalWHRoX99CFoRcurUwu9pskH5/Hy82aoNUMKOTtBtLPMHK', '2024-10-26 04:28:47', '09502227920', 'uploads/progresss.jpg', 'user'),
(6, 'Admin', 'FINLIT', 'male', '2022-12314', 'admin@finlit.com', '$2y$10$Pi6LK1O/KAvvkZJJUs57QOTlxdyw7Kre94u.Mh1hqCHW4q8Y14ls.', '2024-11-26 09:21:19', NULL, 'default.jpg', 'admin'),
(7, 'System', 'Administrator', 'male', 'ADMIN-0001', 'admin@finlit.edu', '$2y$10$Pi6LK1O/KAvvkZJJUs57QOTlxdyw7Kre94u.Mh1hqCHW4q8Y14ls.', '2024-12-02 22:40:57', NULL, 'default.jpg', 'admin'),
(9, 'Admin', 'FINLIT', 'male', 'ADMIN-001', 'admin@finlit.com', '$2y$10$Pi6LK1O/KAvvkZJJUs57QOTlxdyw7Kre94u.Mh1hqCHW4q8Y14ls.', '2024-12-03 18:36:31', NULL, 'default.jpg', 'admin'),
(11, 'sda', 'sad', '', '2022-10699', 't@gmail.com', '$2y$10$FIjWzv/dO0OT9WtIrQGhPOcKmmfIy7LohaOsLdGUStwAnFMtSLGnS', '2024-12-04 23:55:40', NULL, 'default.jpg', 'user'),
(12, 'jhaymuel', 'Lorenzo', 'male', '2022-10666', 'jdlorenzo@ccc.edu.ph', '$2y$10$ctINN6x.R6YHSKdn6eabOespEhnlJzB/Q73mQ5kbFqA6dd9kHaJ06', '2024-12-06 13:39:32', NULL, 'default.jpg', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `user_answers`
--

CREATE TABLE `user_answers` (
  `answer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `user_answer` char(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assessment_results`
--
ALTER TABLE `assessment_results`
  ADD PRIMARY KEY (`result_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`module_id`);

--
-- Indexes for table `module_progress`
--
ALTER TABLE `module_progress`
  ADD PRIMARY KEY (`progress_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `module_id` (`module_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_answers`
--
ALTER TABLE `user_answers`
  ADD PRIMARY KEY (`answer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assessment_results`
--
ALTER TABLE `assessment_results`
  MODIFY `result_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `module_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `module_progress`
--
ALTER TABLE `module_progress`
  MODIFY `progress_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `modules` (`module_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

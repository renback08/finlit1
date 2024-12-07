<!DOCTYPE html>
<?php
/**
 * This HTML code represents the signup form for a web application.
 * It includes fields for first name, last name, sex, student ID, email,
 * password, and password confirmation. The form also includes a checkbox
 * for agreeing to the terms and conditions, and a link to the login page.
 */
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Page</title>
    <link rel="stylesheet" href="signup.css">
</head>
<body>
    <div class="wrapper">   
        <div class="title-text">
            <div class="title signup active">Signup Form</div>
            <div class="title login">Login Form</div>
        </div>
        <div class="form-container">
            <div class="form-inner">
                <form action="register.php" method="POST" class="signup">
                <input type="hidden" name="signup" value="true">
                    <div class="field-container">
                        <div class="field">
                            <input type="text" name="first_name" placeholder="First Name" required id="firstName">
                            <small class="error-message" id="firstNameError"></small>
                        </div>
                        <div class="field">
                            <input type="text" name="last_name" placeholder="Last Name" required id="lastName">
                            <small class="error-message" id="lastNameError"></small>
                        </div>
                        <div class="field">
                            
                            <select name="sex" id="sex" required>
                                <option value="" disabled selected>Select your sex</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="field" >
                            <input type="text" name="student_id" placeholder="Student ID (YYYY-NNNNN)" required id="studentID">
                            <small class="error-message" id="studentIDError"></small>
                        </div>
                        <div class="field" >
                            <input type="email" name="email" placeholder="Email Address" required id="signupEmail">
                            <small class="error-message" id="emailError"></small>
                        </div>  
                        <div class="field password-field">
                            <input type="password" name="password" placeholder="Password" required id="signupPassword">
                            <button type="button" class="toggle-password" onclick="togglePasswordVisibility('signupPassword')">👁️</button>
                            <small class="password-strength" id="passwordStrength"></small>
                        </div>
                        <div class="field password-field">
                            <input type="password" placeholder="Confirm Password" required id="confirmPassword">
                            <button type="button" class="toggle-password" onclick="togglePasswordVisibility('confirmPassword')">👁️</button>
                            <small class="error-message" id="confirmError"></small>
                        </div>
                    </div>
                    <div class="field remember-me">
                        <input type="checkbox" id="terms" required>
                        <label for="terms">I agree to the Terms and Conditions</label>
                    </div>
                    <div class="field">
                        <input type="submit" value="Signup">
                    </div>
                    <div class="signup-link">Already a Member? <a href="login.php">Login Now</a></div>
                </form>
            </div>
        </div>  
    </div>
    
    <script src="signup.js"></script>
</body>
</html>

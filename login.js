/**
 * Handles the login form functionality, including email validation, password visibility toggling, and form submission.
 * 
 * The login form is selected using the CSS selector "form.login".
 * The email input field is selected using the ID "loginEmail".
 * The password input field is selected using the ID "loginPassword".
 * The email error message element is selected using the ID "emailError".
 * The password visibility toggle button is selected using the ID "togglePassword".
 * 
 * The email input field is validated on input, and an error message is displayed if the email is not valid.
 * The password visibility can be toggled by clicking the password visibility toggle button.
 * When the login form is submitted, the "remember me" checkbox state is logged to the console, and the actual login logic should be implemented.
 */
const loginForm = document.querySelector("form.login");
const emailInput = document.getElementById("loginEmail");
const passwordInput = document.getElementById("loginPassword");
const emailError = document.getElementById("emailError");
const togglePassword = document.getElementById("togglePassword");

// Email validation
emailInput.addEventListener('input', () => {
    const emailValue = emailInput.value;
    const isValidEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailValue);
    emailError.textContent = isValidEmail ? '' : 'Please enter a valid email address';
});

// Toggle password visibility
togglePassword.addEventListener('click', () => {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    togglePassword.textContent = type === 'password' ? '👁️' : '🙈'; // Change icon
});

// Add real-time password strength indicator
passwordInput.addEventListener('input', () => {
    const strength = checkPasswordStrength(passwordInput.value);
    updatePasswordStrengthUI(strength);
});

function checkPasswordStrength(password) {
    let strength = 0;
    if(password.length >= 8) strength++;
    if(/[A-Z]/.test(password)) strength++;
    if(/[a-z]/.test(password)) strength++;
    if(/[0-9]/.test(password)) strength++;
    if(/[^A-Za-z0-9]/.test(password)) strength++;
    return strength;
}

function showError(message) {
    const errorPopup = document.getElementById('errorPopup');
    const errorMessage = document.getElementById('errorMessage');
    errorMessage.textContent = message;
    errorPopup.style.display = 'block';
    
    setTimeout(() => {
        errorPopup.style.display = 'none';
    }, 3000);
}

// Login form submission
loginForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    if (!validateForm()) {
        return;
    }
    
    const formData = new FormData(loginForm);
    
    try {
        const response = await fetch('login.php', {
            method: 'POST',
            body: formData
        });
        
        if (!response.ok) {
            showError('Invalid email or password');
            return;
        }
        
        const data = await response.json();
        window.location.href = data.role === 'admin' ? 'admin.php' : 'lobby.php';
        
    } catch (error) {
        showError('Invalid email or password');
    }
});

// Add close button functionality
document.querySelector('.close-btn').addEventListener('click', () => {
    document.getElementById('errorPopup').style.display = 'none';
});
function validateForm() {
    const email = emailInput.value.trim();
    const password = passwordInput.value.trim();
    let isValid = true;
    
    if (!email) {
        emailError.textContent = 'Email is required';
        isValid = false;
    }
    
    if (!password) {
        passwordError.textContent = 'Password is required';
        isValid = false;
    }
    
    return isValid;
}
 

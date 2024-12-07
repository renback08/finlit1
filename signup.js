/**
 * Validates the email input field in real-time and displays an error message if the email is invalid.
 */
// Real-Time Email Validation
const emailInput = document.getElementById("signupEmail");
const emailError = document.getElementById("emailError");

emailInput.addEventListener('input', () => {
    const emailValue = emailInput.value;
    const isValidEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailValue);
    emailError.textContent = isValidEmail ? '' : 'Please enter a valid email address';
});

// Student ID Validation
const studentIDInput = document.getElementById("studentID");
const studentIDError = document.getElementById("studentIDError");

studentIDInput.addEventListener('input', () => {
    const studentIDValue = studentIDInput.value;
    const isValidStudentID = /^\d{4}-\d{5}$/.test(studentIDValue);
    studentIDError.textContent = isValidStudentID ? '' : 'Please enter a valid Student ID (YYYY-NNNNN)';
});

// Password Strength Indicator
const passwordInput = document.getElementById("signupPassword");
const passwordStrength = document.getElementById("passwordStrength");

passwordInput.addEventListener('input', () => {
    const passwordValue = passwordInput.value;
    let strength = '';

    if (passwordValue.length < 6) {
        strength = 'Too short';
    } else if (/[A-Z]/.test(passwordValue) && /[0-9]/.test(passwordValue)) {
        strength = 'Strong';
    } else {
        strength = 'Weak';
    }

    passwordStrength.textContent = strength;
});
function togglePasswordVisibility(passwordFieldId) {
    const passwordField = document.getElementById(passwordFieldId);
    
    // Check if the password field exists
    if (!passwordField) {
        console.error(`Element with id ${passwordFieldId} not found`);
        return;
    }

    // Determine the current type and toggle it
    const currentType = passwordField.getAttribute('type');
    const newType = currentType === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', newType);

    // Optional: Update the button text or icon based on visibility
    const toggleButton = passwordField.nextElementSibling; // Get the button next to the input
    if (toggleButton) {
        toggleButton.textContent = newType === 'password' ? '👁️' : '🙈'; // Change icon based on visibility
    }
}
const firstNameInput = document.getElementById("firstName");
const firstNameError = document.getElementById("firstNameError");

firstNameInput.addEventListener('input', () => {
    const firstNameValue = firstNameInput.value;
    const isValidFirstName = /^[A-Za-z]+$/.test(firstNameValue) && firstNameValue.length > 0;
    firstNameError.textContent = isValidFirstName ? '' : 'Please enter a valid first name';
});

// Last Name Validation
const lastNameInput = document.getElementById("lastName");
const lastNameError = document.getElementById("lastNameError");

lastNameInput.addEventListener('input', () => {
    const lastNameValue = lastNameInput.value;
    const isValidLastName = /^[A-Za-z]+$/.test(lastNameValue) && lastNameValue.length > 0;
    lastNameError.textContent = isValidLastName ? '' : 'Please enter a valid last name';
});
// Confirm Password Validation
const confirmPasswordInput = document.getElementById("confirmPassword");
const confirmError = document.getElementById("confirmError");

confirmPasswordInput.addEventListener('input', () => {
    confirmError.textContent = confirmPasswordInput.value === passwordInput.value ? '' : 'Passwords do not match';
});

document.querySelector("form.signup").addEventListener('submit', (e) => {
    // Prevent default if there are any validation errors
    if (emailError.textContent || studentIDError.textContent || confirmError.textContent || 
        firstNameError.textContent || lastNameError.textContent) {
        e.preventDefault();
        alert("Please fix the errors before submitting.");
    } else {
        console.log("Signup form submitted");
        // Add AJAX call or continue with form submission
    }
});

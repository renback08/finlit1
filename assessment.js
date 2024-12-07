function showNextQuestion(currentQuestion) {
    const currentQuestionElement = document.getElementById('question' + currentQuestion);
    const nextQuestionElement = document.getElementById('question' + (currentQuestion + 1));
    
    if (isQuestionAnswered(currentQuestion)) {
        currentQuestionElement.style.display = 'none';
        nextQuestionElement.style.display = 'block';
    } else {
        alert('Please select an answer before proceeding.');
    }
}

function showPreviousQuestion(currentQuestion) {
    const currentQuestionElement = document.getElementById('question' + currentQuestion);
    const previousQuestionElement = document.getElementById('question' + (currentQuestion - 1));
    
    currentQuestionElement.style.display = 'none';
    previousQuestionElement.style.display = 'block';
}

function isQuestionAnswered(questionNum) {
    const questionBox = document.getElementById('question' + questionNum);
    const radioButtons = questionBox.querySelectorAll('input[type="radio"]');
    return Array.from(radioButtons).some(radio => radio.checked);
}

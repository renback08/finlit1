function toggleReview() {
    const reviewSection = document.getElementById('answersReview');
    const reviewBtn = document.querySelector('.review-btn');
    
    if (reviewSection.style.display === 'none') {
        reviewSection.style.display = 'block';
        reviewBtn.textContent = 'Hide Review';
    } else {
        reviewSection.style.display = 'none';
        reviewBtn.textContent = 'Review Answers';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const reviewQuestions = document.querySelectorAll('.review-question');
    reviewQuestions.forEach((question, index) => {
        question.addEventListener('click', () => {
            const options = question.querySelector('.review-options');
            if (options.style.display === 'none') {
                options.style.display = 'block';
            } else {
                options.style.display = 'none';
            }
        });
    });
});

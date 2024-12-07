document.addEventListener('DOMContentLoaded', function() {
    // Navigation buttons functionality
    const nextBtn = document.querySelector('.next-btn');
    const prevBtn = document.querySelector('.prev-btn');
    const backBtn = document.querySelector('.back-btn');
    const assessmentBtn = document.querySelector('.assessment-btn');

    if (nextBtn) {
        nextBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = `topic.php?id=${topicId}&content=${currentContent + 1}`;
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = `topic.php?id=${topicId}&content=${currentContent - 1}`;
        });
    }

    if (assessmentBtn) {
        assessmentBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = `assessment.php?module_id=${topicId}`;
        });
    }

    // Content box animation
    const contentBox = document.querySelector('.content-box');
    contentBox.style.opacity = '0';
    contentBox.style.transform = 'translateY(20px)';
    
    setTimeout(() => {
        contentBox.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        contentBox.style.opacity = '1';
        contentBox.style.transform = 'translateY(0)';
    }, 100);

    // Progress tracking
    function updateProgress() {
        const progress = (currentContent / 5) * 100;
        const progressBar = document.querySelector('.progress-bar');
        if (progressBar) {
            progressBar.style.width = `${progress}%`;
        }
    }

    updateProgress();
});

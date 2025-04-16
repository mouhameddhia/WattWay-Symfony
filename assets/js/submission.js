document.addEventListener('DOMContentLoaded', function() {
    // Handle response toggle functionality
    document.querySelectorAll('.toggle-responses').forEach(toggle => {
        toggle.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const responseList = document.getElementById(targetId);
            const icon = this.querySelector('i');

            if (responseList) {
                if (responseList.style.display === 'none') {
                    // Show responses
                    responseList.style.display = 'block';
                    responseList.style.maxHeight = '0px';
                    setTimeout(() => {
                        responseList.style.maxHeight = responseList.scrollHeight + 'px';
                    }, 10);
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                } else {
                    // Hide responses
                    responseList.style.maxHeight = '0px';
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                    setTimeout(() => {
                        responseList.style.display = 'none';
                    }, 300); // Match this with the CSS transition duration
                }
            }
        });
    });

    // Add hover effect for submission cards
    document.querySelectorAll('.submission-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.classList.add('hover');
        });
        card.addEventListener('mouseleave', function() {
            this.classList.remove('hover');
        });
    });

    // Add validation for vinCode
    const form = document.querySelector('#submission-form');
    if (form) {
        form.addEventListener('submit', function(event) {
            const vinCodeInput = document.querySelector('#vinCode');
            const errorMessage = document.querySelector('#vinCode-error');
            if (vinCodeInput && vinCodeInput.value.trim() === '') {
                event.preventDefault();
                if (errorMessage) {
                    errorMessage.textContent = 'VIN Code is required.';
                    errorMessage.style.display = 'block';
                }
            } else if (errorMessage) {
                errorMessage.style.display = 'none';
            }
        });
    }
});
document.addEventListener('DOMContentLoaded', function() {
    const keyTermsButtons = document.querySelectorAll('.key-terms-btn');
    const keyTermsModal = new bootstrap.Modal(document.getElementById('keyTermsModal'));
    const termsList = document.querySelector('.terms-list');



    // Add click event listeners to key terms buttons
    keyTermsButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const submissionText = this.getAttribute('data-submission-text');

            // Make AJAX request to extract key terms
            fetch('/extract-key-terms', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ text: submissionText })
            })
            .then(response => response.json())
            .then(data => {
                // Update modal content with key terms
                const termsList = document.querySelector('.terms-list');
                termsList.innerHTML = data.terms.map(term => 
                    `<div class="term-item">${term}</div>`
                ).join('');
                
                // Show the modal
                keyTermsModal.show();
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });

    // Handle modal close events
    document.getElementById('keyTermsModal').addEventListener('hidden.bs.modal', function () {
        termsList.innerHTML = '';
    });
});
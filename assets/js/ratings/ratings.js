document.addEventListener('DOMContentLoaded', function() {
    // Gestione dell'hover sulle stelle
    const starContainer = document.querySelector('.star-rating');
    if (starContainer) {
        const stars = starContainer.querySelectorAll('input[type="radio"]');
        const labels = starContainer.querySelectorAll('label');

        // Funzione per evidenziare le stelle
        function highlightStars(rating) {
            labels.forEach((label, index) => {
                if (5 - index <= rating) {
                    label.style.color = '#ffd700'; 
                } else {
                    label.style.color = '#ddd';    
                }
            });
        }

        labels.forEach((label, index) => {
            const rating = 5 - index; 

            label.addEventListener('mouseenter', () => {
                highlightStars(rating);
            });

            label.addEventListener('mouseleave', () => {
                const selectedRating = document.querySelector('input[name="rating"]:checked');
                if (selectedRating) {
                    highlightStars(selectedRating.value);
                } else {
                    highlightStars(0);
                }
            });
        });

        stars.forEach(star => {
            star.addEventListener('change', function() {
                highlightStars(this.value);
            });
        });
    }

    const messageContainer = document.querySelector('.rating-message');
    const ratingForm = document.querySelector('.rating-stars');
    if (ratingForm) {
        ratingForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageContainer.innerHTML = `<div class="success-message">${data.message}</div>`;
                    ratingForm.reset();
                    highlightStars(0);
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    messageContainer.innerHTML = `<div class="error-message">${data.message}</div>`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                messageContainer.innerHTML = `<div class="error-message">Si è verificato un errore durante l'invio</div>`;
            });
        });
    }
});
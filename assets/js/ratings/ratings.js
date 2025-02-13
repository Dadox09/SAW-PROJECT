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
                    label.style.color = '#ffd700'; // Colore oro per le stelle selezionate
                } else {
                    label.style.color = '#ddd';    // Colore grigio per le stelle non selezionate
                }
            });
        }

        // Eventi per l'hover sulle stelle
        labels.forEach((label, index) => {
            const rating = 5 - index; // Invertiamo l'indice perché le stelle sono in ordine inverso nel HTML

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

        // Evento per il click sulle stelle
        stars.forEach(star => {
            star.addEventListener('change', function() {
                highlightStars(this.value);
            });
        });
    }

    // Gestione del form
    const ratingForm = document.querySelector('.rating-stars');
    if (ratingForm) {
        ratingForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Validazione
            const rating = document.querySelector('input[name="rating"]:checked');
            const comment = document.querySelector('textarea[name="comment"]');

            if (!rating) {
                showMessage('Per favore, seleziona un punteggio', 'error');
                return;
            }

            if (!comment.value.trim()) {
                showMessage('Per favore, inserisci un commento', 'error');
                return;
            }

            // Invio del form tramite AJAX
            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('Grazie per la tua recensione!', 'success');
                    // Resetta il form
                    ratingForm.reset();
                    highlightStars(0);
                    // Ricarica la pagina per mostrare la nuova recensione
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showMessage(data.message || 'Si è verificato un errore', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('Si è verificato un errore durante l\'invio', 'error');
            });
        });
    }
});

// Funzione per mostrare messaggi
function showMessage(message, type) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'}`;
    messageDiv.textContent = message;

    const form = document.querySelector('.rating-form');
    form.insertBefore(messageDiv, form.firstChild);

    // Rimuovi il messaggio dopo 3 secondi
    setTimeout(() => {
        messageDiv.remove();
    }, 3000);
}

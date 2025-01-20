document.addEventListener('DOMContentLoaded', function() {
    const bookingForm = document.getElementById('booking-form');
    const messageContainer = document.getElementById('booking-message');

    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitButton = bookingForm.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.textContent = 'Prenotazione in corso...';

            setTimeout(() => {
                const formData = new FormData(this);
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        messageContainer.innerHTML = `<div class="success-message">${data.message}</div>`;
                        submitButton.textContent = 'Prenotazione effettuata!';
                    } else {
                        messageContainer.innerHTML = `<div class="error-message">${data.message}</div>`;
                        submitButton.disabled = false;
                        submitButton.textContent = 'Prenota';
                    }
                })
                .catch(error => {
                    messageContainer.innerHTML = '<div class="error-message">Si è verificato un errore durante la prenotazione. Riprova più tardi.</div>';
                    console.error('Error:', error);
                    submitButton.disabled = false;
                    submitButton.textContent = 'Prenota';
                });
            }, 1000);
        });
    }
});
function showNotification(message, isSuccess) {
    // Rimuovi notifiche esistenti
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }

    // Crea l'elemento notifica
    const notification = document.createElement('div');
    notification.className = `notification ${isSuccess ? 'success' : 'error'}`;
    notification.textContent = message;

    // Aggiungi la notifica al DOM
    document.body.appendChild(notification);

    // Aggiungi classe per l'animazione di entrata
    setTimeout(() => notification.classList.add('show'), 10);

    // Rimuovi la notifica dopo 5 secondi
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('newsletterForm');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const button = document.getElementById('iscrivitiBtn');
            
            // Disabilita il pulsante durante l'invio
            button.disabled = true;
            button.textContent = 'Iscrizione in corso...';

            const formData = new FormData();
            formData.append('email', email);
            
            // Invia la richiesta al server
            fetch('functions/newsletter.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, true);
                    form.reset();
                    
                    // Gestione dati aggiuntivi se presenti
                    if (data.data && data.data.emailSent === false) {
                        console.warn('Email di conferma non inviata');
                    }
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                console.error('Errore:', error);
                showNotification(error.message || 'Errore durante l\'iscrizione. Riprova più tardi.', false);
            })
            .finally(() => {
                button.disabled = false;
                button.textContent = 'Iscriviti ora!';
            });
        });
    } else {
        console.error('Form newsletter non trovato');
    }
});
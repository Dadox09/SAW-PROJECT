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
            
            // Invia la richiesta al server
            fetch('/websites/SAW-PROJECT/functions/newsletter.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'email=' + encodeURIComponent(email)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Errore del server: ' + response.status);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    form.reset();
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                console.error('Errore:', error);
                alert(error.message || 'Errore durante l\'iscrizione. Riprova più tardi.');
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
function showNotification(message, isSuccess) {
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }

    const notification = document.createElement('div');
    notification.className = `notification ${isSuccess ? 'success' : 'error'}`;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => notification.classList.add('show'), 10);

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
            
            button.disabled = true;
            button.textContent = 'Iscrizione in corso...';

            const formData = new FormData();
            formData.append('email', email);
            
            fetch('functions/newsletter.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, true);
                    form.reset();
                    
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